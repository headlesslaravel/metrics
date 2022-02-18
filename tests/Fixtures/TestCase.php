<?php

namespace HeadlessLaravel\Metrics\Tests\Fixtures;

use HeadlessLaravel\Metrics\Tests\Fixtures\Models\Order;
use HeadlessLaravel\Metrics\Tests\Fixtures\TestProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\AssertableJsonString;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $useMysql = false;

    protected function getPackageProviders($app)
    {
        return [
            TestProvider::class,
        ];
    }

    public function useMysql()
    {
        $this->useMysql = true;
    }

    public function getEnvironmentSetUp($app)
    {
        if (!$this->useMysql) {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
        }

        include_once __DIR__.'/Database/migrations/create_orders_table.php.stub';
        include_once __DIR__.'/Database/migrations/create_customers_table.php.stub';

        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');

        (new \CreateOrdersTable())->up();
        (new \CreateCustomersTable())->up();
    }

    public function login()
    {
        $user = User::forceCreate([
            'name'        => 'User',
            'email'       => 'user@example.com',
            'password'    => '$2y$10$MTibKZXWRvtO2gWpfpsngOp6FQXWUhHPTF9flhsaPdWvRtsyMUlC2',
            'permissions' => json_encode(['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete']),
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function createPerDayFromMonth($model, array $days)
    {
        foreach($days as $day => $amount) {
            if(is_array($amount)) {
                $create = array_merge($amount, [
                    'created_at' => now()->startOfMonth()->next($day),
                ]);
                unset($create['count']);
                $amount = $amount['count'];
            } else {
                $create = [
                    'created_at' => now()->startOfMonth()->next($day),
                ];
            }

            $model::factory()->count($amount)->create($create);
        }
    }
}
