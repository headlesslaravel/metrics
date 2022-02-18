<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AggregateTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_aggregate()
    {
        Order::factory(10)->create();

        $output = Metric::make(Order::class)->count()->render();

        $this->assertEquals(10, $output);
    }

    public function test_sum_aggregate()
    {
        Order::factory()->create(['total' => 1]);
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 100]);

        $output = Metric::make(Order::class)->sum('total')->render();

        $this->assertEquals(111, $output);
    }

    public function test_min_aggregate()
    {
        Order::factory()->create(['total' => 1]);
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 100]);

        $output = Metric::make(Order::class)->min('total')->render();

        $this->assertEquals(1, $output);
    }

    public function test_max_aggregate()
    {
        Order::factory()->create(['total' => 1]);
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 100]);

        $output = Metric::make(Order::class)->max('total')->render();

        $this->assertEquals(100, $output);
    }

    public function test_avg_aggregate()
    {
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 10]);
        Order::factory()->create(['total' => 30]);

        $output = Metric::make(Order::class)->avg('total')->render();

        $this->assertEquals(15, $output);
    }
}
