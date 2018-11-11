<?php

use Illuminate\Database\Seeder;
use App\Models\General\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('settings');
        Setting::create([
            'setting_key' => 'open_registration',
            'setting_value' => 0,
        ]);
        Setting::create([
            'setting_key' => 'social_registration',
            'setting_value' => 0,
        ]);
        Setting::create([
            'setting_key' => 'content_not_found',
            'setting_value' => 0,
        ]);
        Setting::create([
            'setting_key' => 'tag_dynamic',
            'setting_value' => 0,
        ]);
        Setting::create([
            'setting_key' => '2fa_activation',
            'setting_value' => 1,
        ]);

        $settings = Setting::pluck('setting_value', 'setting_key')->all();
        Cache::forever('settings', $settings);

    }
}
