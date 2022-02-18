<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WhereTest extends TestCase
{
    use RefreshDatabase;

    public function test_where_equals()
    {
        Order::factory()->count(2)->create(['total' => 200]);
        Order::factory()->create(['total' => 100]);

        $first = Metric::make(Order::class)
            ->where('total', '200')
            ->count()
            ->render();

        $second = Metric::make(Order::class)
            ->where('total', '=', '100')
            ->count()
            ->render();

        $this->assertEquals(2, $first);
        $this->assertEquals(1, $second);
    }

    public function test_where_greater_than_less_than()
    {
        Order::factory()->create(['total' => 300]);
        Order::factory()->count(2)->create(['total' => 100]);

        $first = Metric::make(Order::class)
            ->where('total', '<', '200')
            ->count()
            ->render();

        $second = Metric::make(Order::class)
            ->where('total', '>', '200')
            ->count()
            ->render();

        $this->assertEquals(2, $first);
        $this->assertEquals(1, $second);
    }
}
