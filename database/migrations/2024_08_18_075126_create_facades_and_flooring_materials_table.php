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
        Schema::create('decorative_stones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->default(600);
            $table->string('t')->nullable();
            $table->string('b')->default(2400);
            $table->string('a')->nullable();
            $table->timestamps();
        });

        Schema::create('body_tiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->default(500);
            $table->string('t')->nullable();
            $table->string('b')->default(2400);
            $table->string('a')->nullable();
            $table->timestamps();
        });

        Schema::create('ceramics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->default(350);
            $table->string('t')->nullable();
            $table->string('b')->default(1800);
            $table->string('a')->nullable();
            $table->timestamps();
        });

        Schema::create('mosaics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->default(350);
            $table->string('t')->nullable();
            $table->string('b')->default(1800);
            $table->string('a')->nullable();
            $table->timestamps();
        });

        Schema::create('cementings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('c')->default(450);
            $table->string('t')->nullable();
            $table->string('b')->default(2100);
            $table->string('a')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cementings');
        Schema::dropIfExists('mosaics');
        Schema::dropIfExists('ceramics');
        Schema::dropIfExists('body_tiles');
        Schema::dropIfExists('decorative_stones');
    }
};
