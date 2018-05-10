<?php

return [
    /*
    |--------------------------------------------------------------------------
    | portlets Asset Publisher
    |--------------------------------------------------------------------------
    |  Aggregatore di documenti - è possibile
    |  taggare e categorizzare i file da visualizzare
    |
    */
    'portlets' => [
        'documentList' => [
            'name'          =>  'Aggregatore di documenti',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '30/03/2018',
            'description'   =>  'Consente di visualizzare le liste di documenti ',
            'type'          =>  '1',
            'path'          =>  'scavelli\documentlist',
            'service'       =>  'App\Models\Content\File'
        ]
    ]
];