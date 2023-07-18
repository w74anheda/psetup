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
        Schema::create('user_phone_verifications', function (Blueprint $table)
        {
            $table->string('user_id')->index();
            $table->string('code')->index();
            $table->string('hash')->unique()->index();
            $table->dateTime('expire_at');
            $table->primary([ 'code', 'hash' ]);
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_phone_verifications');
    }
};
