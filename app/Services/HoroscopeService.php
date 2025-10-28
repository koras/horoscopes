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
        $result = [];
        // Получаем гороскопы за текущий день
        for ($day = 0; $day < 4; $day++) {
            $dailyHoroscopes = $this->getDailyHoroscopes($day);
            foreach ($dailyHoroscopes as $horoscope) {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $horoscope->date)->format('d.m');
                $result['daily'][$formattedDate][self::getSodiac($horoscope->zodiac)] = $this->resourse($horoscope);
            }

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

        // Если записей уже 12 или больше, ничего не делаем
        if ($existingRecords >= 12) {
            return [
                'targetDate' => $targetDate,
                'lastDate' => $lastDate,
                'data' => [],
                'message' => 'Records already exist for this date'
            ];
        }

        // Если записей меньше 12, удаляем все записи за эту дату
        if ($existingRecords > 0 && $existingRecords < 12) {
            Show::where('date', $targetDate)->where('type', 'daily')->delete();
        }

        // ПРОБЛЕМА БЫЛА ЗДЕСЬ: Слишком строгие условия для выбора гороскопов
        // Сначала пытаемся найти гороскопы, которые не использовались неделю
        $dailyHoroscopes = Horoscope::whereDoesntHave('shows', function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subWeek());
        })
            ->inRandomOrder()
            ->limit(12)
            ->get();

        // ЕСЛИ не нашли достаточно - берем любые активные гороскопы
        if ($dailyHoroscopes->count() < 12) {
            $neededCount = 12 - $dailyHoroscopes->count();
            $additionalHoroscopes = Horoscope::where('active', 1)
                ->inRandomOrder()
                ->limit($neededCount)
                ->get();

            $dailyHoroscopes = $dailyHoroscopes->merge($additionalHoroscopes);
        }

        // ЕСЛИ все еще недостаточно - берем ЛЮБЫЕ гороскопы
        if ($dailyHoroscopes->count() < 12) {
            $neededCount = 12 - $dailyHoroscopes->count();
            $anyHoroscopes = Horoscope::inRandomOrder()
                ->limit($neededCount)
                ->get();

            $dailyHoroscopes = $dailyHoroscopes->merge($anyHoroscopes);
        }

        // Если ВООБЩЕ нет гороскопов в базе - выходим
        if ($dailyHoroscopes->count() == 0) {
            return [
                'targetDate' => $targetDate,
                'lastDate' => $lastDate,
                'data' => [],
                'message' => 'No horoscopes available in database'
            ];
        }

        $tmp = ['targetDate' => $targetDate, 'lastDate' => $lastDate, 'data' => []];

        // Добавляем записи в таблицу show
        foreach ($dailyHoroscopes as $index => $horoscope) {
            // Генерируем 5 случайных чисел в порядке возрастания
            $favoriteNumbers = collect(range(1, 100))->random(5)->sort()->values();

            $data = [
                'type' => 'daily',
                'horoscopes_id' => $horoscope->id,
                'zodiac' => $index + 1,
                'date' => $targetDate,
                'favorite_numbers' => $favoriteNumbers->toJson(),
                'chart_love' => rand(1, 100),
                'chart_money' => rand(1, 100),
                'chart_like' => rand(1, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            Show::create($data);
            $tmp['data'][] = $data;
        }

        return $tmp;
    }

    // Временно добавьте в контроллер
        public function checkHoroscopes()
        {
            $count = Horoscope::count();
            $activeCount = Horoscope::where('active', 1)->count();

            return [
                'total_horoscopes' => $count,
                'active_horoscopes' => $activeCount,
                'horoscopes_list' => Horoscope::limit(5)->get()->toArray()
            ];
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
