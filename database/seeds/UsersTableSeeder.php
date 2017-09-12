<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Content\Page;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'nome' => 'NewPortal',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'note' => 'Password provvisoria da cambiare dopo il primo accesso',
        ]);

        $superadmin = Role::where('slug', config('newportal.super_admin'))->first();
        $user->roles()->attach($superadmin);

        Page::create([
            'name' => 'Welcome',
            'slug' => 'welcome',
            'layout' => 'home',
            'theme' => 'default',
            'description' => 'Pagina demo di benvenuto',
            'status_id' => 1,
            'user_id' => $user->id,
            'username' => $user->username,
        ]);

        /**
         * per uso development
         */
        //factory(App\Models\User::class, 30)->make();
    }
}
