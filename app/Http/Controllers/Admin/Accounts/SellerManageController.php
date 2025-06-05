<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SellerManageController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role_id', 1); // role_id = 1 là seller

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('fullname', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $sellers = $query->latest()->paginate(10);

        return view('admin.accounts.sellerManage.index', compact('sellers'));
    }

    public function create()
    {
        return view('admin.accounts.sellerManage.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]{7,20}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'is_verified' => ['required', 'boolean'],
        ], [
            'fullname.required' => 'Họ và tên không được để trống.',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'is_verified.required' => 'Trạng thái xác thực là bắt buộc.',
            'is_verified.boolean' => 'Trạng thái xác thực không hợp lệ.',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['role_id'] = 1; // role_id seller là 1

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/avatars', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = null;
        }

        User::create($data);

        return redirect()->route('admin.accounts.seller-manage.index')
            ->with('success', 'Seller account created successfully.');
    }

    public function show(User $user)
    {
        if ($user->role_id != 1) {
            abort(404);
        }
        return view('admin.accounts.sellerManage.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role_id != 1) {
            abort(404);
        }
        return view('admin.accounts.sellerManage.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role_id != 1) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]{7,20}$/', 'unique:users,phone,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'is_verified' => ['required', 'boolean'],
        ], [
            'fullname.required' => 'Họ và tên không được để trống.',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'is_verified.required' => 'Trạng thái xác thực là bắt buộc.',
            'is_verified.boolean' => 'Trạng thái xác thực không hợp lệ.',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('uploads/avatars', 'public');
            $user->image = $path;
        }

        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->is_verified = $request->is_verified;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.accounts.seller-manage.index')
                         ->with('success', 'Seller account updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role_id != 1) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('admin.accounts.seller-manage.index')
            ->with('success', 'Tài khoản người bán đã được xóa thành công.');
    }
}
