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
        Schema::create('additions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('rewards', 10, 2)->default(0);
            $table->decimal('incentives', 10, 2)->default(0);
            $table->decimal('reimbursements', 10, 2)->default(0);
            $table->decimal('shift_differentials', 10, 2)->default(0);
            $table->decimal('commissions', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);
            $table->decimal('extra_hour_rate', 10, 2)->default(0);
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
        Schema::dropIfExists('additions');
    }
};
