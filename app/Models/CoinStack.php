<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinStack extends Model
{
    protected $table = 'coin_stacks';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'vending_machine_id',
        'type',
        'remaining_amount',
    ];

    public function vendingMachine(): BelongsTo
    {
        return $this->belongsTo(VendingMachine::class, 'vending_machine_id', 'id');
    }
}
