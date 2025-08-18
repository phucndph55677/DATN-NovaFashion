<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function getAllDescendantIds()
    {
        $ids = [];

        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return $ids;
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

    // 🔥 Auto tạo slug khi lưu
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            // Nếu slug chưa có hoặc thay đổi parent_id/name thì cập nhật lại
            if (
                empty($category->slug) ||
                $category->isDirty('parent_id') ||
                $category->isDirty('name')
            ) {
                $slug = Str::slug($category->name);

                if ($category->parent_id) {
                    $parent = Category::find($category->parent_id);
                    if ($parent) {
                        $slug = $parent->slug . '/' . $slug;
                    }
                }

                $category->slug = $slug;
            }
        });
    }
}