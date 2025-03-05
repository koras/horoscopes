<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductAttributes;


class CategoryProduct extends Model
{

    use HasFactory;

    // Указываем имя таблицы
    protected $table = 'category_products';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'product_id',
        'category_id',
        'shop_id',
    ];
    // Связь с моделью Seller
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Связь с моделью supplier
    public function supplier()
    {
        return $this->belongsTo(supplier::class);
    }

    // Связь с моделью ProductAttributes (один к одному)
    public function attributes()
    {
        return $this->hasOne(ProductAttributes::class, 'product_id');
    }
}
