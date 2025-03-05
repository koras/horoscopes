<?php

namespace App\Services\Moon;

use App\Contracts\Repositories\MarkersRepositoryInterface;
use App\Contracts\Services\MarkerServiceInterface;
use App\Contracts\Models\Moon\MarkersTestInterface;

class MarkerService implements MarkerServiceInterface
{
    public function __construct(
        private readonly MarkersRepositoryInterface $markers,
        private readonly MarkersTestInterface $markersTest
    ) {
    }

    public function takeMarketBlood(int $id)
    {
        return $this->markersTest->with('marker')->where(['tests_id' => $id])->get();
    }

}
