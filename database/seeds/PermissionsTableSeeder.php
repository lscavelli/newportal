<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Permessi per la gestione degli users (user, group, permission,
         * role, organizzation, session, activity etc...)
         */
        Permission::create([
            'name' => 'Gestione utenti',
            'slug' => 'users-manage',
            'description' =>
                'Consente la gestione degli utenti. Il ruolo "Super Admin" accede comunque a
                 tutte le funzionalità del sistema'
        ]);


        /**
         * Permessi del service Web Content
         */
        Permission::create([
            'name' => 'Crea contenuto',
            'slug' => 'create-content',
            'description' => 'Consente la creazione di un contenuto web per il service webContent'
        ]);
        Permission::create([
            'name' => 'Modifica contenuto',
            'slug' => 'update-content',
            'description' => 'Consente la modifica di un contentuto per il service webContent'
        ]);
        Permission::create([
            'name' => 'Cancella contentuo',
            'slug' => 'delete-content',
            'description' => 'Consente la cancellazione di un contenuto per il service webContent'
        ]);
        Permission::create([
            'name' => 'Controllo dei widgets',
            'slug' => 'widget-control',
            'description' => 'Consente di aggiungere, cancellare o modificare i widget delle pagine'
        ]);
    }
}
