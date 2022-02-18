<?php

namespace HeadlessLaravel\Metrics\Concerns;

use Illuminate\Support\Collection;

trait Aggregates
{
    protected $aggregate;

    protected $aggregateColumn;

    public function avg(string $column): self
    {
        $this->aggregate = 'avg';
        $this->aggregateColumn = $column;

        return $this;
    }

    public function min(string $column): self
    {
        $this->aggregate = 'min';
        $this->aggregateColumn = $column;

        return $this;
    }

    public function max(string $column): self
    {
        $this->aggregate = 'max';
        $this->aggregateColumn = $column;

        return $this;
    }

    public function sum(string $column): self
    {
        $this->aggregate = 'sum';
        $this->aggregateColumn = $column;

        return $this;
    }

    public function count(string $column = '*'): self
    {
        $this->aggregate = 'count';
        $this->aggregateColumn = $column;

        return $this;
    }

    protected function aggregate(string $column, string $aggregate):mixed
    {
        $this->fallbacks();

        // TODO: move outside of aggregate trait // unrelated
        $builder = $this->builder
            ->toBase()
            ->whereBetween($this->dateColumn, [$this->from, $this->to]);

        if(! $this->interval) {
            return $builder->aggregate($aggregate, [$column]);
        }

        $values = $builder
            ->selectRaw("
                {$this->getSqlDate()} as date,
                {$aggregate}({$column}) as aggregate
            ")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->mapValuesToDates($values);
    }
}
