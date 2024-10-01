<?php

namespace App\Services\Products;

use App\Models\ProductFileShops;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class UploadExcel
{
    public function __construct(public ProductFileShops $fileShops)
    {
    }

    public function parserExcel($request)
    {
        $uploadId = $request->input("upload_id");
        $columns = $request->input("col");
        $fileRecord = $this->fileShops->getFile($uploadId);
        if ($fileRecord) {
            $filePath = $fileRecord->path;
            $data = $this->getData($filePath);
            // Вернем данные

            $this->prepareProduct($columns, $data);
            return $data;
        }
    }


    /**
     * Обрабатывает данные из экселя
     * @param $columns
     * @param $data
     * @return void
     */
    private function prepareProduct($columns = [], $datas = [])
    {
        foreach ($datas[0] as  $rows) {
            foreach ($rows as $key => $row)
            {
        echo        $columns[$key];

            }
           // dd($key, $rows);
        }
    }


    /**
     * Получаем данные из файла
     * @param $filePath
     * @return array
     */
    private function getData($filePath)
    {
        // Проверяем, существует ли файл
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        $file = Storage::disk('public')->path($filePath);
        return Excel::toArray([], $file);
    }

}
