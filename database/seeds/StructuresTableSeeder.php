<?php

use Illuminate\Database\Seeder;
use App\Models\Content\Structure;

class StructuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = File::get(base_path('database/data/content_base.json'));
        $structure = Structure::create([
            'name' => 'Contenuto base',
            'description' => 'Struttura di base del content web',
            'content' => $data,
            'type_id' => 2,
            'user_id' => 1,
            'username' => 'admin',
        ]);

        $json = File::get(base_path('database/data/modelli.json'));
        $data = json_decode($json,true);
        $structure->models()->createMany($data);
    }
}
