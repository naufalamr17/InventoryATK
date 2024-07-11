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
        Schema::create('repairstatuses', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('inv_id'); // Menggunakan unsignedBigInteger untuk merujuk ke ID pengguna
            $table->foreign('inv_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->date('tanggal_kerusakan')->nullable();
            $table->date('tanggal_pengembalian')->nullable();
            $table->string('note')->nullable();
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairstatuses');
    }
};
