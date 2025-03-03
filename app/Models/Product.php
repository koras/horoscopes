<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Models\ProductInterface;

class Product extends Model implements ProductInterface
{

    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = true;

    // Указываем имя таблицы
    protected $table = 'products';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'name',
        'price',
        'quantity',
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
