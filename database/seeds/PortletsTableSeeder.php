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
            'revision' => '1.2',
            'date' => '07/04/2018',
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
        Portlet::create([
            'name' => 'Navigazione per categopria',
            'init' => 'navigationCat',
            'path' => 'scavelli\navigationcat',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '08/06/2017',
            'description' => 'Widget per la navigazione per categoria - configurabile'
        ]);
        Portlet::create([
            'name' => 'Navigazione per tag',
            'init' => 'navigationTag',
            'path' => 'scavelli\navigationtag',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '14/06/2017',
            'description' => 'Widget per la navigazione per tag - configurabile'
        ]);
        Portlet::create([
            'name' => 'Aggregatore di contenuti',
            'init' => 'contentList',
            'path' => 'scavelli\contentlist',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.2',
            'date' => '07/04/2018',
            'description' => 'Widget per la creazione automatica di liste di contenuti - configurabile'
        ]);
        Portlet::create([
            'name' => 'Graphic Representation',
            'init' => 'listOrganizations',
            'path' => 'scavelli\organizations',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '17/02/2017',
            'description' => 'Widget per la rappresentazione grafica delle organizzazioni - alpha release'
        ]);
        Portlet::create([
            'name' => 'Aggregatore di documenti',
            'init' => 'documentList',
            'path' => 'scavelli\documentlist',
            'type_id' => 1,
            'status_id' => 1,
            'author' => 'Scavelli',
            'revision' => '1.0',
            'date' => '07/04/2018',
            'description' => 'Widget per la creazione automatica di liste di documenti - configurabile'
        ]);
    }
}
