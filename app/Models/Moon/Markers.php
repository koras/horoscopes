<?php

namespace App\Models\Moon;

use App\Models\Moon\MarkersTest;
use App\Contracts\Models\Moon\MarkersInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markers extends Model implements MarkersInterface
{

    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = true;

    // Указываем имя таблицы
    protected $table = 'markers';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'abbreviations',
        'name_ru',
        'name_lat',
        'description',
    ];


    public function markerTests()
    {
        return $this->hasMany(MarkersTest::class, 'marker_id', 'id');
    }

}
