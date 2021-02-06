<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('equipment_users', 'users_workshops');
        Schema::rename('department_users', 'departments_users');

        Schema::table('users_workshops', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'workshop_id');
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
            $table->renameColumn('workshop_id', 'equipment_id');
        });

        Schema::rename('departments_users', 'department_users');
        Schema::rename('users_workshops', 'equipment_users');
    }
}
