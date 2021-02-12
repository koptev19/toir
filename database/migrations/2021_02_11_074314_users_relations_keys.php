<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersRelationsKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_workshops', function (Blueprint $table) {
            $table->foreign('workshop_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->unique(['user_id', 'workshop_id']);
        });

        Schema::table('departments_users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->unique(['user_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_workshops', function (Blueprint $table) {
            $table->dropForeign(['workshop_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('departments_users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
