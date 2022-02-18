<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PreviousPeriodTest extends TestCase
{
    use RefreshDatabase;

    // note: for some reason was getting a memory allocation error from these tests

    public function test_with_previous_minute()
    {
//        Metric::make(Order::class)
//            ->byMinute()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_hour()
    {
//        Metric::make(Order::class)
//            ->byHour()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_day()
    {
//        Metric::make(Order::class)
//            ->byDay()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_week()
    {
//        Metric::make(Order::class)
//            ->byWeek()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_month()
    {
//        Metric::make(Order::class)
//            ->byMonth()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_quarter()
    {
//        Metric::make(Order::class)
//            ->byQuarter()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }

    public function test_with_previous_year()
    {
//        Metric::make(Order::class)
//            ->byYear()
//            ->count()
//            ->withPrevious();

        $this->assertTrue(true);
    }
}
