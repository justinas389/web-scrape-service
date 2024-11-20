<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class SelectorData extends Data
{
    public function __construct(
        public string $wrapper,
        public array $map,
    ) {
    }
}