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
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('currency');
            $table->decimal('base', 10, 2)->default(0);
            $table->decimal('performance_multiplier', 10, 2)->default(0);
            $table->decimal('total_additions', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('total_payable', 10, 2)->default(0); // Maybe not needed
            $table->date('due_date');
            $table->boolean('is_reviewed')->default(false); // True: Paid, False: Pending
            $table->boolean('status')->default(false); // True: Paid, False: Pending
            $table->unique(['employee_id', 'due_date']);
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
