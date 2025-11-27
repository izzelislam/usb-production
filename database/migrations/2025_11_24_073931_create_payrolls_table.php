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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->enum('salary_type', ['per_kg', 'per_day'])->default('per_kg');

            $table->integer('days_present')->default(0);
            $table->decimal('total_workload', 12, 2)->default(0);

            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('total_salary', 12, 2)->default(0);

            $table->decimal('total_bonus', 12, 2)->default(0);
            $table->decimal('total_deduction', 12, 2)->default(0);

            $table->decimal('final_salary', 12, 2)->default(0);

            $table->enum('mode', ['manual', 'master'])->default('manual');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
