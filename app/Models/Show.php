<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contracts\ShowInterface;

class Show extends Model implements ShowInterface
{
    use HasFactory;

    protected $table = 'show';

    protected $fillable = [
        'horoscopes_id',
        'type',
        'zodiac',
        'date',
        'favorite_numbers',
        'chart_love',
        'chart_money',
        'chart_like',
    ];

    protected static function booted()
    {
        static::created(function ($show) {
            // Увеличиваем значение `using` на 1
            $horoscope = Horoscope::find($show->horoscopes_id);
            if ($horoscope) {
                $horoscope->increment('using');
            }
        });
    }

    public function horoscope()
    {
        return $this->belongsTo(Horoscope::class, 'horoscopes_id');
    }
}
