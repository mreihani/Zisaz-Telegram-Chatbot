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
        Schema::create('beam_and_block_roofs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('a')->nullable();
            $table->string('h')->nullable();
            $table->string('c')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beam_and_block_roofs');
    }
};
