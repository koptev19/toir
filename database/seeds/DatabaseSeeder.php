<?php

use App\Models\Department;
use App\Models\Equipment;
use App\Models\Line;
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
        $this->user_relations();
    }

    public function users()
    {
        User::create([
            'name' => 'Админ',
            'last_name' => 'Админыч',
            'email' => 'admin@toir24.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Начальник',
            'last_name' => 'Службы 1',
            'email' => 'user1@toir24.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => false,
            'all_workshops' => true,
        ]);

        User::create([
            'name' => 'Начальник',
            'last_name' => 'Службы 2',
            'email' => 'user2@toir24.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => false,
            'all_workshops' => true,
        ]);

        User::create([
            'name' => 'Сотрудник',
            'last_name' => 'Службы 1 по одному цеху',
            'email' => 'user3@toir24.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Сотрудник',
            'last_name' => 'Службы 2',
            'email' => 'user4@toir24.ru',
            'password' => '$2y$10$BRSXoX4La6wck/VzJNoSy.q8tEdgMEzAxidnkHCme3Q1ol3t7F5.O', //password
            'connected' => true,
            'is_admin' => false,
        ]);
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
        $lines = Line::all();
        foreach($lines as $line) {
            Stop::create([
                'workshop_id' => $line->workshop_id,
                'line_id' => $line->id,
                'date' => '2021-02-01',
            ]);
            Stop::create([
                'workshop_id' => $line->workshop_id,
                'line_id' => $line->id,
                'date' => '2021-03-01',
            ]);
        }
    }

    public function settings()
    {
        Setting::create([
            'name' => 'plan_month_day',
            'value' => 15,
        ]);
    }

    public function user_relations()
    {
        $user1 = User::find(2);
        $user1->departments()->sync([1]);

        $user2 = User::find(3);
        $user2->departments()->sync([2]);

        $user3 = User::find(4);
        $user3->departments()->sync([1]);
        $user3->workshops()->sync([1]);

        $user4 = User::find(5);
        $user4->departments()->sync([2]);
        $user4->workshops()->sync([1]);
    }
}
