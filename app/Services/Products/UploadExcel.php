<?php

namespace App\Services\Products;
use Maatwebsite\Excel\Facades\Excel;


class UploadExcel
{
    public function parserExcel($file){

        Excel::import(new ProductsPrepare, $file);
    }
}
