<?php

use App\Models\Department;
use App\Models\Equipment;
use App\Models\Setting;
use App\Models\Stop;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);
        $this->users();
        $this->equipment();
        $this->departments();
        $this->stops();
        $this->settings();
    }

    public function users()
    {
        User::create([
            'name' => 'Админ',
            'last_name' => 'Админыч',
            'email' => 'admin@admin.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => true,
        ]);

        // User::create([
        //     'name' => 'User1',
        //     'last_name' => 'User1',
        //     'email' => 'user1@user1.ru',
        //     'password' => 'user1',
        //     'connected' => false,
        //     'is_admin' => false,
        // ]);
    }

    public function equipment()
    {
        $workshop1 = Workshop::create([
            'name' => 'Цех 1',
            'mechanic_id' => 1,
        ]);

        $workshop2 = Workshop::create([
            'name' => 'Цех 2',
            'mechanic_id' => 1,
        ]);

        $line1 = $workshop1->lines()->create(['name' => 'Линия 1.1']);
        $line2 = $workshop1->lines()->create(['name' => 'Линия 1.2']);

        $workshop2->lines()->create(
            ['name' => 'Линия 2.1'],
            ['name' => 'Линия 2.2']
        );

        $line1->children()->create([
            'name' => 'Механизм 1',
            'type' => Equipment::TYPE_MECHANISM,
        ]);
    }

    public function departments()
    {
        Department::create([
            'name' => 'Служба 1',
            'short_name' => 'С1',
            'manager_id' => 1,
        ]);

        Department::create([
            'name' => 'Служба 2',
            'short_name' => 'С2',
            'manager_id' => 1,
        ]);
    }

    public function stops()
    {
        Stop::create([
            'workshop_id' => 1,
            'line_id' => 3,
            'date' => '2021-01-06',
        ]);
        Stop::create([
            'workshop_id' => 1,
            'line_id' => 3,
            'date' => '2021-01-13',
        ]);
        Stop::create([
            'workshop_id' => 1,
            'line_id' => 3,
            'date' => '2021-01-20',
        ]);
        Stop::create([
            'workshop_id' => 1,
            'line_id' => 3,
            'date' => '2021-01-27',
        ]);
    }

    public function settings()
    {
        Setting::create([
            'name' => 'plan_month_day',
            'value' => 20,
        ]);
    }
}
