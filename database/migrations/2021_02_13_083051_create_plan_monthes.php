<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanMonthes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_monthes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year');
            $table->unsignedInteger('month');
            $table->unsignedInteger('stage');
            $table->unsignedBigInteger('workshop_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('operations', function (Blueprint $table) {
            $table->unsignedBigInteger('source_operation_id')->nullable()->after('work_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn('source_operation_id');
        });

        Schema::dropIfExists('plan_monthes');
    }
}
