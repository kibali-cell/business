<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable(); // Could be linked to users
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};
