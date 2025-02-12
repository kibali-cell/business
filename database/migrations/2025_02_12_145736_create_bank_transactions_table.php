<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            // Link to an internal bank account if you have one
            $table->unsignedBigInteger('account_id')->nullable();
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            // An external reference (from the bank API)
            $table->string('external_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
};
