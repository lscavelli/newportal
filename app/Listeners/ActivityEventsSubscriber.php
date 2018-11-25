<?php

namespace App\Listeners;

use App\Events\Created;
use App\Events\Updated;
use App\Events\Deleted;
use App\Events\Assigned;
use App\Events\Removed;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ActivityEventsSubscriber {

    public function onCreate(Created $event) {
        if (!Auth::check()) return;
        $this->writeToActivity($event,"Creato");
    }

    public function onUpdate(Updated $event) {
        $this->writeToActivity($event,'Aggiornato');
    }

    public function onDelete(Deleted $event) {
        $this->writeToActivity($event,'Cancellato');
    }

    public function onAssign(Assigned $event) {
        $model = $event->getModel();
        $children = $event->getAssigned();
        $message = "Assegnato \"$children->name\" (id $children->id - {$children->getTable()}) a \"$model->name\" (id $model->id - {$model->getTable()}) ";
        $this->saveToDbActivity($message);
    }

    public function onRemove(Removed $event) {
        $model = $event->getModel();
        $children = $event->getRemoved();
        /*
        $message = "Rimosso ";
        if ($children instanceof Collection) {
            $message .= $children->each(function ($item, $key) {
                 return "\"$item->name\" (id $item->id) ";
            });

        }*/
        $message = "Rimossi alcuni elementi ";
        $message .= "({$children->first()->getTable()})";
        $message .= " da \"$model->name\" (id $model->id - {$model->getTable()}) ";
        $this->saveToDbActivity($message);
    }

    public function onLogin() {
        $this->saveToDbActivity("Logged in");
    }

    public function onLogout() {
        $this->saveToDbActivity("Logged out");
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = 'App\Listeners\ActivityEventsSubscriber';
        $events->listen(Created::class, "{$class}@onCreate");
        $events->listen(Updated::class, "{$class}@onUpdate");
        $events->listen(Deleted::class, "{$class}@onDelete");
        $events->listen(Assigned::class, "{$class}@onAssign");
        $events->listen(Removed::class, "{$class}@onRemove");
        $events->listen('Illuminate\Auth\Events\Login', "{$class}@onLogin");
        $events->listen('Illuminate\Auth\Events\Logout', "{$class}@onLogout");
    }

    private function writeToActivity($event,$operation) {
        $model = $event->getModel();
        $name = "\"{$model->name}\" (id {$model->id})";
        //$message = sprintf("$operation $name in %s.",$model->getTable());
        $message = $operation . " ". $name ."in ".$model->getTable();
        $this->saveToDbActivity($message);
    }

    private function saveToDbActivity($message) {
        Activity::create([
            'user_id' => \Auth::user()->id,
            'description' => $message,
            'user_agent' => substr((string) \Request::header('User-Agent'), 0, 600),
            'ip_address' => \Request::ip()
        ]);
    }

}
