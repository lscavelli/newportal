<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Widgets Organizations
    |--------------------------------------------------------------------------
    |  i frame del master sono sempre disponibili per l'assegnazione dei contenuti
    |
    */
    'widgets' => [
        'listOrganizations' => [
            'name'          =>  'Graphic Representation',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '17/02/2017',
            'description'   =>  'Widget per la rappresentazione grafica delle organizzazioni - alpha release',
            'type'          =>  '1',
            'path'          =>  'scavelli\organizations'
        ],
        'cardOrganization'  =>  [
            'name'          =>  'Profilo organization',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.4',
            'date'          =>  '15/02/2017',
            'description'   =>  'Scheda di dettaglio dell\'organizzazione - alpha release',
            'type'          =>  '1',
            'path'          =>  'scavelli\organizations'
        ]
    ],

];