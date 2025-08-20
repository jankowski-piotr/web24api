<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone_number',
    ];
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
