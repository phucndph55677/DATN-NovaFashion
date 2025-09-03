<?php

namespace App\Http\Controllers\Client;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy danh sách tất cả các chat + user + tin nhắn cuối cùng (tuỳ mục đích mở rộng UI)
        $chats = Chat::with(['user', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        // Tìm hoặc tạo chat mặc định cho user hiện tại với admin đầu tiên
        $defaultChat = Chat::firstOrCreate(
            ['user_id' => $userId],
            [
                'admin_id' => User::where('role_id', 1)->value('id'),
                'last_message_at' => now(),
            ]
        );

        // Ưu tiên chat_id từ request, nếu không có thì dùng chat mặc định của user
        $targetChatId = $request->get('chat_id', $defaultChat->id);

        $chatDetails = Chat::with([
            'user',
            'chatDetails.sender',
            'chatDetails.receiver'
        ])->find($targetChatId);

        return view('client.chats.index', compact('chats', 'chatDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $chat = Chat::findOrFail($request->chat_id);

            // Chỉ cho chủ nhân chat gửi
            if ($chat->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Forbidden'], 403);
            }

            $request->validate([
                'chat_id' => 'required|exists:chats,id',
                'message' => 'required|string|max:1000',
            ]);

            // Tạo tin nhắn mới
            $chatDetail = ChatDetail::create([
                'chat_id' => $chat->id,
                'sender_id' => Auth::id(),
                'receiver_id' => $chat->admin_id,
                'message' => $request->message,
            ]);
            
            // Cập nhật thời gian tin nhắn cuối
            $chat->update([
                'last_message_at' => now()
            ]);

            // Load relationship để broadcast
            $chatDetail->load('sender.role');

            // Đảm bảo broadcast sync như phía admin
            config(['queue.default' => 'sync']);
            broadcast(new MessageSent($chatDetail))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $chatDetail,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMessages($chatId)
    {
        $chat = Chat::with(['chatDetails.sender.role'])
            ->where('id', $chatId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'chat' => $chat,
            'messages' => $chat->chatDetails,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
