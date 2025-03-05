<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFileShops extends Model
{
    /**
     * Таблица в которой хранятся ссылки на файл загруженный магазином
     */
    use HasFactory;

    // Указываем имя таблицы
    protected $table = 'products_file_shops';

    // Поля, которые могут быть заполнены
    protected $fillable = ['name', 'path', 'hash_name', 'shop_id'];

    public function getFile(int $id)
    {
       return ProductFileShops::find($id);
    }
}
