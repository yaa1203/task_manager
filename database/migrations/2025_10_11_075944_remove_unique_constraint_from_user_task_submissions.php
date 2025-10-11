<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->dropUnique(['task_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->unique(['task_id', 'user_id']);
        });
    }
};