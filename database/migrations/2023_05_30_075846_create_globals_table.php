<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// THIS IS A SINGLETON TABLE. ONLY 1 ROW IS EXPECTED HERE.

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('globals', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('timezone')->default("Africa/Cairo"); // Africa/Cairo for example.
            $table->string('organization_address'); // Probably you only need the country, not full address
            $table->unsignedSmallInteger('absence_limit');
            $table->unsignedTinyInteger('payroll_day')->default(1);
            $table->json('weekend_off_days')->default(json_encode(['friday', 'saturday']));
            $table->string('email');
            $table->float('income_tax')->unsigned()->default(14);
            $table->boolean('is_ip_based')->default(false);
            $table->json('ip')->nullable();
            $table->timestamps();
        });

        // Add a check constraint to enforce only one record
        //DB::statement('ALTER TABLE globals ADD CONSTRAINT singleton_constraint CHECK (id = 1)'); // Does not work with MySQL

        // Add a check constraint to ensure income tax is a percentage
        DB::statement('ALTER TABLE globals ADD CONSTRAINT chk_income_tax_range CHECK (income_tax >= 0 AND income_tax <= 100)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('globals');
    }
};
