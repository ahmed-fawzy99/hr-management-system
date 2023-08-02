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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['complaint', 'payment', 'leave', 'other']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('message')->nullable();
            $table->unsignedSmallInteger('status')->default(0); // 0 => Pending, 1=> Approved, 2 => Rejected
            $table->text('admin_response')->nullable();
            $table->boolean('is_seen')->default(false); // seen by employee?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
