<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'euro_rate',
        'format',
    ];

    public function vendingMachines(): HasMany
    {
        return $this->hasMany(VendingMachine::class, 'currency_code', 'code');
    }
}
