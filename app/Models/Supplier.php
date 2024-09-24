<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Указываем имя таблицы
    protected $table = 'suppliers';

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
