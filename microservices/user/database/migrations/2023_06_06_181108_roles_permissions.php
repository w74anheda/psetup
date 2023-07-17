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
        Schema::create( 'roles_permissions', function (Blueprint $table) {
            $table->bigInteger( 'role_id' )->unsigned();
            $table->foreignId( 'permission_id' );
            $table->foreign( 'role_id' )->references( 'id' )->on( 'roles' )->onDelete( 'cascade' );
            $table->foreign( 'permission_id' )->references( 'id' )->on( 'permissions' )->onDelete( 'cascade' );
            $table->primary( [ 'permission_id', 'role_id' ] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists( 'roles_permissions' );
    }
};
