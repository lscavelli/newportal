<?php

namespace App\Http\Controllers\General;


use App\Http\Controllers\Controller;


class RouteController extends Controller {

    public function __construct()  {
        $this->middleware('auth');
    }

    /**
     * Mostra il form con i settings
     * @return \Illuminate\Contracts\View\View
     */
    public function getRoutes()   {
        $routes = collect(app('router')->getRoutes())->map(function ($route) {
            return [
                'uri' => $route->uri,
                'as' => $route->action['as'] ?? '',
                'methods' => $route->methods,
                'action' => $route->action['uses'] ?? '',
                'middleware' => $route->action['middleware'] ?? [],
            ];
        });

        return response()->json($routes);
    }




}
