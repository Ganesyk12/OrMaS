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
        Schema::create('user_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('module');
            $table->boolean('allow_create')->default(false);
            $table->boolean('allow_edit')->default(false);
            $table->boolean('allow_view')->default(false);
            $table->boolean('allow_delete')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'module']);
            $table->index(['module']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accesses');
    }
};
