<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use NumberFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách hóa đơn đã phát hành
        $invoices = Invoice::with([
                'order.user',
                'order.paymentStatus',
                'order.orderStatus'
            ])
            ->whereHas('order', function ($query) {
                $query->where('order_status_id', 6)
                      ->where('payment_status_id', 2);
            })
            ->latest()
            ->get();

        return view('admin.invoices.index', compact('invoices'));
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
        // Lấy hóa đơn theo id và load luôn đơn hàng liên quan
        $invoice = Invoice::with(['order.orderDetails.productVariant.product'
            ])->findOrFail($id);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function printInvoice($id)
    {
        // Lấy hóa đơn theo id và load luôn đơn hàng liên quan
        $invoice = Invoice::with(['order.orderDetails.productVariant.product'])->findOrFail($id);

        // Lấy order từ invoice
        $order = $invoice->order;

        // Nếu muốn set ngày xuất (issue_date) khi in lần đầu
        if (is_null($invoice->issue_date)) {
            $invoice->issue_date = now();
            $invoice->save();
        }

        // Chuyển số tiền sang chữ
        // $formatter = new NumberFormatter('vi', NumberFormatter::SPELLOUT);
        // $totalInWords = $formatter->format($order->total_amount);

        // Truyền dữ liệu sang view
        $pdf = Pdf::loadView('admin.invoices.print', compact('invoice', 'order'));

        // Nếu muốn tải file
        // return $pdf->download('invoice-'.$order->order_code.'.pdf');

        // Mở PDF trên trình duyệt (stream) với tên file dựa trên mã hóa đơn
        return $pdf->stream('invoice-' . ($invoice->invoice_code ?? $order->order_code) . '.pdf');
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
