<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HoroscopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakerRu = \Faker\Factory::create('ru_RU'); // Для русского текста
        $fakerEn = \Faker\Factory::create('en_US'); // Для английского текста

        for ($i = 0; $i < 4000; $i++) {
            DB::table('horoscopes')->insert([

                'text_ru' => $fakerRu->realText(100), // Генерация текста на русском
                'text_en' => $fakerEn->realText(100), // Генерация текста на английском
                'using' => rand(0, 100), // Случайное число от 0 до 100
                'active' => rand(0, 1), // Случайное значение 0 или 1
                'created_at' => Carbon::now(), // Текущая дата и время
                'updated_at' => Carbon::now(), // Текущая дата и время
            ]);
        }
    }
}
