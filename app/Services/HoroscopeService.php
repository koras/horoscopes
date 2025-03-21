<?php

namespace App\Services;

use App\Contracts\Services\HoroscopeServiceInterface;

use App\Models\Show;
use App\Models\Horoscope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HoroscopeService implements HoroscopeServiceInterface
{
    private const SODIAC = [
        'aquarius',
        'aries',
        'cancer',
        'capricorn',
        'gemini',
        'leo',
        'libra',
        'pisces',
        'sagittarius',
        'scorpio',
        'taurus',
        'virgo',
    ];

    private $compatibilityItem = [
        "love",
        "money",
        "travel",
        "interests",
        "work",
        "energy",
    ];

    private function getSodiac($id)
    {
        return self::SODIAC[($id - 1)];
    }


    private function resourse($horoscope)
    {
        return
            [
                "chart" => [
                    "chart_love" => $horoscope->chart_love,
                    "chart_money" => $horoscope->chart_money,
                    "chart_like" => $horoscope->chart_like,
                ],
                "text" => [
                    "ru" => $horoscope->horoscope->text_ru,
                    "en" => $horoscope->horoscope->text_en,
                ],
                "favorite_numbers" => json_decode($horoscope->favorite_numbers, true),
            ];
    }


    public function getInfo()
    {
        // Получаем гороскопы за текущий день
        $dailyHoroscopes = $this->getDailyHoroscopes(0);

        foreach ($dailyHoroscopes as $horoscope) {
            $result['daily'][$horoscope->date][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
        }

        $dailyHoroscopes = $this->getDailyHoroscopes(1);
        foreach ($dailyHoroscopes as $horoscope) {
            $result['daily'][$horoscope->date][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
        }
        $dailyHoroscopes = $this->getDailyHoroscopes(2);
        foreach ($dailyHoroscopes as $horoscope) {
            $result['daily'][$horoscope->date][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
        }

        $dailyHoroscopes = $this->getDailyHoroscopes(3);
        foreach ($dailyHoroscopes as $horoscope) {
            $result['daily'][$horoscope->date][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
        }

        $weeklyHoroscopes = $this->getWeeklyHoroscopes();
        // dd($weeklyHoroscopes);
        foreach ($weeklyHoroscopes as $horoscope) {
            $result['weekly'][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
        }

        return $result;
    }

    public function getDailyHoroscopes($day)
    {
        $currentDate = Carbon::now()->addDays($day)->toDateString();

        return Show::with('horoscope')
            ->where('type', 'daily')
            ->where('date', $currentDate)
            ->orderBy('zodiac')
            ->get();
    }

    public function getWeeklyHoroscopes()
    {
        $currentDate = Carbon::now()->toDateString();

        return Show::with('horoscope')
            ->where('type', 'weekly')
            ->where('date', '>=', Carbon::parse($currentDate)->startOfWeek())
            ->where('date', '<=', Carbon::parse($currentDate)->endOfWeek())
            ->orderBy('zodiac')
            ->get();
    }


    public function getCurrent()
    {
        // Получаем последнюю дату из таблицы show

        $lastDate = Show::where('type', 'daily')->max('date');

        // Если таблица show пуста, начинаем с текущей даты
        if (!$lastDate) {
            $lastDate = Carbon::now()->toDateString();
        }

        // Вычисляем целевую дату (последняя дата + 1 день)
        $targetDate = Carbon::parse($lastDate)->addDay()->toDateString();

        // Проверяем, есть ли уже записи для этой даты
        $existingRecords = Show::where('date', $targetDate)->where('type', 'daily')->count();
        if ($existingRecords >= 12) {
            return; // Если записи уже есть, ничего не делаем
        }

        //dd( $lastDate);
        // Получаем дневные гороскопы, которые не использовались в течение недели
        $dailyHoroscopes = Horoscope::whereDoesntHave('shows', function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subWeek());
        })
            ->inRandomOrder()
            ->limit(12)
            ->get();

        // Если записей недостаточно, выходим
        if ($dailyHoroscopes->count() < 12) {
            return;
        }
        $tmp = [];
        // Добавляем записи в таблицу show
        foreach ($dailyHoroscopes as $index => $horoscope) {
            // Генерируем 5 случайных чисел в порядке возрастания
            $favoriteNumbers = collect(range(1, 100))->random(5)->sort()->values();

            $tmp[] = Show::create([
                'type' => 'daily', // Тип гороскопа
                'horoscopes_id' => $horoscope->id,
                'zodiac' => $index + 1, // Знаки зодиака от 1 до 12
                'date' => $targetDate,
                'favorite_numbers' => $favoriteNumbers->toJson(), // Сохраняем в формате JSON
                'chart_love' => rand(1, 100),
                'chart_money' => rand(1, 100),
                'chart_like' => rand(1, 100),
            ]);
        }
        return $tmp;
    }

    public function fillWeeklyShowTable()
    {
        // Получаем последнюю дату из таблицы show для недельных гороскопов
        $lastWeeklyDate = Show::where('type', 'weekly')->max('date');
        $week = false;
        // Если таблица show пуста, начинаем с текущей даты
        if (!$lastWeeklyDate) {
            //  dd( 444,$week,$lastWeeklyDate);

            $lastWeeklyDate = Carbon::now()->toDateString();
            $targetDate = Carbon::parse($lastWeeklyDate)->toDateString();
            $week = true;
        } else {
            $targetDate = Carbon::parse($lastWeeklyDate)->addWeek()->toDateString();
        }


        if ($week) {
            //  dd( $week,$lastWeeklyDate);
            // Вычисляем целевую дату (последняя дата + 7 дней)
            //    $targetDate = Carbon::parse($lastWeeklyDate)->addWeek()->toDateString();
        } else {
            //   // Вычисляем целевую дату (последняя дата + 7 дней)
            //    $targetDate = Carbon::parse($lastWeeklyDate)->toDateString();
        }
        // Проверяем, есть ли уже записи для этой даты
        $existingRecords = Show::where('date', $targetDate)->where('type', 'weekly')->count();
        if ($existingRecords >= 12) {
            return; // Если записи уже есть, ничего не делаем
        }

        // Получаем недельные гороскопы, которые не использовались в течение месяца
        $weeklyHoroscopes = Horoscope::whereDoesntHave('shows', function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        })
            ->inRandomOrder()
            ->limit(12)
            ->get();

        // Если записей недостаточно, выходим
        if ($weeklyHoroscopes->count() < 12) {
            return;
        }
        $tmp = [];
        // Добавляем записи в таблицу show
        foreach ($weeklyHoroscopes as $index => $horoscope) {
            // Генерируем 5 случайных чисел в порядке возрастания
            $favoriteNumbers = collect(range(1, 100))->random(5)->sort()->values();

            $tmp[] = Show::create([
                'type' => 'weekly', // Тип гороскопа
                'horoscopes_id' => $horoscope->id,
                'zodiac' => $index + 1, // Знаки зодиака от 1 до 12
                'date' => $targetDate,
                'favorite_numbers' => $favoriteNumbers->toJson(), // Сохраняем в формате JSON
                'chart_love' => rand(1, 100),
                'chart_money' => rand(1, 100),
                'chart_like' => rand(1, 100),
            ]);
        }
        return $tmp;
    }


}
