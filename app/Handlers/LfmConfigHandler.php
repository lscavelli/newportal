<?php

namespace App\Handlers;

class LfmConfigHandler
{

    /**
     * set in config/lfm.php 'shared_folder_name' => App\Handlers\LfmConfigHandler::class
     * add in function getRootFolder() of the vendor/../Lfm.php
     * $folder = $this->config->get('lfm.shared_folder_name');
     * if (class_exists($folder)) {
     *      $folder = app()->make($folder)->setFolderName();
     * }
     *
     * in resources/views/vendor/laravel-filemanager/index.blade.php
     * <script>
     *      @if(request()->has(['restype','resvalue']))
     *          {{ session()->put(request('restype'), request('resvalue')) }}
     *          goTo('/shares/{{ request('restype') }}/{{ request('resvalue') }}');
     *      @endif
     * </script>
     * @return string
     */
    public function setFolderName()
    {
        if (request()->has(['restype','resvalue'])) {
            return 'shares/'.request('restype').'/'.request('resvalue');
        }
        return 'shares';

    }
}
