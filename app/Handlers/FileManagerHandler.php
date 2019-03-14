<?php

namespace App\Handlers;
use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;


class FileManagerHandler implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return null;
    }


    public function getRules(): array
    {
        return  [
            ['disk' => 'public', 'path' => '*', 'access' => 2],
            //['disk' => 'public', 'path' => 'images', 'access' => 1],
            //['disk' => 'public', 'path' => 'images/general', 'access' => 1],
            //['disk' => 'public', 'path' => 'images/general/*', 'access' => 2],
        ];
    }
}