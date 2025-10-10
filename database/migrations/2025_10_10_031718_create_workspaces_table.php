<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color')->default('#6366f1'); // Default indigo
            $table->string('icon')->default('📁'); // Default folder icon
            $table->enum('type', ['project', 'task', 'mixed'])->default('mixed');
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });

        // Pivot table untuk many-to-many relationship dengan tasks
        Schema::create('workspace_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table untuk many-to-many relationship dengan projects
        Schema::create('workspace_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workspace_task');
        Schema::dropIfExists('workspace_project');
        Schema::dropIfExists('workspaces');
    }
};