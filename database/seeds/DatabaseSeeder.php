<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Seed\Tag::class, 30)->create();

         $this->call(UserSeeder::class);
    }
}
