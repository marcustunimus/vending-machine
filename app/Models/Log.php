<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $table = 'logs';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'vending_machine_id',
        'type',
        'text',
    ];

    public function vendingMachine(): BelongsTo
    {
        return $this->belongsTo(VendingMachine::class, 'vending_machine_id', 'id');
    }
}
