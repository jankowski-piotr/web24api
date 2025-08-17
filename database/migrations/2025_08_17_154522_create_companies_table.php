<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_ADDRESSES = 'addresses';
    private const TABLE_COMPANIES = 'companies';

    private const ATTR_NAME = 'name';
    private const ATTR_TAX_NUMBER = 'tax_number';
    private const ATTR_ADDRESS_ID = 'address_id';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE_COMPANIES, function (Blueprint $table) {
            $table->id();
            $table->string(self::ATTR_NAME, 100);
            $table->string(self::ATTR_TAX_NUMBER, 13);
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
        Schema::dropIfExists(self::TABLE_COMPANIES);
    }
};

