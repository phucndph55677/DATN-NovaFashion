<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Review;
use Illuminate\Validation\Rule;

class ClientManageController extends Controller
{
    /**
     * Danh sách khách hàng (role_id = 3).
     */
    public function index(Request $request)
    {
        $clients = User::where('role_id', 3)->get();
        return view('admin.accounts.clientManage.index', compact('clients'));
    }

    /**
     * Chi tiết khách hàng.
     */
    public function show(string $id)
    {
        $client = User::findOrFail($id);
        
        // Lấy reviews thông qua orders của user
        $reviews = Review::whereHas('order', function($query) use ($id) {
            $query->where('user_id', $id);
        })->with('product')->get();

        return view('admin.accounts.clientManage.show', compact('client', 'reviews'));
    }

    /**
     * Form sửa khách hàng.
     */
    public function edit(string $id)
    {
        $client = User::where('role_id', 3)->findOrFail($id);

        $statuses = [
            (object)['id' => 1, 'name' => 'Hoạt Động'],
            (object)['id' => 0, 'name' => 'Không Hoạt Động'],
        ];

        return view('admin.accounts.clientManage.edit', compact('client', 'statuses'));
    }

    /**
     * Cập nhật khách hàng (KHÔNG dùng ranking).
     */
    public function update(Request $request, string $id)
    {
        $client = User::where('role_id', 3)->findOrFail($id);

        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email', Rule::unique('users','email')->ignore($client->id)],
            'phone'   => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:255'],
            'status'  => ['required','in:0,1'],
        ], [
            'name.required'   => 'Vui lòng nhập tên.',
            'email.required'  => 'Vui lòng nhập email.',
            'email.email'     => 'Email không hợp lệ.',
            'email.unique'    => 'Email đã tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $client->fill($data)->save();

        return redirect()
            ->route('admin.accounts.client-manage.index')
            ->with('success', 'Cập nhật khách hàng thành công.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function destroy(string $id) {}
}
