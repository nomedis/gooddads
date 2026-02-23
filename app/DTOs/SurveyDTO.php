<?php

namespace App\DTOs;

class SurveyDTO
{
    public function __construct(
        public readonly string $client_dob,
        public readonly string $delivery_method,
        public readonly string $why,
        public readonly string $why_other,
        public readonly string $how,
        public readonly string $how_other,
        public readonly string $gain,
        public readonly string $gain_other,
    ) {}
}
?>