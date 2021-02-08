<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEquipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->renameColumn('nachalnik_tsekha', 'manager_id');
            $table->dropColumn('id_papki_dokumentatsiya');
            $table->renameColumn('opisanie', 'description');
            $table->dropColumn('external_view_id');
            $table->unsignedBigInteger('sketch_id')->nullable()->change();
            $table->dropColumn('documentation_id');
            $table->dropColumn('sostoyanie');
            $table->dropColumn('zavodskoy_nomer');
            $table->renameColumn('inventarnyy_nomer', 'inventory_number');
            $table->renameColumn('data_vvoda', 'enter_date');
            $table->dropColumn('garantiya_do');
            $table->dropColumn('vremya_zhizni');
            $table->dropColumn('article');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->date('enter_date')->nullable()->change();

            $table->foreign('parent_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('workshop_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        
            $table->foreign('line_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('manager_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('mechanic_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('photo_id')->references('id')->on('files')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('sketch_id')->references('id')->on('files')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('filepath');
        });
    
        Schema::create('equipments_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('document_id');

            $table->foreign('equipment_id')->references('id')->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('document_id')->references('id')->on('files')
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
        Schema::dropIfExists('equipments_documents');

        Schema::table('files', function (Blueprint $table) {
            $table->string('filepath');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['workshop_id']);        
            $table->dropForeign(['line_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['mechanic_id']);
            $table->dropForeign(['photo_id']);
            $table->dropForeign(['sketch_id']);
            $table->string('enter_date')->nullable()->change();
        });
        Schema::table('equipment', function (Blueprint $table) {
            $table->renameColumn('manager_id', 'nachalnik_tsekha');
            $table->unsignedBigInteger('id_papki_dokumentatsiya')->nullable();
            $table->renameColumn('description', 'opisanie');
            $table->string('external_view_id')->nullable();
            $table->unsignedInteger('sketch_id')->nullable()->change();
            $table->string('documentation_id')->nullable();
            $table->text('sostoyanie')->nullable();
            $table->text('zavodskoy_nomer')->nullable();
            $table->renameColumn('inventory_number', 'inventarnyy_nomer');
            $table->renameColumn('enter_date', 'data_vvoda');
            $table->text('garantiya_do')->nullable();
            $table->unsignedBigInteger('vremya_zhizni')->nullable();
            $table->text('article')->nullable();
        });
    }
}
