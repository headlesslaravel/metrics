<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Customer;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_with_ranges()
    {
        Order::factory()->count(1)->create(['total' => 49]);
        Order::factory()->count(1)->create(['total' => 101]);
        Order::factory()->count(4)->create(['total' => 75]);

        Request::merge(['total:min' => 50, 'total:max' => 100]);

        $result = Metric::make(Order::class)
            ->count()
            ->filters([
                Filter::make('total')->range()
            ]);

        $this->assertEquals(4, $result);
    }

    public function test_filters_with_relations()
    {
        $customer = Customer::factory()->create();

        Order::factory()->for($customer)->create(['total' => 100]);
        Order::factory()->for($customer)->create(['total' => 100]);
        Order::factory()->create(['total' => 75]);

        Request::merge(['customer' => $customer->id]);

        $result = Metric::make(Order::class)
            ->sum('total')
            ->filters([
                Filter::make('customer')->relation()
            ]);

        $this->assertEquals(200, $result);
    }
}
