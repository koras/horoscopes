<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SnakeCasingTrait
{
    /**
     * @param array $data
     * @return array
     */
    protected function arrayKeysToSnakeCase(array $data): array
    {
        $snakeData = [];
        foreach ($data as $key => $value) {
            $snakeData[Str::snake($key)] = $value;
        }
        return $snakeData;
    }
}