<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            EquipmentSeeder::class,
            SportSeeder::class,
            EquipmentSportSeeder::class,
            RolesSeeder::class,
        ]);

        User::factory(10)->create();
        User::factory()->create([
            'login' => 'normalDude',
            'first_name' => 'Normal',
            'last_name' => 'Dude',
            'email' => 'normalDude@gmail.com',
            'password' => bcrypt('Password123'),
            'role_id' => 1, 
        ]);
        User::factory()->create([
            'login' => 'adminDude',
            'first_name' => 'Admin',
            'last_name' => 'Dude',
            'email' => 'adminDude@gmail.com',
            'password' => bcrypt('Password1234'),
            'role_id' => 2, 
        ]);
        Rental::factory(80)->create();

    }
}
