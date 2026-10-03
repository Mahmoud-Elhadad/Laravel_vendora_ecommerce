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
        //approved_at rejected_at rejection_reaso
        Schema::create('merchents', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("ecomm_users")->onDelete("cascade")->onUpdate("cascade");
            $table->enum("status" , ["pending" , "approved" , "rejected"])->default("pending");
            $table->timestamp("approved_at")->nullable();
            $table->timestamp("rejected_at")->nullable();
            $table->text("rejected_reason")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchents');
    }
};
