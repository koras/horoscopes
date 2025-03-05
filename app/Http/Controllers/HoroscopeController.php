<?php

namespace App\Http\Controllers;

use App\Contracts\Services\MarkerServiceInterface;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Horoscope;
use Illuminate\Support\Facades\Cache;
use App\Contracts\Services\HoroscopeServiceInterface;

class HoroscopeController extends Controller
{

    private $sodiac = [
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
        //  "family",
        //  "loyalty",
    ];


    public function compatibility(Request $request)
    {
        $result = [];
        foreach ($this->sodiac as $valueMan) {
            foreach ($this->sodiac as $valueWoman) {
                foreach ($this->compatibilityItem as $compatibilityItem) {
                    $result[$valueMan][$valueWoman][$compatibilityItem] = rand(5, 100);
                }
            }
        }
        return $result;
    }

    /**
     * @return string[]
     */
    public function info(HoroscopeServiceInterface $service)
    {
        // Кэширование данных на 1 час (3600 секунд)
        return Cache::remember('key', 3600, function () use ($service) {
            return $service->getInfo();
        });
    }


    public function horoscope(Request $request, HoroscopeServiceInterface $service)
    {
        $res = [];
        $res[] = $service->getCurrent();
        $res[] = $service->fillWeeklyShowTable();
        //       $type = $request->input('type',1);
//        if($type == 1){
//            $service->getCurrent();
//        }else {
//            $service->fillWeeklyShowTable();
//        }
        return $res;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'text_ru' => 'required|string',
            'text_en' => 'required|string',
        ]);

        Horoscope::create($validatedData);

        return redirect()->route('horoscopes.store')->with('success', 'Post created successfully!');
    }

    public function view()
    {
        return view('horoscope.create');
    }


    // Обновление поля active через AJAX
    public function toggleActive(Request $request, $id)
    {
        $horoscope = Horoscope::findOrFail($id);
        $horoscope->active = !$horoscope->active; // Инвертируем значение
        $horoscope->save();

        return response()->json([
            'success' => true,
            'active' => $horoscope->active,
        ]);
    }

    public function index()
    {
        $horoscopes = Horoscope::orderBy("id", "desc")->paginate(10); // Пагинация по 10 записей на страницу
        return view('horoscope.index', compact('horoscopes'));
    }


    // Удаление записи
    public function destroy($id)
    {
        $horoscope = Horoscope::findOrFail($id);
        $horoscope->delete();

        return redirect()->route('horoscopes.index')->with('success', 'Запись успешно удалена!');
    }


    // Отображение формы редактирования записи
    public function edit($id)
    {
        $horoscope = Horoscope::findOrFail($id);
        return view('horoscope.edit', compact('horoscope'));
    }

    // Обновление записи
    public function update(Request $request, $id)
    {
        $request->validate([
            'text_ru' => 'required|string',
            'text_en' => 'required|string',
            'using' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $horoscope = Horoscope::findOrFail($id);
        $horoscope->update($request->all());

        return redirect()->route('horoscopes.index')->with('success', 'Запись успешно обновлена!');
    }
}
