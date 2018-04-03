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
            'color' => '#00a65a',
            'content' => '{"varmodelli":{
                "np_image":"Immagine",
                "np_href":"Link"
                }}'
        ]);
        Service::create([
            'name' => 'Documenti',
            'class' => 'App\Models\Content\File',
            'color' => '#3c8dbc',
            'content' => '{"varmodelli":{
                "np_size":"Dimensione file",
                "np_extension":"Estensione",
                "np_fullpath":"Path",
                "np_file_name":"Nome del File",
                "np_mime_type":"Tipo file",
                "np_href":"Link pubblico",
                "np_class_icon":"Icona"
                }}'
        ]);
    }
}
