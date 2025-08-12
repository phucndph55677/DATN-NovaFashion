<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
    ];

    // Danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('childrenRecursive');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function hasProductsInTree()
    {
        // Kiểm tra sản phẩm của chính danh mục này
        if ($this->products()->exists()) {
            return true;
        }

        // Đệ quy kiểm tra con cháu
        foreach ($this->childrenRecursive as $child) {
            if ($child->hasProductsInTree()) {
                return true;
            }
        }

        return false;
    }
}