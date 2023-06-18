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
        Schema::create('addresses', function (Blueprint $table)
        {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->string('full_address');
            $table->integer('house_number');
            $table->tinyInteger('unit_number');
            $table->string('postalcode', 20);
            $table->point('point');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
