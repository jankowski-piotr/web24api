<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_ADDRESSES = 'addresses';
    private const ATTR_STREET = 'street';
    private const ATTR_CITY = 'city';
    private const ATTR_COUNTRY_CODE = 'country_code';
    private const ATTR_POSTAL_CODE = 'postal_code';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_ADDRESSES, function (Blueprint $table) {
            $table->id();
            $table->string(self::ATTR_STREET, 255);
            $table->string(self::ATTR_CITY, 100);
            $table->char(self::ATTR_COUNTRY_CODE, 3);
            $table->char(self::ATTR_POSTAL_CODE, 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_ADDRESSES);
    }
};