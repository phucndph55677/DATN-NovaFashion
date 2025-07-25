<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhotoAlbum extends Model
{
    /** @use HasFactory<\Database\Factories\ProductPhotoAlbumFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
