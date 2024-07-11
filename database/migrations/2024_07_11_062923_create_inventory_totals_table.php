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
        Schema::create('inventory_totals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('qty');
            $table->string('location'); 
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
        Schema::dropIfExists('inventory_totals');
    }
};
