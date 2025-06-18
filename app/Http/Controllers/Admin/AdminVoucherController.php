<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;

class AdminVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'client', 'seller'])->get();
        return view('admin.vouchers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'voucher_code' => 'required|string|unique:vouchers,voucher_code',
                'quantity' => 'required|integer|min:1',
                'sale_price' => 'required|string|numeric|max:100',
                'min_price' => 'required|string',
                'max_price' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'name.required' => 'Tên voucher không được để trống.',
                'name.string' => 'Tên voucher phải là chuỗi.',
                'name.max' => 'Tên voucher không được vượt quá 255 ký tự.',
                'voucher_code.required' => 'Mã voucher không được để trống.',
                'voucher_code.string' => 'Mã voucher phải là chuỗi.',
                'voucher_code.unique' => 'Mã voucher đã tồn tại.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.integer' => 'Số lượng phải là số nguyên.',
                'quantity.min' => 'Số lượng phải lớn hơn 0.',
                'sale_price.required' => 'Giá giảm không được để trống.',
                'sale_price.max' => 'Giá giảm không được vượt quá 100%.',
                'min_price.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
                'max_price.required' => 'Giá trị đơn hàng tối đa không được để trống.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
                'role_id.required' => 'Vui lòng chọn đối tượng áp dụng.',
                'role_id.exists' => 'Vai trò không tồn tại.',
            ]
        );

        // Convert formatted currency strings to numbers
        $salePrice = (float) str_replace('.', '', $data['sale_price']);
        $minPrice = (float) str_replace('.', '', $data['min_price']);
        $maxPrice = (float) str_replace('.', '', $data['max_price']);

        // Validate numeric values
        if ($salePrice < 0) {
            return back()->withErrors(['sale_price' => 'Giá giảm phải lớn hơn hoặc bằng 0.'])->withInput();
        }
        if ($minPrice < 0) {
            return back()->withErrors(['min_price' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.'])->withInput();
        }
        if ($maxPrice <= $minPrice) {
            return back()->withErrors(['max_price' => 'Giá trị đơn hàng tối đa phải lớn hơn giá trị tối thiểu.'])->withInput();
        }

        // Map form fields to database columns
        $voucherData = [
            'name' => $data['name'],
            'voucher_code' => $data['voucher_code'],
            'sale_price' => $salePrice,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'quantity' => $data['quantity'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'role_id' => $data['role_id'],
            'status' => true,
        ];

        Voucher::create($voucherData);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $roles = Role::all();
        return view('admin.vouchers.edit', compact('voucher', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'voucher_code' => [
                    'required',
                    'string',
                    Rule::unique('vouchers', 'voucher_code')->ignore($voucher->id),
                ],
                'quantity' => 'required|integer|min:1',
                'sale_price' => 'required|string|numeric|max:100',
                'min_price' => 'required|string',
                'max_price' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'role_id' => 'nullable|exists:roles,id',
            ],
            [
                'name.required' => 'Tên voucher không được để trống.',
                'name.string' => 'Tên voucher phải là chuỗi.',
                'name.max' => 'Tên voucher không được vượt quá 255 ký tự.',
                'voucher_code.required' => 'Mã voucher không được để trống.',
                'voucher_code.string' => 'Mã voucher phải là chuỗi.',
                'voucher_code.unique' => 'Mã voucher đã tồn tại.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.integer' => 'Số lượng phải là số nguyên.',
                'quantity.min' => 'Số lượng phải lớn hơn 0.',
                'sale_price.required' => 'Giá giảm không được để trống.',
                'sale_price.max' => 'Giá giảm không được vượt quá 100%.',
                'min_price.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
                'max_price.required' => 'Giá trị đơn hàng tối đa không được để trống.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
                'role_id.exists' => 'Vai trò không tồn tại.',

            ]
        );

        // Convert formatted currency strings to numbers
        $salePrice = (float) str_replace('.', '', $data['sale_price']);
        $minPrice = (float) str_replace('.', '', $data['min_price']);
        $maxPrice = (float) str_replace('.', '', $data['max_price']);

        // Validate numeric values
        if ($salePrice < 0) {
            return back()->withErrors(['sale_price' => 'Giá giảm phải lớn hơn hoặc bằng 0.'])->withInput();
        }
        if ($minPrice < 0) {
            return back()->withErrors(['min_price' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.'])->withInput();
        }
        if ($maxPrice <= $minPrice) {
            return back()->withErrors(['max_price' => 'Giá trị đơn hàng tối đa phải lớn hơn giá trị tối thiểu.'])->withInput();
        }

        // Map form fields to database columns
        $voucherData = [
            'name' => $data['name'],
            'voucher_code' => $data['voucher_code'],
            'sale_price' => $salePrice,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'quantity' => $data['quantity'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'role_id' => $data['role_id'],
            'status' => true,
        ];

        $voucher->update($voucherData);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công.');
    }
}
