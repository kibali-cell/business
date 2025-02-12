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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            // Optionally, you can have a department or category for the budget:
            $table->string('department')->nullable();
            // The allocated amount (the budgeted amount)
            $table->decimal('allocated', 15, 2);
            // The actual spending (this could be updated manually or via a process that aggregates expenses)
            $table->decimal('actual', 15, 2)->default(0);
            // Optionally store the variance (or calculate on the fly)
            // $table->decimal('variance', 15, 2)->nullable();
            // Budget period: start and end dates (for a month, quarter, etc.)
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
