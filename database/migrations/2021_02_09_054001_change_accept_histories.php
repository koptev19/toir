<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAcceptHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accept_histories', function (Blueprint $table) {
            $table->dropColumn('workshop_id');
            $table->dropColumn('line_id');
            $table->dropColumn('equipment_id');
            $table->dropColumn('files');
            $table->renameColumn('user_second_name', 'fio');

            $table->foreign('accept_id')->references('id')->on('accepts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::table('accept_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->after('fio')->nullable();

            $table->foreign('author_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('accept_histories_files', function (Blueprint $table) {
            $table->unsignedBigInteger('accept_history_id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('accept_history_id')->references('id')->on('accept_histories')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('file_id')->references('id')->on('files')
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
        Schema::dropIfExists('accept_histories_files');

        Schema::table('accept_histories', function (Blueprint $table) {
            $table->dropForeign(['accept_id']);
            $table->dropForeign(['author_id']);

            $table->dropColumn('author_id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->text('files');
            $table->renameColumn('fio', 'user_second_name');
        });
    }
}
