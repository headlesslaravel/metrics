<?php

namespace HeadlessLaravel\Metrics\Concerns;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

trait Filters
{
    public $filters = [];

    public function filters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function filterDates($from = 'from', $to = 'to'): self
    {
        $this->builder->when(Request::filled($from), function($query) use($from, $to) {
            $query->whereBetween($this->dateColumn, [
                Request::input($from),
                Request::input($to, Carbon::now()),
            ]);
        });

        return $this;
    }
}
