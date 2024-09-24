<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    // Указываем имя таблицы
    protected $table = 'sellers';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'name',
        'contact_info',
    ];

    // Связь с моделью Product (один ко многим)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
