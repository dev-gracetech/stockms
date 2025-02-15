<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable(); // Stores the file path
            $table->integer('high_stock_threshold')->default(100);
            $table->integer('low_stock_threshold')->default(10);
            $table->integer('expiry_alert_days')->default(30);
            $table->string('default_stock_location')->nullable();
            $table->string('notification_email')->nullable();
            $table->string('currency')->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
