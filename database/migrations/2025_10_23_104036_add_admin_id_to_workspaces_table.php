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
        Schema::table('workspaces', function (Blueprint $table) {
            // langkah 1: tambahkan kolom biasa dulu
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
        });

        // langkah 2: isi sementara dengan user_id (kalau ada) atau 1
        DB::table('workspaces')->update([
            'admin_id' => DB::raw('user_id') // atau angka tetap seperti 1
        ]);

        // langkah 3: baru tambahkan constraint
        Schema::table('workspaces', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
