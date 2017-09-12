<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function __construct()  {
        $this->middleware('auth');
    }

    public function activitiesData() {
        $activity = DB::table('activity')
            ->select(DB::raw('count(*) as activity, DATE_FORMAT(created_at, "%m") as "_month", MONTHNAME(created_at) as label'))
            ->whereYear('created_at', '=', date('Y'))
            ->groupBy(['_month','label'])
            ->orderBy('_month','ASC')
            ->get();
        if ($activity->count()<2) {
            $activity = DB::table('activity')
                ->select(DB::raw('count(*) as activity, DATE_FORMAT(created_at, "%d") as "label"'))
                ->whereYear('created_at', '=', date('Y-m'))
                ->groupBy(['label'])
                ->orderBy('label','ASC')
                ->get();
        };

        //$data=array_map(function($item){
        //    return (array) $item;
        //},$activity);
        return $activity;
    }

    public function index() {
        $users = \App\Models\User::all()->count();
        $roles = \App\Models\Role::all()->count();
        $permissions = \App\Models\Permission::all()->count();
        //$activities = $this->activitiesData();
        return view('dashboard.dashboard', compact('users', 'roles', 'permissions'));

    }
}
