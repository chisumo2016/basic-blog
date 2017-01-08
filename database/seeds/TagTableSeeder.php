<?php

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Seeder
        $tag = new \App\Tag();
        $tag->name = 'Bernard';
        $tag->save();


        $tag = new \App\Tag();
        $tag->name = 'Bertha';
        $tag->save();
    }
}
