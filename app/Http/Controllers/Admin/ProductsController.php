<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductsPrepare;
use Illuminate\Http\Request;
use App\Services\Products;
use App\Http\Requests\ProductsExcelRequest;
use App\Services\Products\UploadExcel;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{

    /**
     * Готовим эксель для сохранения
     * @param Request $request
     * @return void
     */
    public function preparationExcel(ProductsExcelRequest $request)
    {
        $service = new ProductsPrepare();
        Excel::import($service, $request->file('file'));

        return $service->firstFiveRows;
    }

    public function uploadExcel(ProductsExcelRequest $request)
    {
        $file = $request->file('file');

        $firstRow = Excel::import(new ProductsPrepare, $request->file('file'));
        //   $model =

        //      Excel::import(new ProductsPrepare, $file);
        // $data = (new UploadExcel())->parserExcel($request->file('file'));
        dd($firstRow);
    }


    /**
     * Сохраняем эксель
     * @param Request $request
     * @return void
     */
    public function saveExcel(Request $request)
    {
    }


}
