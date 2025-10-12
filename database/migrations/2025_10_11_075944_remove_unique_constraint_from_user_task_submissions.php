<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['task_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_task_submissions', function (Blueprint $table) {
            // Baru hapus unique constraint
            $table->dropUnique(['task_id', 'user_id']);
        });

        Schema::table('user_task_submissions', function (Blueprint $table) {
            // Tambahkan lagi foreign key kalau masih perlu
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->unique(['task_id', 'user_id']);
        });

        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
