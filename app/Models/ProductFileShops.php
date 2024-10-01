<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    // Указываем имя таблицы
    protected $table = 'products';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'discount',
        'color',
        'size',
        'weight',
        'seller_id',
        'supplier_id',
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
        return $this->hasOne(ProductAttribute::class, 'product_id');
    }
}
