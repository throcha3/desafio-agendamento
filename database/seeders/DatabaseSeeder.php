<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
          'name' => 'Admin',
          'password' => bcrypt('123xe'),
          'email' => 'admin@admin.com'
        ]);

        for ($i=1; $i <= 14 ; $i++) { 
        	\App\Models\Space::create([
	          'name' => "Espaço - ".$i,
	        ]);
        }

        \App\Models\Parameter::create([
          'minimum_days' => 5,
          'antecedence_days' => 10,
        ]);

    }
}
