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

class Metric
{
    use Concerns\Periods;
    use Concerns\Filters;
    use Concerns\Outputs;
    use Concerns\Intervals;
    use Concerns\Aggregates;

    public $builder;

    public $interval;

    public $from;

    public $to;

    public $dateColumn = 'created_at';

    public static function new():self
    {
        return new static();
    }

    public static function make($model): self
    {
        $metric = static::new();

        if(is_string($model)) {
            $model = $model::query();
        }

        $metric->builder = $model;

        return $metric;
    }

    public function makeMany(array $metrics): array
    {
        $results = [];

        // TODO: need to apply intervals, periods, filters etc to each
        foreach($metrics as $metric) {
            $results[] = $metric->render();
        }

        return $results;
    }

    public function render()
    {
        return $this->aggregate(
            $this->aggregateColumn,
            $this->aggregate
        );
    }

    public function withPrevious(): self
    {
        return $this;
    }

    protected function fallbacks()
    {
        if(is_null($this->to)) {
            $this->to = Carbon::now();
        }

        if(is_null($this->from)) {
            $this->from = Carbon::today()->subYears(100);
        }
    }

    public function where(...$arguments): self
    {
        $this->builder->where(...$arguments);

        return $this;
    }

    public function to(Carbon $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function dateColumn(string $column): self
    {
        $this->dateColumn = $column;

        return $this;
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
            'minute' => 'Y-m-d H:i:00',
            'hour' => 'Y-m-d H:00',
            'day' => 'Y-m-d',
            'month' => 'Y-m',
            'year' => 'Y',
            default => throw new Error('Invalid interval.'),
        };
    }

    public function __destruct()
    {
        return $this->render();
    }
}
