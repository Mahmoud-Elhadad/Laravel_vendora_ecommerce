<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchents', function (Blueprint $table) {
            // Fix nullable to not nullable first, then add the foreign key
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('ecomm_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('merchents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
