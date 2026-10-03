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
        Schema::table('ecomm_users', function (Blueprint $table) {
            $table->enum("role" , ["customer" , "merchent"])->default("customer");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecomm_users', function (Blueprint $table) {
            $table->dropColumn("role");
        });
    }
};
