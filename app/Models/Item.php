<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $table = 'items';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'vending_machine_id',
        'name',
        'price',
        'remaining_quantity',
    ];

    public function vendingMachine(): BelongsTo
    {
        return $this->belongsTo(VendingMachine::class, 'vending_machine_id', 'id');
    }
}
