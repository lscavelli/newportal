<?php

use Illuminate\Database\Seeder;
use App\Models\Content\Widget;

class WidgetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dirWidgets = app_path().'/'.config('newportal.widgets.namespace').'/';
        DB::table('widgets')->delete();
        if (is_dir($dirWidgets)) {
            foreach (File::allFiles($dirWidgets) as $file) {
                $pathFile = (string)$file;
                if (class_basename($pathFile)=="config.php") {
                    foreach (File::getRequire($pathFile)['widgets'] as $init=>$widget) {
                        $widget['init'] = $init;
                        $widget['type_id'] = $widget['type'];
                        unset($widget['type']);
                        Widget::create($widget);
                    }
                }
            }
        }
    }
}
