<?php

namespace App\Imports;

use App\Models\ProductPrepare;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsPrepare implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ProductPrepare([
            //
        ]);
    }
}
