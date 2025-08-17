<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_EMPLOYEES = 'employees';
    private const TABLE_ADDRESSES = 'addresses';

    private const ATTR_NAME = 'name';
    private const ATTR_LAST_NAME = 'last_name';
    private const ATTR_EMAIL = 'email';
    private const ATTR_PHONE_NUMBER = 'phone_number';
    private const ATTR_ADDRESS_ID = 'address_id';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_EMPLOYEES, function (Blueprint $table) {
            $table->id();
            $table->string(self::ATTR_NAME, 100);
            $table->string(self::ATTR_LAST_NAME, 100);
            $table->string(self::ATTR_EMAIL)->unique();
            $table->string(self::ATTR_PHONE_NUMBER, 20)->nullable();
            $table->foreignId(self::ATTR_ADDRESS_ID)
                ->constrained(self::TABLE_ADDRESSES)
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_EMPLOYEES);
    }
};
