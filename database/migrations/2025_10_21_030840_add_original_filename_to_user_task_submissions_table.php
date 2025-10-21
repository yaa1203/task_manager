<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
        });
    }

    public function down()
    {
        Schema::table('user_task_submissions', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });
    }
};
