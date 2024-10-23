<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
//         \App\Models\User::factory(10)->create();
//         \App\Models\Contact::factory(10)->create();
//         \App\Models\Faqs::factory(10)->create();
//         \App\Models\Category::factory(10)->create();
//         \App\Models\Store::factory(3)->create();
//         \App\Models\Product::factory(3)->create();

         Category::create([
             'name' => 'Memory',
             'image'=>'uploads/category/1728563500.png'
         ]);
    }
}
