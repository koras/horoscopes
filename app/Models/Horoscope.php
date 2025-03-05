<?php

namespace App\Models;

use App\Contracts\Models\HoroscopeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horoscope  extends Model implements HoroscopeInterface
{

    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;

    // Указываем имя таблицы
    protected $table = 'horoscopes';

    //public $timestamps = false;

    public $using = 0;

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'using',
        'text_ru',
        'text_en',
        'active',
    ];

    public function shows()
    {
        return $this->hasMany(Show::class, 'horoscopes_id');
    }
}

