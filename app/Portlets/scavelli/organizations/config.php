<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Portlets Organizations
    |--------------------------------------------------------------------------
    |  i frame del master sono sempre disponibili per l'assegnazione dei contenuti
    |
    */
    'portlets' => [
        'listOrganizations' => [
            'name'          =>  'Graphic Representation',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '17/02/2017',
            'description'   =>  '',
            'type'          =>  '1',
            'path'          =>  'scavelli\organizations'
        ],
        'cardOrganization'  =>  [
            'name'          =>  'Profilo organization',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.4',
            'date'          =>  '15/02/2017',
            'description'   =>  'Scheda di dettaglio dell\'organizzazione',
            'type'          =>  '1',
            'path'          =>  'scavelli\organizations'
        ]
    ],

];