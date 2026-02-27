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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('name');
            $table->foreignId('activity_type_id')->nullable(); //->constrained('activity_types')->nullOnDelete();
            $table->foreignId('activity_scope_id')->nullable(); //->constrained('activity_scopes')->nullOnDelete();
            $table->foreignId('material_type_id')->nullable(); //->constrained('material_types')->nullOnDelete();
            $table->foreignId('activity_method_id')->nullable(); //->constrained('activity_methods')->nullOnDelete();
            $table->foreignId('batch_id')->nullable(); //->constrained('batches')->nullOnDelete();
            $table->foreignId('activity_format_id')->nullable(); //->constrained('activity_formats')->nullOnDelete();
            $table->string('collaboration_inst')->nullable();
            $table->foreignId('target_participant_id')->nullable(); //->constrained('targer_participants')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget_amount', 15, 2)->nullable();
            $table->foreignId('work_unit_id')->nullable(); //->constrained('work_units')->nullOnDelete();
            $table->foreignId('user_id')->nullable(); //->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
