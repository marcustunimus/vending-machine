<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendingMachine extends Model
{
    protected $table = 'vending_machines';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'currency_code',
        'key',
        'location',
        'balance_amount',
    ];

    protected $with = [
        'currency',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function coinStacks(): HasMany
    {
        return $this->hasMany(CoinStack::class, 'vending_machine_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'vending_machine_id', 'id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Item::class, 'vending_machine_id', 'id');
    }
}
