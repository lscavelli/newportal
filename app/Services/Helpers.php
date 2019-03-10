<?php

namespace App\Services;

class Helpers {

    /**
     * Restituisce una password generata automaticamente.
     * @param int $length
     * @return null|string
     */
    public static function makeCode($length=6) {
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

    /**
     * Restituisce un sommario senza troncare il testo
     * @param $testo
     * @param string $num_char
     * @param bool $strip_tags
     * @param string $pos
     * @return bool|string
     */
    public static function sommario($testo, $num_char='255', $strip_tags=true, $pos=' ') {
        if ($strip_tags) $testo = strip_tags($testo);
        $lung_str= strlen($testo);
        if ($lung_str<$num_char)
            return $testo;
        else {
            //$voce= "<strong>".$voce."</strong>";
            $testo=substr($testo,0,$num_char);
            $spazio_bianco=strrpos($testo,$pos);
            $testo_1=substr($testo,0,$spazio_bianco);
            return $testo_1."...";
        }
    }

}
