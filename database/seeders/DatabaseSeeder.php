<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Offer;
use App\Models\Category;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(1)->create([
            'name'=>'admin',
            'password'=>'admin',
            'role'=>'admin',
             'email'=>'admin@ecom.com'
         ]);

         Store::factory(1)->create([
            "mid"=>"311235136600003"
         ]);



         Store::factory(1)->create([
            "mid"=>"301071869100003"
         ]);

         Store::factory(1)->create([
            "mid"=>"311169907600003"
         ]);


         Category::factory(10)->create();


         Offer::factory(1)->create(['store_id'=>1,'available'=>true]);
         Offer::factory(1)->create(['store_id'=>2,'available'=>true]);
         Offer::factory(1)->create(['store_id'=>3,'available'=>true]);


         Offer::factory(10)->create();

         User::factory(1)->create([

            'password'=>'user',
            'role'=>'user',
             'email'=>'user@ecom.com'
         ]);


         User::factory(1)->create([

            'password'=>'user2',
            'role'=>'user',
             'email'=>'user2@ecom.com'
         ]);
         User::factory(1)->create([

            'password'=>'user3',
            'role'=>'user',
             'email'=>'user3@ecom.com'
         ]);
         User::factory(1)->create([

            'password'=>'user4',
            'role'=>'user',
             'email'=>'user4@ecom.com'
         ]);



    }
}
