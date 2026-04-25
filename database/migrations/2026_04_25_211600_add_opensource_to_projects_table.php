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
        Schema::table('projects', function (Blueprint $blueprint) {
            $blueprint->boolean('is_opensource')->default(false)->after('blog_content');
            $blueprint->string('github_link')->nullable()->after('is_opensource');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['is_opensource', 'github_link']);
        });
    }
};
