<?php

namespace HeadlessLaravel\Metrics\Adapters;

use Error;

class MySqlAdapter extends AbstractAdapter
{
    public function format(string $column, string $interval): string
    {
        $format = match ($interval) {
            'minutes' => '%Y-%m-%d %H:%i:00',
            'hours' => '%Y-%m-%d %H:00',
            'days' => '%Y-%m-%d',
            'months' => '%Y-%m',
            'years' => '%Y',
            default => throw new Error('Invalid interval.'),
        };

        return "date_format({$column}, '{$format}')";
    }
}
