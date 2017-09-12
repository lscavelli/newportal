<?php

use Illuminate\Database\Seeder;
use App\Models\Content\Portlet;

class PortletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Portlet::create([
            'name' => 'Visualizzazione Contenuto Web',
            'init' => 'viewWebContent',
            'path' => 'scavelli\webcontent',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '19/02/2017',
            'description' => 'Widget per la visualizzazione dei contenuti web - completo di strutture e modelli'
        ]);
        Portlet::create([
            'name' => 'Navigazione delle pagine',
            'init' => 'navigationPages',
            'path' => 'scavelli\navigationpages',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '13/03/2017',
            'description' => 'Widget per la navigazione delle pagine attive - configurabile'
        ]);
    }
}
