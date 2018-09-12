<?php

namespace app\Services;

class sl_text {

    // Restituisce un array contenente le righe del testo divise per \n
    function toArray($text){
        if (!is_string($text)) exit ("l'argomento non è una stringa");
        if (strstr($text, "\r"))
            $text_array = explode("\r\n",$text);
        else
            $text_array = explode("\n",$text);
        return $text_array;
    }

    // Verifica se il testo è alfabetico
    function isAlpha ($text) {
        return !preg_match("/[^a-zA-Z]/", $text);
    }

    // Rimuove gli spazi dalla stringa
    function stripSpaces($text, $replace=NULL) {
        if (is_string($replace)) return str_replace(" ", substr($replace, 0, 1), $text);
        else return str_replace(" ", "", $text);
    }

    // Verifica se il testo è alfanumerico
    function alphaNum($text) {
        return preg_replace("/[^a-zA-Z0-9]/", "", $text);
    }

    // Rimuove gli apostrofi da una stringa

    function stripQuotes($text) {
        $text = str_replace("'", "", $text);
        $text = str_replace("\"", "", $text);
        return $text;
    }

    // Rimuove gli slashes solo dagli apostrofi
    function stripSlashQuotes($text){
        $text = str_replace("\\'", "'", $text);
        $text = str_replace("\\\"", "\"", $text);
        return $text;
    }

    // ritorna una lista ([mixed values])
    function makeList ($values="") {
        $output = "<ul>\n";
        if (is_array($values)) {
            while (list(,$value) = each($values)) {
                $output .= " <li>$value\n";
            }
        } else {
            $output .= $values;
        }
        $output .= "</ul>\n";
        return $output;
    }

    //	Restituisce $corpo
    //==========================================================================
    function neval($taghtml) {
        $valnum = "<<=!". ++$GLOBALS['temp_txt_num']."!=>>";
        $GLOBALS['temp_txt_patners'][] = $valnum;
        $GLOBALS['temp_txt_replace'][] = stripslashes(trim($taghtml));
        return $valnum;
    }

    //	Restituisce $colore
    //==========================================================================
    function setcolor($parola,$search) {
        $cpa = strtolower($parola);
        $key = array_search($cpa,$search);
        $key += 1;
        if ($key>5) $key=1;
        return "<span class=\"evitext$key\">$parola</span>";
    }

    //	Restituisce $corpo
    //==========================================================================
    function evitext($search,$corpo,$isAndword=true) {
        if (empty($search)) return $corpo;

        $GLOBALS['temp_txt_num'] = 0;
        $corpo = preg_replace("/(<\/?\w+[^>]*>)/ei","\$this->neval('\\1')",$corpo);

        if ($isAndword) {
            if (strpos($search," ")) {
                $search = explode(" ",$search);
            }
            if (!is_array($search)) $search = array($search);
            foreach ($search as $val) {
                $newsearch[] = strtolower(trim($val));
            }
            $valori = implode("|",$newsearch);
            $corpo = preg_replace("/($valori)/ei",'\$this->setcolor("\\1",$newsearch)',$corpo);
        } else {
            $corpo = preg_replace("/([^a-z0-9]($search)[^a-z0-9])/i","<span class=\"evitext2\">\\1</span>",$corpo);
        }


        $corpo = str_replace($GLOBALS['temp_txt_patners'],$GLOBALS['temp_txt_replace'],$corpo);
        unset($GLOBALS['temp_txt_patners'],$GLOBALS['temp_txt_replace'],$GLOBALS['temp_txt_num']);
        return $corpo;
    }

    //	Restituisce $num caratteri da testo
    //==========================================================================
    public static function sommario($testo, $num_char='255', $strip_tags=true, $pos=' '){
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