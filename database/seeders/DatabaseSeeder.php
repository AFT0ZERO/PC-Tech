<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
         \App\Models\User::factory(10)->create();
         \App\Models\Contact::factory(10)->create();
         \App\Models\Faqs::factory(10)->create();

    }
}
