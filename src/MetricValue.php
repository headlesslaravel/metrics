<?php

namespace HeadlessLaravel\Metrics;

class MetricValue
{
    public function __construct(
        public string $date,
        public mixed $aggregate,
    ) {
    }
}
