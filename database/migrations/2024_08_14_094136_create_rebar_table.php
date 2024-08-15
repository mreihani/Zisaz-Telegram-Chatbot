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
        Schema::create('rebar_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('d')->nullable();
            $table->timestamps();
        });

        Schema::create('stirrup_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('d')->nullable();
            $table->string('l')->nullable();
            $table->string('b')->nullable();
            $table->string('n')->nullable();
            $table->timestamps();
        });

        Schema::create('rebar_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('d1')->nullable();
            $table->string('n')->nullable();
            $table->string('d2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rebar_conversions');
        Schema::dropIfExists('stirrup_weights');
        Schema::dropIfExists('rebar_weights');
    }
};
