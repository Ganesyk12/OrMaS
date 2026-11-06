<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menghapus fitur agenda rapat beserta data dan hak aksesnya.
     */
    public function up(): void
    {
        Schema::dropIfExists('agendas');

        DB::table('user_accesses')->where('module', 'agendas')->delete();
        DB::table('modules')->where('name', 'agendas')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('agendas')) {
            Schema::create('agendas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('presenter')->nullable();
                $table->unsignedSmallInteger('duration_minutes')->nullable();
                $table->unsignedSmallInteger('order')->default(0);
                $table->string('status');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['meeting_id', 'order']);
                $table->index(['meeting_id', 'status']);
            });
        }

        DB::table('modules')->updateOrInsert(
            ['name' => 'agendas'],
            [
                'label' => 'Agenda',
                'sort_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
};
