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
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'voucher_code' => 'required|string|unique:vouchers,voucher_code',
                'sale_price' => 'required|numeric|max:100',
                'max_discount' => 'required|numeric|min:1',
                'min_price' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|min:1',
                'user_limit' => 'required|numeric|min:1|lte:quantity',                
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required',
                'description' => 'nullable|string',
            ],
            [
                'voucher_code.required' => 'Mã voucher không được để trống.',
                'voucher_code.string' => 'Mã voucher phải là chuỗi.',
                'voucher_code.unique' => 'Mã voucher đã tồn tại.',
                'sale_price.required' => 'Giá giảm không được để trống.',
                'sale_price.numeric' => 'Giá giảm phải là số.',
                'sale_price.max' => 'Giá giảm không được vượt quá 100%.',
                'max_discount.required' => 'Giảm giá tối đa không được để trống.',
                'max_discount.numeric' => 'Giảm giá tối đa phải là số.',
                'max_discount.min' => 'Giảm giá tối đa lớn hơn 0.',
                'min_price.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
                'min_price.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
                'min_price.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn 0.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.numeric' => 'Số lượng phải là số.',
                'quantity.min' => 'Số lượng phải lớn hơn 0.',
                'user_limit.required' => 'Số lượt mỗi người dùng không được để trống.',
                'user_limit.numeric' => 'Số lượt mỗi người dùng phải là số.',
                'user_limit.min' => 'Số lượt mỗi người dùng phải lớn hơn 0.',
                'user_limit.lte' => 'Số lượt mỗi người dùng phải <= số lượng voucher.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải >= ngày bắt đầu.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'description.string' => 'Mô tả phải là chuỗi.',
            ]
        );

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index');
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
        $voucher = Voucher::findOrFail($id);
        $statuses = [
            (object)['id' => 1, 'name' => 'On'],
            (object)['id' => 0, 'name' => 'Off'],
        ];
        
        return view('admin.vouchers.edit', compact('voucher', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $data = $request->validate(
            [
                'voucher_code' => [
                    'required',
                    'string',
                    Rule::unique('vouchers', 'voucher_code')->ignore($voucher->id),
                ],
                'sale_price' => 'required|numeric|max:100',
                'max_discount' => 'required|numeric|min:1',
                'min_price' => 'required|numeric|min:1',
                'quantity' => 'required|numeric|min:1',
                'user_limit' => 'required|numeric|min:1|lte:quantity',                
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required',
                'description' => 'nullable|string',
            ],
            [
                'voucher_code.required' => 'Mã voucher không được để trống.',
                'voucher_code.string' => 'Mã voucher phải là chuỗi.',
                'voucher_code.unique' => 'Mã voucher đã tồn tại.',
                'sale_price.required' => 'Giá giảm không được để trống.',
                'sale_price.numeric' => 'Giá giảm phải là số.',
                'sale_price.max' => 'Giá giảm không được vượt quá 100%.',
                'max_discount.required' => 'Giảm giá tối đa không được để trống.',
                'max_discount.numeric' => 'Giảm giá tối đa phải là số.',
                'max_discount.min' => 'Giảm giá tối đa lớn hơn 0.',
                'min_price.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
                'min_price.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
                'min_price.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn 0.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.numeric' => 'Số lượng phải là số.',
                'quantity.min' => 'Số lượng phải lớn hơn 0.',
                'user_limit.required' => 'Số lượt mỗi người dùng không được để trống.',
                'user_limit.numeric' => 'Số lượt mỗi người dùng phải là số.',
                'user_limit.min' => 'Số lượt mỗi người dùng phải lớn hơn 0.',
                'user_limit.lte' => 'Số lượt mỗi người dùng phải <= số lượng voucher.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải >= ngày bắt đầu.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'description.string' => 'Mô tả phải là chuỗi.',
            ]
        );

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index');
    }
}
