<?php

return [
    /*
    |--------------------------------------------------------------------------
    | widgets Asset Publisher
    |--------------------------------------------------------------------------
    |  Aggregatore di documenti - è possibile
    |  taggare e categorizzare i file da visualizzare
    |
    */
    'widgets' => [
        'documentList' => [
            'id'            =>  2,
            'name'          =>  'Aggregatore di documenti',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.3',
            'date'          =>  '07/05/2018',
            'description'   =>  'Widget per la creazione automatica di liste di documenti - configurabile',
            'type'          =>  '1',
            'path'          =>  'scavelli\documentlist',
            'service'       =>  'App\Models\Content\File'
        ]
    ]
];
