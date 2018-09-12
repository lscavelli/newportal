<?php

return [
    /*
    |--------------------------------------------------------------------------
    | widgets Asset Publisher
    |--------------------------------------------------------------------------
    |  Aggregatore di contenuti web - è possibile
    |  taggare e categorizzare i contenuti da visualizzare
    |
    */
    'widgets' => [
        'contentList' => [
            'name'          =>  'Aggregatore di contenuti',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.2',
            'date'          =>  '07/04/2018',
            'description'   =>  'Widget per la creazione automatica di liste di contenuti - configurabile',
            'type'          =>  '1',
            'path'          =>  'scavelli\contentlist',
            'service'       =>  'App\Models\Content\Content'
        ]
    ]
];