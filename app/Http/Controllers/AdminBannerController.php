<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

 public function store(Request $request)
{
    $validated = $request->validate([
        'description' => 'required',
        'image' => 'required|image',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:0,1',
    ]);

    $validated['image'] = $request->file('image')->store('banners', 'public');

    Banner::create([
        'description' => $validated['description'],
        'image' => $validated['image'],
        'start_date' => Carbon::parse($validated['start_date']),
        'end_date' => Carbon::parse($validated['end_date']),
        'status' => $validated['status'],
    ]);

    return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
}

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
{
    $validated = $request->validate([
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu có
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $validated['image'] = $request->file('image')->store('banners', 'public');
    }

    // Gán thời gian nếu có
    $validated['start_date'] = $request->start_date;
    $validated['end_date'] = $request->end_date;

    // Giữ lại title cũ vì form không cho sửa
    $validated['title'] = $banner->title;

    $banner->update($validated);

    return redirect()->route('admin.banners.index')->with('success', 'Banner has been updated successfully.');
}

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner has been deleted successfully.');
    }
}
