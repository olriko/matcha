<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = \App\Models\Tag::get();

        factory(\App\Models\User::class, 600)->create()->each(function($user) use ($tags) {
            $user->tags()->attach(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );

            $user->images()->create([
                'main' => true,
                'name' => $user->gender === 'female' ? 'woman.png' : 'man.png'
            ]);
        });
    }
}
