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
         * Permessi del service Blog
         */
        Permission::create([
            'name' => 'Crea articolo',
            'slug' => 'create-post',
            'description' => 'Consente la creazione di un Post per il service Blog'
        ]);
        Permission::create([
            'name' => 'Modifica articolo',
            'slug' => 'update-post',
            'description' => 'Consente la modifica di un Post per il service Blog'
        ]);
        Permission::create([
            'name' => 'Pubblica articolo',
            'slug' => 'publish-post',
            'description' => 'Consente la pubblicazione di un Post per il service Blog'
        ]);
    }
}
