<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};