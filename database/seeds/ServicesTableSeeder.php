<?php

use Illuminate\Database\Seeder;
use App\Models\Content\Service;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'ContentWeb',
            'class' => 'App\Models\Content\Content',
            'color' => '#00a65a'
        ]);
        Service::create([
            'name' => 'Blog',
            'class' => 'App\Models\Blog\Post',
            'class' => '#f39c12'
        ]);
        Service::create([
            'name' => 'Documenti',
            'class' => 'App\Models\Content\File',
            'color' => '#3c8dbc'
        ]);
    }
}
