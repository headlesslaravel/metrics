<?php

namespace HeadlessLaravel\Metrics\Tests\Fixtures\Models;

use HeadlessLaravel\Metrics\Tests\Fixtures\Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public static function newFactory()
    {
        return CustomerFactory::new();
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
