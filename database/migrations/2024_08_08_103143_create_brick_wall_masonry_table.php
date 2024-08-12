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
        Schema::create('brick_wall_masonry_apartment_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('a')->nullable();
            $table->string('b')->nullable();
            $table->string('c')->default(250);
            $table->timestamps();
        });
        
        Schema::create('brick_wall_masonry_gardens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('l')->nullable();
            $table->string('h')->nullable();
            $table->string('b')->default(20);
            $table->string('type')->nullable();
            $table->string('d')->nullable();
            $table->timestamps();
        });
      
        Schema::create('brick_wall_masonry_pressed_bricks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('a')->nullable();
            $table->string('b')->nullable();
            $table->string('r')->default(22);
            $table->string('e')->default(10.5);
            $table->string('f')->default(5.5);
            $table->string('c')->default(250);
            $table->timestamps();
        });

        Schema::create('brick_wall_masonry_partitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->string('a')->nullable();
            $table->string('r')->default(20);
            $table->string('e')->default(8);
            $table->string('f')->default(13);
            $table->string('b')->nullable();
            $table->string('c')->default(250);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brick_wall_masonry_partitions');
        Schema::dropIfExists('brick_wall_masonry_pressed_bricks');
        Schema::dropIfExists('brick_wall_masonry_gardens');
        Schema::dropIfExists('brick_wall_masonry_apartment_blocks');
    }
};
