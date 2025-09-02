<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\ChatBotSession;
use App\Models\ChatBotMessage;
use Illuminate\Support\Facades\Log;

class ClientChatBotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessionCode = request('session_code');
        $isLoggedIn = Auth::check();
        $session = null;
        $messages = [];

        if ($isLoggedIn) {
            // Nếu có session_code, tìm session đó
            if ($sessionCode) {
                $session = ChatBotSession::where('session_code', $sessionCode)
                    ->where('user_id', Auth::id())
                    ->first();
            }

            // Lấy lịch sử chat của user (không phụ thuộc session_code)
            $messages = ChatBotMessage::whereHas('session', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('id')
            ->limit(50)
            ->get(['sender_type as role', 'message as text', 'chat_bot_session_id'])
            ->toArray();

            // Nếu không có session, tạo session mới
            if (!$session) {
                $session = ChatBotSession::create([
                    'user_id' => Auth::id(),
                    'session_code' => (string) Str::uuid(),
                    'title' => 'Cuộc trò chuyện',
                ]);
            }
        }

        return response()->json([
            'session_code' => $session?->session_code,
            'messages' => $messages,
            'is_logged_in' => $isLoggedIn,
            'can_save_history' => $isLoggedIn,
            'session_invalid' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'session_code' => ['nullable','string'],
            'message' => ['required','string','max:4000'],
        ]);

        $isLoggedIn = Auth::check();
        $session = null;
        $sessionCode = null;

        // Chỉ tìm hoặc tạo session nếu user đã đăng nhập
        if ($isLoggedIn) {
            if (!empty($data['session_code'])) {
                $session = ChatBotSession::where('session_code', $data['session_code'])
                    ->where('user_id', Auth::id()) // Đảm bảo session thuộc về user hiện tại
                    ->first();

                // Nếu session không tồn tại hoặc không thuộc về user hiện tại, tạo session mới
                if (!$session) {
                    $session = ChatBotSession::create([
                        'user_id' => Auth::id(),
                        'session_code' => (string) Str::uuid(),
                        'title' => 'Cuộc trò chuyện',
                    ]);
                }
            } else {
                // Tạo session mới nếu không có session_code
                $session = ChatBotSession::create([
                    'user_id' => Auth::id(),
                    'session_code' => (string) Str::uuid(),
                    'title' => 'Cuộc trò chuyện',
                ]);
            }
            $sessionCode = $session->session_code;
        }

        // Chỉ lưu tin nhắn nếu user đã đăng nhập
        $userMsg = null;
        if ($isLoggedIn && $session) {
            try {
                $userMsg = ChatBotMessage::create([
                    'chat_bot_session_id' => $session->id,
                    'sender_type' => 'user',
                    'message' => $data['message'],
                ]);
            } catch (\Throwable $e) {
                // omit noisy logs
            }
        }

        // Sử dụng OpenAI API key
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $openAiOrg = env('OPENAI_ORG');

        $responseText = '';
        try {
            // Xây dựng context chat
            $history = [];
            if ($isLoggedIn && $session) {
                // Lấy lịch sử chat từ database nếu user đã đăng nhập
                $history = ChatBotMessage::where('chat_bot_session_id', $session->id)
                    ->orderBy('id')
                    ->limit(20) // Tăng limit để có context tốt hơn
                    ->get()
                    ->map(function($m){
                        return [
                            'role' => $m->sender_type === 'user' ? 'user' : 'assistant',
                            'content' => $m->message,
                        ];
                    })->values()->all();
            } else {
                // Nếu chưa đăng nhập, chỉ gửi tin nhắn hiện tại
                $history = [
                    [
                        'role' => 'user',
                        'content' => $data['message'],
                    ]
                ];
            }

            // Thêm system prompt để định hướng bot (VN)
            $systemPrompt = [
                'role' => 'system',
                'content' => implode("\n", [
                    'Bạn là Nova AI, trợ lý tư vấn thời trang của NovaFashion. Ngôn ngữ mặc định: tiếng Việt.',
                    'Mục tiêu: trả lời NGẮN GỌN, RÕ RÀNG, ưu tiên gạch đầu dòng khi liệt kê.',
                    'Luôn tập trung vào: sản phẩm, size, màu, giá, bảo hành, đổi trả, vận chuyển, theo dõi đơn.',
                    'Nếu câu hỏi mơ hồ hoặc viết tắt (vd: "kh", "ko", "ship", "sp"), hãy suy đoán ý nghĩa phổ biến và hỏi 1 câu làm rõ trước khi trả lời chi tiết.',
                    'Nếu không chắc thông tin, nói rõ bạn chưa chắc và đề xuất bước tiếp theo (ví dụ: gửi link sản phẩm, mã đơn hàng).',
                    'Khi user đổi chủ đề, trả lời theo chủ đề mới; chỉ dùng context cũ khi user thể hiện muốn tiếp tục.',
                    'Tránh bịa đặt. Không trả lời ngoài phạm vi cửa hàng trừ khi user yêu cầu chung.',
                ])
            ];

            // Thêm system prompt vào đầu history
            array_unshift($history, $systemPrompt);

            // Dùng model IDs của OpenAI
            $models = ['gpt-4o-mini', 'gpt-3.5-turbo'];
            foreach ($models as $mdl) {
                $payload = [
                    'model' => $mdl,
                    'messages' => $history,
                    // Nghiêng về câu trả lời đi thẳng vấn đề, ổn định
                    'temperature' => 0.3,
                    'max_tokens' => 350,
                ];

                $headers = [
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                ];
                if ($openAiOrg) {
                    $headers['OpenAI-Organization'] = $openAiOrg;
                }
                $apiRes = Http::withHeaders($headers)
                    ->timeout(30)
                    ->post('https://api.openai.com/v1/chat/completions', $payload);

                if ($apiRes->successful()) {
                    $choices = $apiRes->json('choices');
                    $responseText = $choices[0]['message']['content'] ?? '';
                    break;
                } else {
                    continue;
                }
            }

            if ($responseText === '') {
                $responseText = 'Xin lỗi, hiện mình không thể phản hồi. Vui lòng thử lại sau.';
            }
        } catch (\Throwable $e) {
            $responseText = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
        }

        // Chỉ lưu tin nhắn bot nếu user đã đăng nhập
        $botMsg = null;
        if ($isLoggedIn && $session) {
            try {
                $botMsg = ChatBotMessage::create([
                    'chat_bot_session_id' => $session->id,
                    'sender_type' => 'bot',
                    'message' => $responseText,
                ]);
            } catch (\Throwable $e) {
                // omit noisy logs
            }
        }

        return response()->json([
            'session_code' => $sessionCode,
            'messages' => [
                [ 'role' => 'user', 'text' => $data['message'] ],
                [ 'role' => 'bot', 'text' => $responseText ],
            ],
            'is_logged_in' => $isLoggedIn,
            'can_save_history' => $isLoggedIn,
        ]);
    }

}
