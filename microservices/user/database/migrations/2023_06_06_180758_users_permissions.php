<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create( 'users_permissions', function (Blueprint $table) {
            $table->string( 'user_id' );
            $table->foreignId( 'permission_id' );
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->foreign( 'permission_id' )->references( 'id' )->on( 'permissions' )->onDelete( 'cascade' );
            $table->primary( [ 'permission_id', 'user_id' ] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists( 'users_permissions' );
    }
};
