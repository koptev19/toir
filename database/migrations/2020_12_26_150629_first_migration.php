<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FirstMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Файлы
        $this->files();

        // Пользователи
        $this->users();

        // Оборудование
        $this->equipment();

        // Службы
        $this->departments();

        $this->user_equipment();

        $this->user_departments();

        // Дни остановки
        $this->stops();

        // Аварии
        $this->crashes();

        // Шаблоны приемки оборудования
        $this->accepts();

        // Приемка оборудования
        $this->accept_histories();

        // Заявки на ремонт
        $this->service_requests();

        // Рабочие
        $this->workers();

        // Процесс планирования / отчета
        $this->date_processes();

        // Плановые операции
        $this->plans();

        // Работы без даты
        $this->works();

        // Операции
        $this->operations();

        // История операций
        $this->histories();

        $this->operations_service_requests();
        $this->histories_service_requests();

        // Время работ для рабочих
        $this->worktimes();
    
        // Отложенные списания
        $this->dalayed_writeoffs();
    
        // Простои
        $this->downtimes();
    
        // Списания
        $this->writeoffs();
    
        // Настройки
        $this->settings();
    }

    /**
     * Файлы
     */
    private function files()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_name');
            $table->string('filename');
            $table->string('filepath');
            $table->string('mime');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Пользователи
     */
    private function users()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('email', 80)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->text('toir_session')->nullable();
            $table->boolean('connected')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('all_workshops')->default(false);
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Оборудование
     */
    private function equipment()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('type', 255);
            $table->string('name', 255);
            $table->unsignedInteger('level');
            $table->unsignedBigInteger('workshop_id')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->string('short_name', 255)->nullable();
            $table->unsignedBigInteger('nachalnik_tsekha')->nullable();
            $table->unsignedBigInteger('mechanic_id')->nullable();
            $table->unsignedBigInteger('id_papki_dokumentatsiya')->nullable();
            $table->longtext('opisanie')->nullable();
            $table->unsignedBigInteger('photo_id')->nullable();
            $table->string('external_view_id')->nullable();
            $table->string('sketch_id')->nullable();
            $table->string('documentation_id')->nullable();
            $table->text('sostoyanie')->nullable();
            $table->text('zavodskoy_nomer')->nullable();
            $table->text('inventarnyy_nomer')->nullable();
            $table->text('data_vvoda')->nullable();
            $table->text('garantiya_do')->nullable();
            $table->unsignedBigInteger('vremya_zhizni')->nullable();
            $table->text('article')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('parent_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('mechanic_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Службы
     */
    private function departments()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('name', 255);
            $table->string('short_name', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('manager_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    private function user_equipment()
    {
        Schema::create('equipment_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('equipment_id');

            // $table->foreign('user_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    private function user_departments()
    {
        Schema::create('department_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_id');

            // $table->foreign('user_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('department_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Дни остановки
     */
    private function stops()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('crash_id')->nullable();
            $table->string('date');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Аварии
     */
    private function crashes()
    {
        Schema::create('crashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedInteger('status');
            $table->unsignedBigInteger('stop_id');
            $table->string('date', 255);
            $table->string('time_from', 10);
            $table->string('time_to', 10);
            $table->longtext('description')->nullable();
            $table->text('owner')->nullable();
            $table->longtext('decision')->nullable();
            $table->string('documents')->nullable();
            $table->string('decision_documents')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('stop_id')->references('id')->on('stops')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });

        Schema::table('stops', function (Blueprint $table) {
            // $table->foreign('crash_id')->references('id')->on('crashes')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Шаблоны приемки оборудования
     */
    private function accepts()
    {
        Schema::create('accepts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->text('checklist')->nuallable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Приемка оборудования
     */
    private function accept_histories()
    {
        Schema::create('accept_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('accept_id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedInteger('stage');
            $table->text('comment')->nullable();
            $table->text('comment_done')->nullable();
            $table->string('user_second_name');
            $table->string('files')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('accept_id')->references('id')->on('accepts')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Заявки на ремонт
     */
    private function service_requests()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('receiving_id')->nullable();
            $table->unsignedBigInteger('crash_id')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('user_done')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('author_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('crash_id')->references('id')->on('crashes')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('receiving_id')->references('id')->on('receivings')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('user_done')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Рабочие
     */
    private function workers()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->string('name', 255);
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Процесс планирования / отчета
     */
    private function date_processes()
    {
        Schema::create('date_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('service_id');
            $table->string('date', 255);
            $table->string('stage', 255);
            $table->text('comment_reject')->nullable();
            $table->timestamp('plan_done')->nullable();
            $table->unsignedBigInteger('plan_user_id')->nullable();
            $table->unsignedBigInteger('plan_approve_admin_id')->nullable();
            $table->timestamp('plan_approve_date')->nullable();
            $table->unsignedBigInteger('plan_reject_admin_id')->nullable();
            $table->timestamp('plan_reject_date')->nullable();
            $table->timestamp('report_done')->nullable();
            $table->unsignedBigInteger('report_user_id')->nullable();
            $table->text('comment_expired')->nullable();
            $table->text('report_comment_expired')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('plan_user_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('plan_approve_admin_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('report_user_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Плановые операции
     */
    private function plans()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('crash_id')->nullable();
            $table->unsignedInteger('periodicity')->nullable();
            $table->string('type_to');
            $table->string('type_operation');
            $table->string('start_date');
            $table->text('recommendation')->nullable();
            $table->string('next_execution_date');
            $table->string('task_result')->nullable();
            $table->string('last_date_from_checklist')->nullable();
            $table->text('comment_no_result')->nullable();
            $table->string('reason');
            $table->string('operations_not_done')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('crash_id')->references('id')->on('crashes')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Работы без даты
     */
    private function works()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('service_id');
            $table->date('last_complited')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }
    
    /**
     * Операции
     */
    private function operations()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('crash_id')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('date_process_id')->nullable();
            $table->string('type_operation', 255);
            $table->string('owner', 255)->nullable();
            $table->text('recommendation')->nullable();
            $table->text('comment')->nullable();
            $table->string('work_time', 255)->nullable();
            $table->string('start_date', 255)->nullable();
            $table->string('planned_date', 255);
            $table->string('task_result', 255)->nullable();
            $table->string('last_date_from_checklist', 255)->nullable();
            $table->string('comment_no_result', 255)->nullable();
            $table->unsignedInteger('periodicity')->nullable();
            $table->string('reason', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('plan_id')->references('id')->on('plans')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('work_id')->references('id')->on('works')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('crash_id')->references('id')->on('crashes')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('date_process_id')->references('id')->on('date_processes')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * История операций
     */
    private function histories()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('operation_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedInteger('periodicity')->nullable();
            $table->string('type_operation', 255)->nullable();
            $table->string('planned_date', 255);
            $table->string('start_date', 255);
            $table->string('completion_date', 255);
            $table->text('recommendation')->nullable();
            $table->text('comment', 255)->nullable();
            $table->text('comment_no_result', 255)->nullable();
            $table->string('work_time', 255)->nullable();
            $table->string('reason', 255);
            $table->string('result');
            $table->string('owner')->nullable();
            $table->string('source');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('workshop_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('line_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        
            // $table->foreign('equipment_id')->references('id')->on('equipment')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_id')->references('id')->on('departments')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('operation_id')->references('id')->on('operations')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('plan_id')->references('id')->on('plans')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('work_id')->references('id')->on('works')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('author_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    private function operations_service_requests()
    {
        Schema::create('operations_service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('operation_id');
            $table->unsignedBigInteger('service_request_id');

            // $table->foreign('operation_id')->references('id')->on('operations')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_request_id')->references('id')->on('service_requests')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    private function histories_service_requests()
    {
        Schema::create('histories_service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('history_id');
            $table->unsignedBigInteger('service_request_id');

            // $table->foreign('history_id')->references('id')->on('histories')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('service_request_id')->references('id')->on('service_requests')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Время работ для рабочих
     */
    private function worktimes()
    {
        Schema::create('worktimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('operation_id');
            $table->string('action', 255);
            $table->string('time_from', 255);
            $table->string('time_to', 255);
            $table->string('group', 255);
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('worker_id')->references('id')->on('workers')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('operation_id')->references('id')->on('operations')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Отложенные списания
     */
    private function dalayed_writeoffs()
    {
        Schema::create('dalayed_writeoffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('operation_id');
            $table->boolean('is_done');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('author_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('operation_id')->references('id')->on('operations')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Простои
     */
    private function downtimes()
    {
        Schema::create('downtimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->string('machine');
            $table->unsignedInteger('stage');
            $table->date('date');
            $table->string('time_from')->nullable();
            $table->string('time_to');
            $table->string('master')->nullable();
            $table->text('comment')->nullable();
            $table->text('comment_service')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('author_id')->references('id')->on('users')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');

            // $table->foreign('operation_id')->references('id')->on('operations')
            //     ->onUpdate('cascade')
            //     ->onDelete('restrict');
        });
    }

    /**
     * Списания
     */
    private function writeoffs()
    {
        Schema::create('writeoffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('operation_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->unsignedInteger('quantity');
            $table->string('guid');
            $table->string('store');
            $table->date('date');
            $table->string('unit');
            $table->string('movingdate');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Настройки
     */
    private function settings()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('writeoffs');
        Schema::dropIfExists('downtimes');
        Schema::dropIfExists('dalayed_writeoffs');
        Schema::dropIfExists('worktimes');
        Schema::dropIfExists('histories_service_requests');
        Schema::dropIfExists('operations_service_requests');
        Schema::dropIfExists('histories');
        Schema::dropIfExists('operations');
        Schema::dropIfExists('works');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('date_processes');
        Schema::dropIfExists('workers');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('accept_histories');
        Schema::dropIfExists('accepts');
        Schema::dropIfExists('crashes');
        Schema::dropIfExists('stops');
        Schema::dropIfExists('equipment_users');
        Schema::dropIfExists('department_users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('files');
    }
}
