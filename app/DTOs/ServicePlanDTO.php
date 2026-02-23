<?php

namespace App\DTOs;

class ServicePlanDTO
{
    public function __construct(
        public readonly string $participant_full_name,
        public readonly string $client_number,
        public readonly string $goal,                          // service_plan_goal
        public readonly string $service_identified,
        public readonly string $strategies_1,
        public readonly string $person_responsible_1,
        public readonly string $timeline_1,
        public readonly string $measure_of_success_1,
        public readonly string $strategies_2,
        public readonly string $person_responsible_2,
        public readonly string $timeline_2,
        public readonly string $measure_of_success_2,
        public readonly string $strategies_3,
        public readonly string $person_responsible_3,
        public readonly string $timeline_3,
        public readonly string $measure_of_success_3,
    ) {}
}
?>
