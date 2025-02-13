<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->date('order_date');
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('pending'); // e.g., pending, approved, received, cancelled
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};
