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
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->nullable();
            $table->enum('m', ['1', '2', '3', '4', '5'])->nullable();
            $table->string('a')->nullable();
            $table->string('b')->nullable();
            $table->string('nb')->nullable();
            $table->string('ng')->default(1);
            $table->string('nf')->nullable();
            $table->string('ns')->default(1);
            $table->timestamps();
        });

        Schema::create('construction_basements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_id');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
            $table->string('b1')->nullable();
            $table->string('b2')->nullable();
            $table->timestamps();
        });

        Schema::create('construction_floors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_id');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
            $table->string('g')->nullable();
            $table->string('f1')->nullable();
            $table->string('f2')->nullable();
            $table->string('f3')->nullable();
            $table->string('f4')->nullable();
            $table->string('f5')->nullable();
            $table->string('f6')->nullable();
            $table->string('f7')->nullable();
            $table->string('f8')->nullable();
            $table->timestamps();
        });

        Schema::create('construction_balconies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_id');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
            $table->string('b1')->nullable();
            $table->string('b2')->nullable();
            $table->string('b3')->nullable();
            $table->timestamps();
        });

        Schema::create('construction_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_id');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
            $table->string('pc')->nullable();
            $table->string('pm')->nullable();
            $table->string('pa')->nullable();
            $table->string('ps')->nullable();
            $table->string('pk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_prices');
        Schema::dropIfExists('construction_balconies');
        Schema::dropIfExists('construction_floors');
        Schema::dropIfExists('construction_basements');
        Schema::dropIfExists('constructions');
    }
};
