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
            'class' => 'App\Models\Content\Content'
        ]);
        Service::create([
            'name' => 'Blog',
            'class' => 'App\Models\Blog\Post'
        ]);
    }
}
