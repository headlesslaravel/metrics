<?php

namespace HeadlessLaravel\Metrics;

use Carbon\CarbonPeriod;
use Error;
use HeadlessLaravel\Metrics\Adapters\MySqlAdapter;
use HeadlessLaravel\Metrics\Adapters\PgsqlAdapter;
use HeadlessLaravel\Metrics\Adapters\SqliteAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class Metric
{
    public $interval;

    public $from;

    public $to;

    public $dateColumn = 'created_at';

    public function __construct(public Builder $builder)
    {
    }

    public static function make(mixed $model): self
    {
        if(is_string($model)) {
            $model = $model::query();
        }

        return new static($model);
    }

    public function filter($key): self
    {
        if(is_array($key)) {
            foreach ($key as $k) {
                $this->filter($k);
            }

            return $this;
        }

        $this->builder->when(Request::filled($key), function($query) use($key) {
            $query->where($key, Request::input($key));
        });

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

    public function where(...$arguments): self
    {
        $this->builder->where(...$arguments);

        return $this;
    }

    public function from(Carbon $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function to(Carbon $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function interval(string $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function byMinute(): self
    {
        return $this->interval('minutes');
    }

    public function byHour(): self
    {
        return $this->interval('hours');
    }

    public function byDay(): self
    {
        return $this->interval('days');
    }

    public function byMonth(): self
    {
        return $this->interval('months');
    }

    public function byYear(): self
    {
        return $this->interval('years');
    }

    public function dateColumn(string $column): self
    {
        $this->dateColumn = $column;

        return $this;
    }

    public function aggregate(string $column, string $aggregate): Collection
    {
        if(is_null($this->to)) {
            $this->to = Carbon::now();
        }

        if(is_null($this->from)) {
            $this->from = Carbon::today()->subYears(100);
        }

        if(is_null($this->interval)) {
            $this->interval('years');
        }

        $values = $this->builder
            ->toBase()
            ->selectRaw("
                {$this->getSqlDate()} as date,
                {$aggregate}({$column}) as aggregate
            ")
            ->whereBetween($this->dateColumn, [$this->from, $this->to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->mapValuesToDates($values);
    }

    public function average(string $column): Collection
    {
        return $this->aggregate($column, 'avg');
    }

    public function min(string $column): Collection
    {
        return $this->aggregate($column, 'min');
    }

    public function max(string $column): Collection
    {
        return $this->aggregate($column, 'max');
    }

    public function sum(string $column): Collection
    {
        return $this->aggregate($column, 'sum');
    }

    public function count(string $column = '*'): Collection
    {
        return $this->aggregate($column, 'count');
    }

    public function mapValuesToDates(Collection $values): Collection
    {
        $values = $values->map(fn ($value) => new MetricResult(
            date: $value->date,
            aggregate: $value->aggregate,
        ));

        $format = $this->getCarbonDateFormat();

        $placeholders = $this->getDatePeriod()->map(
            fn (Carbon $date) => new MetricResult(
                date: $date->format($format),
                aggregate: 0,
            )
        );

        return $values
            ->merge($placeholders)
            ->unique('date')
            ->flatten();
    }

    protected function getDatePeriod(): Collection
    {
        return collect(
            CarbonPeriod::between(
                $this->from,
                $this->to,
            )->interval("1 {$this->interval}")
        );
    }

    protected function getSqlDate(): string
    {
        $adapter = match ($this->builder->getConnection()->getDriverName()) {
            'mysql' => new MySqlAdapter(),
            'sqlite' => new SqliteAdapter(),
            'pgsql' => new PgsqlAdapter(),
            default => throw new Error('Unsupported database driver.'),
        };

        return $adapter->format($this->dateColumn, $this->interval);
    }

    protected function getCarbonDateFormat(): string
    {
        return match ($this->interval) {
            'minutes' => 'Y-m-d H:i:00',
            'hours' => 'Y-m-d H:00',
            'days' => 'Y-m-d',
            'months' => 'Y-m',
            'years' => 'Y',
            default => throw new Error('Invalid interval.'),
        };
    }
}
