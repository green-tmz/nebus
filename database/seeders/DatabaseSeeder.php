<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем виды деятельности
        $food = Activity::create(['name' => 'Еда']);
        $meat = Activity::create(['name' => 'Мясная продукция', 'parent_id' => $food->id, 'level' => 2]);
        $dairy = Activity::create(['name' => 'Молочная продукция', 'parent_id' => $food->id, 'level' => 2]);

        $cars = Activity::create(['name' => 'Автомобили']);
        $trucks = Activity::create(['name' => 'Грузовые', 'parent_id' => $cars->id, 'level' => 2]);
        $passenger = Activity::create(['name' => 'Легковые', 'parent_id' => $cars->id, 'level' => 2]);
        $parts = Activity::create(['name' => 'Запчасти', 'parent_id' => $passenger->id, 'level' => 3]);
        $accessories = Activity::create(['name' => 'Аксессуары', 'parent_id' => $passenger->id, 'level' => 3]);

        // Создаем здания
        $building1 = Building::create([
            'address' => 'г. Москва, ул. Ленина 1, офис 3',
            'latitude' => 55.755826,
            'longitude' => 37.6172999
        ]);

        $building2 = Building::create([
            'address' => 'г. Санкт-Петербург, Невский пр. 10',
            'latitude' => 59.9342802,
            'longitude' => 30.3350986
        ]);

        // Создаем организации
        $org1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => $building1->id
        ]);
        $org1->phones()->create(['phone' => '2-222-222']);
        $org1->phones()->create(['phone' => '3-333-333']);
        $org1->activities()->attach([$food->id, $meat->id]);

        $org2 = Organization::create([
            'name' => 'ЗАО "Молочные реки"',
            'building_id' => $building2->id
        ]);
        $org2->phones()->create(['phone' => '8-923-666-13-13']);
        $org2->activities()->attach([$dairy->id]);
    }
}
