<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'city',
        'country_code',
        'postal_code',
    ];
}
