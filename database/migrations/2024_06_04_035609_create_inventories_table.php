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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('period');
            $table->string('date');
            $table->string('time');
            $table->string('pic');
            $table->integer('qty');
            $table->bigInteger('price')->default(0);
            $table->string('location'); 
            $table->string('category'); 
            $table->string('name'); 
            $table->string('unit'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
