<?php

use Illuminate\Database\Seeder;
use App\Models\Content\Portlet;

class PortletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dirPortlets = app_path().'/'.config('newportal.portlets.namespace').'/';
        DB::table('portlets')->delete();
        if (is_dir($dirPortlets)) {
            foreach (File::allFiles($dirPortlets) as $file) {
                $pathFile = (string)$file;
                if (class_basename($pathFile)=="config.php") {
                    foreach (File::getRequire($pathFile)['portlets'] as $init=>$portlet) {
                        $portlet['init'] = $init;
                        $portlet['type_id'] = $portlet['type'];
                        unset($portlet['type']);
                        Portlet::create($portlet);
                    }
                }
            }
        }
    }
}
