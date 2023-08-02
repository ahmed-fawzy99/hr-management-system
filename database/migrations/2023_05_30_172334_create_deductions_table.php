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
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('income_tax', 10, 2)->default(0);
            $table->decimal('social_security_contributions', 10, 2)->default(0);
            $table->decimal('health_insurance', 10, 2)->default(0);
            $table->decimal('retirement_plan', 10, 2)->default(0);
            $table->decimal('benefits', 10, 2)->default(0);
            $table->decimal('union_fees', 10, 2)->default(0);
            $table->decimal('undertime', 10, 2)->default(0);
            $table->decimal('negative_hour_rate', 10, 2)->default(0);
            $table->date('due_date');
            $table->boolean('status')->default(false); // True: Processed, False: Drafted
            $table->unique(['payroll_id', 'due_date']); // Enforce 1 addition per employee per month
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
