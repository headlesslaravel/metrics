<?php

namespace HeadlessLaravel\Metrics\Adapters;

use Error;

class PgsqlAdapter extends AbstractAdapter
{
    public function format(string $column, string $interval): string
    {
        $format = match ($interval) {
            'minutes' => 'YYYY-MM-DD HH24:MI:00',
            'hours' => 'YYYY-MM-DD HH24:00:00',
            'days' => 'YYYY-MM-DD',
            'months' => 'YYYY-MM',
            'years' => 'YYYY',
            default => throw new Error('Invalid interval.'),
        };

        return "to_char({$column}, '{$format}')";
    }
}
