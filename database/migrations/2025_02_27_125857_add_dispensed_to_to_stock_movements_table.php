<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('from_branch_id')->nullable()->constrained('branches')->onDelete('set null')->after('from_warehouse_id');
            $table->string('dispensed_to')->nullable()->after('to_branch_id'); // Can be a branch name or customer name
        });
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['from_branch_id']);
            $table->dropColumn('from_branch_id');
            $table->dropColumn('dispensed_to');
        });
    }
};
