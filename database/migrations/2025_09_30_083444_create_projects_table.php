<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // nama project
            $table->text('description')->nullable();// deskripsi project
            $table->date('start_date')->nullable(); // tanggal mulai
            $table->date('end_date')->nullable();   // tanggal selesai / deadline
            $table->foreignId('user_id')            // pemilik project
                  ->constrained()
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
