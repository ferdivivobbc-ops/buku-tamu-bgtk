<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->date('visit_date');
            $table->time('check_in_time');
            $table->time('check_out_time')->nullable();
            $table->string('name');
            $table->string('identity_number')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('institution');
            $table->string('position');
            $table->string('city_regency');
            $table->text('purpose');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('destination_person')->nullable();
            $table->integer('companion_count')->default(1);
            $table->string('photo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, sedang_bertemu, selesai, dibatalkan
            $table->text('officer_notes')->nullable();
            $table->tinyInteger('satisfaction_rating')->nullable(); // 1 - 5
            $table->text('satisfaction_feedback')->nullable();
            $table->timestamp('rated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
