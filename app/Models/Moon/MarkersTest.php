<?php

namespace App\Models\Moon;

use App\Contracts\Models\Moon\MarkersTestInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Moon\Markers;

class MarkersTest extends Model implements MarkersTestInterface
{

    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = true;

    // Указываем имя таблицы
    protected $table = 'markers_tests';

    // Поля, которые могут быть заполнены
    protected $fillable = [
        'marker_id',
        'tests_id',
    ];
    public function marker()
    {
        return $this->belongsTo(Markers::class, 'marker_id', 'id');
    }
}
