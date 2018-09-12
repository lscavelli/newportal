<?php

namespace App\Services;

use App\Repositories\RepositoryInterface;

abstract class Packages {

    public static $packages = [];
    private static $rp;

    public function __construct(RepositoryInterface $rp) {
        self::$rp = $rp;
    }

    public static function config($key, $default = null)
    {
        $name = array_search(get_called_class(), self::$packages);
        $key = sprintf('admin.extensions.%s.%s', strtolower($name), $key);
        return config($key, $default);
    }

    public static function import() {}

    protected static function makePermission($name, $slug, $description=null)
    {
        self::$rp->setModel('App\Models\Permission')
        ->create([
            'name'          => $name,
            'slug'          => $slug,
            'description'   => $description
        ]);
    }

    protected static function makeRole()
    {
    }

    protected static function makeMenu()
    {
    }

    public static function addPackage($name, $class)
    {
        self::$packages[$name] = $class;
    }
}
