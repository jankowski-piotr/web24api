<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'tax_number',
        'address_id',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
