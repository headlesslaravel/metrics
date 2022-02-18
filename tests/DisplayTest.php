<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_chart()
    {
        $this->assertTrue(true);
    }

    public function test_to_chart_with_multiple_months()
    {
        $this->assertTrue(true);
    }

    public function test_to_chart_with_multiple_metrics()
    {
        $this->assertTrue(true);
    }

    public function test_to_csv()
    {
        $this->assertTrue(true);
    }

    public function test_to_csv_with_multiple_months()
    {
        $this->assertTrue(true);
    }

    public function test_to_csv_with_multiple_metrics()
    {
        $this->assertTrue(true);
    }

    public function test_to_table()
    {
        $this->assertTrue(true);
    }

    public function test_to_table_with_multiple_months()
    {
        $this->assertTrue(true);
    }

    public function test_to_table_with_multiple_metrics()
    {
        $this->assertTrue(true);
    }
}
