<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_code',
        'name',
        'address',
        'phone',
        'email',
        'status',
        'total',
        'issue_date',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
