<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientVoucherController extends Controller
{
    public function showVoucher()
    {
        $today = Carbon::now()->startOfDay();

        $vouchers = Voucher::where('status', 1) // chỉ lấy voucher đang hiện
        ->whereDate('end_date', '>=', $today) // còn hạn
        ->orderBy('created_at', 'desc') // ưu tiên mới nhất
        ->get();
        return view('home', compact('vouchers'));
    }
}
?>