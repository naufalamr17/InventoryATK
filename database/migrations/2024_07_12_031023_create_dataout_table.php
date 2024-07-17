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
        Schema::create('dataouts', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->date('date');
            $table->string('time');
            $table->string('nik');
            $table->string('code');
            $table->integer('qty');
            $table->string('pic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataout');
    }
};
