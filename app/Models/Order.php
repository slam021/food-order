<?php

namespace App\Models;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sumOrderPrice()
    {
        $orderDetail = OrderDetail::where('order_id', $this->id)->pluck('price');
        $sumOrderDetailPrice = collect($orderDetail)->sum();

        return $sumOrderDetailPrice;
    }


    public function orderDetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function waitress(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waitress_id', 'id');
    }

    public function chasier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chasier_id', 'id');
    }
}
