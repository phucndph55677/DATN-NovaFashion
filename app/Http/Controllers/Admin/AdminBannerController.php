<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::all();
        
        return view('admin.banners.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255',                
                'location_id' => 'required|exists:locations,id',
                'product_link' => 'required|url',
                'status' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'image' => 'required|image|max:2048',
            ],
            [
                'name.required' => 'Tên banner không được để trống.',
                'name.string' => 'Tên banner phải là chuỗi.',
                'name.max' => 'Tên banner không được dài quá 255 ký tự.',
                'location_id.required' => 'Vui lòng chọn vị trí banner.',
                'location_id.exists' => 'Vị trí banner không hợp lệ.',
                'product_link.required' => 'Liên kết sản phẩm không được để trống.',
                'product_link.url' => 'Liên kết sản phẩm phải là một đường dẫn hợp lệ.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
                'image.required' => 'Hình ảnh không được để trống.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );

        // Xử lý ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::query()->create($data);

        return redirect()->route('admin.banners.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $locations = Location::all();
        $banner = Banner::findOrFail($id);
        $statuses = [
            (object)['id' => 1, 'name' => 'On'],
            (object)['id' => 0, 'name' => 'Off'],
        ];

        return view('admin.banners.edit', compact('locations', 'banner', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $data = $request->validate(
            [
                'name' => 'required|string|max:255',                
                'location_id' => 'required|exists:locations,id',
                'product_link' => 'required|url',
                'status' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'image' => 'nullable|image|max:2048',
            ],
            [
                'name.required' => 'Tên banner không được để trống.',
                'name.string' => 'Tên banner phải là chuỗi.',
                'name.max' => 'Tên banner không được dài quá 255 ký tự.',
                'location_id.required' => 'Vui lòng chọn vị trí banner.',
                'location_id.exists' => 'Vị trí banner không hợp lệ.',
                'product_link.required' => 'Liên kết sản phẩm không được để trống.',
                'product_link.url' => 'Liên kết sản phẩm phải là một đường dẫn hợp lệ.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'start_date.required' => 'Ngày bắt đầu không được để trống.',
                'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
                'end_date.required' => 'Ngày kết thúc không được để trống.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if($banner ->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index');
    }
}
