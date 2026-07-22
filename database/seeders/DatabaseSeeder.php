<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
         // Seed a stable test super-admin account (non-destructive)
         \App\Models\User::firstOrCreate(
             ['email' => 'superadmin@example.com'],
             [
                 'fname' => 'Super',
                 'lname' => 'Admin',
                 'role' => 'super-admin',
                 'password' => bcrypt('password123'),
                 'gender' => 'male',
                 'mobile' => '0700000000',
             ]
         );

         // PC Builder slot limits (non-destructive)
         $this->call(BuildSlotSeeder::class);

         // Scraper configuration from config.json
         $this->call(StoreScraperConfigSeeder::class);

         // Other random data
        //  \App\Models\User::factory(10)->create();
        //  \App\Models\Contact::factory(10)->create();
        //  \App\Models\Faqs::factory(10)->create();
        //  \App\Models\Category::factory(10)->create();
        //  \App\Models\Store::factory(3)->create();
        //  \App\Models\Product::factory(3)->create();

    }
}
