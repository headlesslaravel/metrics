<?php

namespace HeadlessLaravel\Metrics\Tests\Fixtures\Database\Factories;

use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'total' => rand(400, 10000),
        ];
    }
}
