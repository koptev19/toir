<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAccepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accepts', function (Blueprint $table) {
            $table->dropColumn('workshop_id');
            $table->dropColumn('line_id');
            $table->text('checklist')->nullable()->change();
        
            $table->foreign('equipment_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accepts', function (Blueprint $table) {
            $table->dropForeign(['equipment_id'])->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
        });
    }
}
