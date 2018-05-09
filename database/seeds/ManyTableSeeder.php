<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Content\Page;
use App\Models\Content\Structure;
use App\Models\Content\Service;

class ManyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Crea l'utente e gli assegna i permessi super admin
         */
        $user = User::create([
            'nome' => 'NewPortal',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'note' => 'Password provvisoria da cambiare dopo il primo accesso',
        ]);
        $superadmin = Role::where('slug', config('newportal.super_admin'))->first();
        $user->roles()->attach($superadmin);

        /**
         * Crea la pagina di ingresso
         */
        Page::create([
            'name' => 'Welcome',
            'slug' => 'welcome',
            'layout' => 'home',
            'theme' => 'default',
            'description' => 'Pagina demo di benvenuto',
            'status_id' => 1,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);

        /**
         * per uso development
         */
        //factory(App\Models\User::class, 30)->make();

        $json = File::get(base_path('database/data/modelli.json'));
        $data = json_decode($json,true);

        /**
         * Crea il service ContentWeb
         */
        $serviceCWeb = Service::create([
            'name' => 'ContentWeb',
            'class' => 'App\Models\Content\Content',
            'color' => '#00a65a',
            'content' => '{"varmodelli":{
                "np_image":"Immagine",
                "np_href":"Link"
                }}'
        ]);

        /**
         * Imposta la struttura di base per ContentWeb e i modelli
         */
        $structureCWeb = Structure::create([
            'name' => 'Contenuto base',
            'description' => 'Struttura di base del content web',
            'content' => File::get(base_path('database/data/content_base.json')),
            'service_id' => $serviceCWeb->id,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
        $structureCWeb->models()->createMany($data['ContentWeb']);

        /**
         * Crea il service Documenti
         */
        $serviceDoc =Service::create([
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

        /**
         * Struttura sul service "Documenti" x modelli documents & image list
         */
        $structureDoc = Structure::create([
            'name' => 'Modelli documents & image list',
            'description' => 'Contenitore di base dei modelli per files e images list',
            'content' => '',
            'service_id' => $serviceDoc->id,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
        $structureDoc->models()->createMany($data['Documenti']);

        /**
         * Struttura sul service "Documenti" x modelli imageView
         */
        $structureImg = Structure::create([
            'name' => 'Modelli imageView',
            'description' => 'Contenitore dei modelli per la visualizzazione delle immagini',
            'content' => '',
            'service_id' => $serviceDoc->id,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
        $structureImg->models()->createMany($data['Immagini']);

        /**
         * Struttura sul service "Documenti" x modelli imageSlider
         */
        $structureImg = Structure::create([
            'name' => 'Modelli imageSlider',
            'description' => 'Contenitore dei modelli per lo scorrimento delle immagini - Carousel',
            'content' => '',
            'service_id' => $serviceDoc->id,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
        $structureImg->models()->createMany($data['Slider']);
    }
}