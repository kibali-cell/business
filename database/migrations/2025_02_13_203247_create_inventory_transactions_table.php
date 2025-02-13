<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id')->nullable(); // multi-warehouse support
            $table->enum('type', ['in', 'out']); // 'in' for stock addition, 'out' for deduction
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->string('reference_number')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
