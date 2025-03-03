<?php

namespace App\Http\Controllers\Moon;

use App\Contracts\Services\MarkerServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MarketController extends Controller
{
    public function __construct(
        private readonly MarkerServiceInterface $markerService
    )
    {
    }

    public function takeMarketBlood(Request $request)
    {
        return $this->markerService->takeMarketBlood($request->id);
    }

}
