<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //User,Item,Label,Comment letrehozasa

         $users_count =10;
         $users = collect();
         for ($i=1;$i <= $users_count;$i++){
            $users->add(\App\Models\User::factory()->create([
                    'email' => 'user'.$i.'@szerveroldali.hu',
                    'password' =>  bcrypt('password'),
            ]));
         }
         \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@szerveroldali.hu',
             'password' =>  bcrypt('adminpwd'),
            'is_admin' => true,
        ]);

        $items= \App\Models\Item::factory(10)->create();
        $labels = \App\Models\Label::factory(5)->create();
         $comments=\App\Models\Comment::factory(10)->create();
        
        //Item es User hozzakapcsolasa a Commenthez
         $comments->each(function ($comment) use(&$users){
            $comment->user()->associate($users->random())->save();
        });

        $comments->each(function ($comment) use (&$items){
            $comment->item()->associate($items->random())->save();
        });
        
        //N-N kapcsolat
       
        $items->each(function ($item) use (&$labels) {
            $item->labels()->sync(
                $labels->random(
                    rand(1, $labels->count())
                )
            );
        });

    }


}
