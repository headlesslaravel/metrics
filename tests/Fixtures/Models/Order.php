<?php

namespace HeadlessLaravel\Metrics\Tests\Fixtures\Models;

use HeadlessLaravel\Metrics\Tests\Fixtures\Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public static function newFactory()
    {
        return OrderFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
