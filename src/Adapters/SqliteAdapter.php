<?php

namespace HeadlessLaravel\Metrics\Adapters;

use Error;

class SqliteAdapter extends AbstractAdapter
{
    public function format(string $column, string $interval): string
    {
        $format = match ($interval) {
            'minutes' => '%Y-%m-%d %H:%M:00',
            'hours' => '%Y-%m-%d %H:00',
            'days' => '%Y-%m-%d',
            'months' => '%Y-%m',
            'years' => '%Y',
            default => throw new Error('Invalid interval.'),
        };

        return "strftime('{$format}', {$column})";
    }
}
