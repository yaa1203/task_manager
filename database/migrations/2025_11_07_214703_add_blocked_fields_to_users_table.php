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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false)->after('remember_token');
            $table->timestamp('blocked_at')->nullable()->after('is_blocked');
            $table->unsignedBigInteger('blocked_by')->nullable()->after('blocked_at');
            
            // Foreign key untuk admin yang memblokir
            $table->foreign('blocked_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']);
            $table->dropColumn(['is_blocked', 'blocked_at', 'blocked_by']);
        });
    }
};