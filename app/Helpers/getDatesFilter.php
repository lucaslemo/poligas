<?php

use Carbon\Carbon;

if (! function_exists('getDatesFilter')) {
    function getDatesFilter(string $filter) : string|false
    {
        if ($filter == 'today') {
            return json_encode([
                'current' => [
                    'start' => Carbon::now()->startOfDay()->toDateString(),
                    'finish' => Carbon::now()->endOfDay()->toDateString(),
                ],
                'previous' => [
                    'start' => Carbon::now()->subDay()->startOfDay()->toDateString(),
                    'finish' => Carbon::now()->subDay()->endOfDay()->toDateString(),
                ]
            ]);
        }
        if ($filter == 'month') {
            return json_encode([
                'current' => [
                    'start' => Carbon::now()->startOfMonth()->toDateString(),
                    'finish' => Carbon::now()->endOfMonth()->toDateString(),
                ],
                'previous' => [
                    'start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                    'finish' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                ]
            ]);
        }
        if ($filter == 'year') {
            return json_encode([
                'current' => [
                    'start' => Carbon::now()->startOfYear()->toDateString(),
                    'finish' => Carbon::now()->endOfYear()->toDateString(),
                ],
                'previous' => [
                    'start' => Carbon::now()->subYear()->startOfYear()->toDateString(),
                    'finish' => Carbon::now()->subYear()->endOfYear()->toDateString(),
                ]
            ]);
        }
    }
}
