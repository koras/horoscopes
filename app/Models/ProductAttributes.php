<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    use HasFactory;
    // Указываем имя таблицы
    protected $table = 'product_attributes';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'product_id',
        'attributes',
    ];

    // Преобразование поля attributes в массив (JSON)
    protected $casts = [
        'attributes' => 'json',
    ];

    // Связь с моделью Product (один к одному)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
