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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('verification_url')->nullable()->after('level');
        });

        // Update existing data: 'pemula' -> 'dasar'
        DB::table('certificates')
            ->where('level', 'pemula')
            ->update(['level' => 'dasar']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data: 'dasar' -> 'pemula'
        DB::table('certificates')
            ->where('level', 'dasar')
            ->update(['level' => 'pemula']);

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('verification_url');
        });
    }
};
