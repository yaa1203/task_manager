<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->boolean('is_personal')->default(false)->after('is_archived');
        });
    }

    public function down()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropColumn('is_personal');
        });
    }
};