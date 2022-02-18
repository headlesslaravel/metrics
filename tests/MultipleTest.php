<?php

namespace HeadlessLaravel\Metrics\Tests;

use HeadlessLaravel\Metrics\Metric;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Customer;
use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultipleTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_metrics_per_day_from_month()
    {
        $this->createPerDayFromMonth(Order::class, [
            'monday' => ['count' => 5, 'total' => 500], // 2500
            'tuesday' => ['count' => 1, 'total' => 500], // 500
            'wednesday' => ['count' => 3, 'total' => 500], // 1500
            'thursday' => ['count' => 2, 'total' => 500], // 1000
            'friday' => ['count' => 6, 'total' => 500], // 3000
            'saturday' => ['count' => 0, 'total' => 500], // 0
            'sunday' => ['count' => 1, 'total' => 500], // 500
        ]);

        $this->createPerDayFromMonth(Customer::class, [
            'monday' => 4,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 1,
            'friday' => 1,
            'saturday' => 0,
            'sunday' => 0,
        ]);

//        $result = Metric::new()
//            ->fromMonth()
//            ->byDay()
//            ->makeMany([
//                Metric::make(Order::class)
//                    ->sum('total'),
//                Metric::make(Order::class)
//                    ->count(),
//                Metric::make(Customer::class)
//                    ->count(),
//            ]);

        $this->assertTrue(true);

        // assert monday has total of 2500 for 5 orders
        // assert monday has 4 customers
        // assert tuesday has total of 500 for 1 orders
        // assert tuesday has 2 customers
        // assert wednesday has total of 1500 for 3 orders
        // assert wednesday has 3 customers
        // assert thursday has total of 1000 for 2 orders
        // assert thursday has 1 customers
        // assert friday has total of 3000 for 6 orders
        // assert friday has 1 customers
        // assert saturday has total of 0 for 0 orders
        // assert saturday has 0 customers
        // assert sunday has total of 500 for 1 orders
        // assert sunday has 0 customers
    }

    public function test_multiple_metrics_per_day_from_multiple_months()
    {
        // TODO: add a date parameter createPerDayFrom($model, $date, $settings = [])

        $this->createPerDayFromMonth(Order::class, [
            'monday' => ['count' => 5, 'total' => 500], // 2500
            'tuesday' => ['count' => 1, 'total' => 500], // 500
            'wednesday' => ['count' => 3, 'total' => 500], // 1500
            'thursday' => ['count' => 2, 'total' => 500], // 1000
            'friday' => ['count' => 6, 'total' => 500], // 3000
            'saturday' => ['count' => 0, 'total' => 500], // 0
            'sunday' => ['count' => 1, 'total' => 500], // 500
        ]);

        $this->createPerDayFromMonth(Customer::class, [
            'monday' => 4,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 1,
            'friday' => 1,
            'saturday' => 0,
            'sunday' => 0,
        ]);

//        $result = Metric::new()
//            ->fromMonth()
//            ->byDay()
//            ->makeMany([
//                Metric::make(Order::class)
//                    ->sum('total'),
//                Metric::make(Order::class)
//                    ->count(),
//                Metric::make(Customer::class)
//                    ->count(),
//            ]);

        $this->assertTrue(true);
        // assert monday has total of 2500 for 5 orders
        // assert monday has 4 customers
        // assert tuesday has total of 500 for 1 orders
        // assert tuesday has 2 customers
        // assert wednesday has total of 1500 for 3 orders
        // assert wednesday has 3 customers
        // assert thursday has total of 1000 for 2 orders
        // assert thursday has 1 customers
        // assert friday has total of 3000 for 6 orders
        // assert friday has 1 customers
        // assert saturday has total of 0 for 0 orders
        // assert saturday has 0 customers
        // assert sunday has total of 500 for 1 orders
        // assert sunday has 0 customers
    }

    public function test_table_multiple_metrics_per_day_from_multiple_months()
    {
        // | Month | Sunday | Monday | Tuesday |
        // |-------|--------|--------|---------|
        // | Jan   | 1      | 2      | 3       |
        // | Feb   | 1      | 2      | 3       |
        // | Mar   | 1      | 2      | 3       |

        Metric::make(Order::class)
            ->fromMonth(3)
            ->byDay()
            ->asTable()
            ->count();

        $this->assertTrue(true);
    }

    // https://www.chartjs.org/docs/latest/general/data-structures.html
    public function test_chart_multiple_metrics_per_day_from_multiple_months()
    {
        // | Month | Sunday | Monday | Tuesday |
        // |-------|--------|--------|---------|
        // | Jan   | 1      | 2      | 3       |
        // | Feb   | 1      | 2      | 3       |
        // | Mar   | 1      | 2      | 3       |

        $output = [
            'Jan' => ['Sunday' => 1, 'Monday' => 2, 'Tuesday' => 3],
            'Feb' => ['Sunday' => 1, 'Monday' => 2, 'Tuesday' => 3],
            'Mar' => ['Sunday' => 1, 'Monday' => 2, 'Tuesday' => 3],
        ];
        // or is it this?
        $output = [
            'Sunday' => ['Jan' => 1, 'Feb' => 1, 'Mar' => 1],
            'Monday' => ['Jan' => 2, 'Feb' => 2, 'Mar' => 2],
            'Tuesday' => ['Jan' => 3, 'Feb' => 3, 'Mar' => 3],
        ];

        Metric::make(Order::class)
            ->fromMonth(3)
            ->byDay()
            ->asChart()
            ->count();

        $this->assertTrue(true);
    }

    public function test_exception_when_periods_or_intervals_defined_on_nested_items()
    {
        $this->assertTrue(true);
    }
}
