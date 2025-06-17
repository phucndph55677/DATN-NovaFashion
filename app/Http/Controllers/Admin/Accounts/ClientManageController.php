<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Ranking;

class ClientManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = User::where('role_id', 3)->get();

        return view('admin.accounts.clientManage.index', compact('clients'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = User::findOrFail($id);
        $reviews = $client->reviews()->with('user')->get();

        return view('admin.accounts.clientManage.show', compact('client', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = User::where('role_id', 3)->findOrFail($id);
        $rankings = Ranking::all(); // Lấy toàn bộ danh sách ranking
        $statuses = [
            (object)['id' => 1, 'name' => 'Active'],
            (object)['id' => 0, 'name' => 'Inactive'],
        ];

        return view('admin.accounts.clientManage.edit', compact('client', 'rankings', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = User::where('role_id', 3)->findOrFail($id);

        $data = $request->validate(
            [
                'ranking_id' => 'required',
                'status' => 'required',
            ],
            [
                'ranking_id.required' => 'Vui lòng chọn xếp hạng.',
                'status.required' => 'Vui lòng chọn trạng thái.',
            ]
        );

        $client->update($data);

        return redirect()->route('admin.accounts.client-manage.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = User::where('role_id', 3)->findOrFail($id);

        if($client ->image) {
            Storage::disk('public')->delete($client->image);
        }

        $client->delete();

        return redirect()->route('admin.accounts.client-manage.index');
    }
}
