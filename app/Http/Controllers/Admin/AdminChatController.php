<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatDetail;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy danh sách tất cả các chat + user + tin nhắn cuối cùng
        $chats = Chat::with(['user', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        // Nếu có truyền chat_id thì load chi tiết toàn bộ tin nhắn
        $chatDetails = null;
        if ($chatId = $request->get('chat_id')) {
            $chatDetails = Chat::with([
                'user',
                'chatDetails.sender',
                'chatDetails.receiver'
            ])->find($chatId);
        }

        return view('admin.chats.index', compact('chats', 'chatDetails'));
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

            // Kiểm tra và tạo chat test nếu cần
            $chatId = $request->chat_id;
            $chat = Chat::find($chatId);

            if (!$chat) {
                // Tạo chat test với user đầu tiên
                $firstUser = \App\Models\User::first();
                if (!$firstUser) {
                    throw new \Exception('No users found in database');
                }

                $chat = Chat::create([
                    'user_id' => $firstUser->id,
                    'last_message_at' => now(),
                ]);

                $chatId = $chat->id;
            }

            $request->merge(['chat_id' => $chatId]);

            $request->validate([
                'chat_id' => 'required|exists:chats,id',
                'message' => 'required|string|max:1000',
            ]);

            // Tạo tin nhắn mới - sử dụng guard admin
            $chatDetail = ChatDetail::create([
                'chat_id' => $chatId,
                'sender_id' => Auth::guard('admin')->id(), // Sử dụng guard admin
                'receiver_id' => $chat->user_id, // Gửi cho user
                'message' => $request->message,
                'is_read' => false,
            ]);

            // Cập nhật thời gian tin nhắn cuối
            $chat->update([
                'last_message_at' => now(),
            ]);

            // Load relationship để broadcast
            $chatDetail->load('sender.role');

            // Broadcast event với sync driver để đảm bảo realtime
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

    /**
     * Get chat messages for a specific chat
     */
    public function getMessages($chatId)
    {
        $chat = Chat::with(['chatDetails.sender.role'])->findOrFail($chatId);

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
