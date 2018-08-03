<?php

namespace App\Services;

class Helpers {

    /**
     * Restituisce una password generata automaticamente.
     * @param int $length
     * @return null|string
     */
    function makeCode($length=6) {
        // Sono stati eliminati l,I,O,1 e 0 perchè generano confusione
        $allow_char = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $len = strlen($allow_char);
        mt_srand((double)microtime() * 1000000);
        $code = NULL;
        for ($i = 0; $i < $length; $i++) {
            $code .= $allow_char[mt_rand(0, $len - 1)];
        }
        return $code;
    }

}