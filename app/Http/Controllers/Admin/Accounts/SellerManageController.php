<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SellerManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sellers = User::where('role_id', 2)->get();

        return view('admin.accounts.sellerManage.index', compact('sellers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accounts.sellerManage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:30',
                'email' => 'required|string|email|unique:users,email',
                'phone' => 'required|string|regex:/^0\d{9}$/|unique:users,phone',
                'password' => 'required|string|min:8',
                'status' => 'required',
                'image' => 'nullable|image|max:2048',
                'address' => 'nullable|string|max:255',
            ],
            [
                'name.required' => 'Tên seller không được để trống.',
                'name.string' => 'Tên seller phải là chuỗi.',
                'name.max' => 'Tên seller không được vượt quá 30 ký tự.',
                'email.required' => 'Email không được để trống.',
                'email.string' => 'Email phải là chuỗi.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email này đã được đăng ký.',
                'phone.required' => 'Số điện thoại không được để trống.',
                'phone.string' => 'Số điện thoại phải là chuỗi.',
                'phone.regex' => 'Số điện thoại không đúng định dạng.',
                'phone.unique' => 'Số điện thoại này đã được sử dụng.',
                'password.required' => 'Password không được để trống.',
                'password.string' => 'Password phải là chuỗi.',
                'password.min' => 'Password phải có ít nhất 8 ký tự.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
                'address.string' => 'Địa chỉ phải là chuỗi.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            ]
        ); 
        
        $data['role_id'] = 2; // role_id seller là 2

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        User::query()->create($data);

        return redirect()->route('admin.accounts.seller-manage.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $seller = User::where('role_id', 2)->findOrFail($id);
        $statuses = [
            (object)['id' => 1, 'name' => 'Active'],
            (object)['id' => 0, 'name' => 'Inactive'],
        ];

        return view('admin.accounts.sellerManage.edit', compact('seller', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $seller = User::where('role_id', 2)->findOrFail($id);

        $data = $request->validate(
            [
                'name' => 'required|string|max:30',
                'email' => [
                    'required',
                    'string',
                    'email',
                    Rule::unique('users', 'email')->ignore($seller->id),
                ],

                'phone' => [
                    'required',
                    'string',
                    'regex:/^0\d{9}$/',
                    Rule::unique('users', 'phone')->ignore($seller->id),
                ],
                'password' => 'nullable|string|min:8',
                'status' => 'required',
                'image' => 'nullable|image|max:2048',
                'address' => 'nullable|string|max:255',
            ],
            [
                'name.required' => 'Tên seller không được để trống.',
                'name.string' => 'Tên seller phải là chuỗi.',
                'name.max' => 'Tên seller không được vượt quá 30 ký tự.',
                'email.required' => 'Email không được để trống.',
                'email.string' => 'Email phải là chuỗi.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email này đã được đăng ký.',
                'phone.required' => 'Số điện thoại không được để trống.',
                'phone.string' => 'Số điện thoại phải là chuỗi.',
                'phone.regex' => 'Số điện thoại không đúng định dạng.',
                'phone.unique' => 'Số điện thoại này đã được sử dụng.',
                'password.string' => 'Password phải là chuỗi.',
                'password.min' => 'Password phải có ít nhất 8 ký tự.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
                'address.string' => 'Địa chỉ phải là chuỗi.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            ]
        ); 

        // Xử lý password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('image')) {
            if ($seller->image) {
                Storage::disk('public')->delete($seller->image);
            }
            $data['image'] = $request->file('image')->store('users', 'public');
           
        }

        $seller->update($data);

        return redirect()->route('admin.accounts.seller-manage.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seller = User::where('role_id', 2)->findOrFail($id);

        if($seller ->image) {
            Storage::disk('public')->delete($seller->image);
        }

        $seller->delete();

        return redirect()->route('admin.accounts.seller-manage.index');
    }
}
