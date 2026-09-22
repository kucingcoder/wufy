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
        Schema::create('crms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_projek');
            $table->string('pic_nama');
            $table->string('pic_wa');
            $table->string('link')->nullable();
            $table->bigInteger('biaya_pengembangan')->default(0);
            $table->enum('status_pembayaran', ['DP', 'TERMIN', 'LUNAS'])->default('DP');
            $table->bigInteger('biaya_bulanan')->nullable()->default(0);
            $table->date('terakhir_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crms');
    }
};
