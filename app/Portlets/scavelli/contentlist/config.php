<?php

return [
    /*
    |--------------------------------------------------------------------------
    | portlets Asset Publisher
    |--------------------------------------------------------------------------
    |  Aggregatore di contenuti web - è possibile
    |  taggare e categorizzare i contenuti da visualizzare
    |
    */
    'portlets' => [
        'contentList' => [
            'name'          =>  'Aggregatore di contenuti',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '17/05/2017',
            'description'   =>  '',
            'type'          =>  '1',
            'path'          =>  'scavelli\contentlist',
            'service'       =>  'App\Models\Content\Content'
        ]
    ]
];