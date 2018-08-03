<?php
// ver. 2.1 del 26.09.09 luigi

namespace App\Services;

class sl_datatime {

    //========================================================================
    function formatDT($time, $format="news") {
        if (strlen($time)==10) $time .= " 00:00:00";
        preg_match ("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $time, $datetime);
        switch ($format) {
            case "d-m-Y"	: $datetime = $datetime[3]."-".$datetime[2]."-".$datetime[1]; break;
            case "H:i d-m-Y"	: $datetime = "ore ".$datetime[4].":".$datetime[5] ." del " .$datetime[3]."-".$datetime[2]."-".$datetime[1]; break;
            case "Ymd"		: $datetime = $datetime[1].$datetime[2].$datetime[3]; break;
            case "list"		: $datetime['m'] = $datetime[2];
                $datetime['d'] = $datetime[3];
                $datetime['Y'] = $datetime[1]; break;
            case "y"		: $datetime = substr($datetime[1],2,3); break;
            case "H:i:s"	: $datetime = $datetime[4].":".$datetime[5].":".$datetime[6]; break;
            case "H:i"		: $datetime = $datetime[4].":".$datetime[5]; break;
            case "H"		: $datetime = $datetime[4]; break;
            case "i"		: $datetime = $datetime[5]; break;
            case "s"		: $datetime = $datetime[6]; break;
            case "news"		:
                $giornoset = date("l", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
                $mese = date("F", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
                $time_hour = $datetime[4];
                $datetime = SL_datatime::traslate($giornoset).", $datetime[3] ". SL_datatime::traslate($mese) ." $datetime[1] @ $time_hour:$datetime[5]";break;
            case "newsnotime"	:
                $giornoset = date("l", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
                $mese = date("F", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
                $datetime = SL_datatime::traslate($giornoset).", $datetime[3] ". SL_datatime::traslate($mese) ." $datetime[1]";
                break;
            case "rfc822toDT"	:
                $date = date_parse("$time");
                $datetime = SL_datatime::mkdate($date['day'],$date['month'],$date['year'],$date['hour'],$date['minute'],$date['second']);
                break;
            case "H:i d-m-Y":
                $datetime = $datetime[4].":".$datetime[5]." ".$datetime[3]."-".$datetime[2]."-".$datetime[1];
                break;
        }
        return($datetime);
    } //END FUNC

    //========================================================================
    function yearArray($start=NULL, $length=10){
        if (!$start)
            $start = date("Y", mktime());
        for ($i=$start; $i<=$start+$length; $i++){
            $year[$i] = date("Y", mktime(0,0,0,1,1,$i));
        }
        return $year;
    } //END FUNC

    //========================================================================
    function dayArray(){
        for ($i=1; $i<32; $i++) {
            $day[$i] = date("d", mktime(0,0,0,1,$i));
        }
        return $day;
    } //END FUNC

    //========================================================================
    function monthArray($testo=0){
        for ($i=1; $i<13; $i++){
            if (!$testo)
                $month[$i] = date("m",mktime(2,0,0,$i,1,2000));
            else
                $month[$i] = SL_datatime::traslate(date("F",mktime(0,0,0,$i,1,2000)));
        }
        return $month;
    } //END FUNC

    // per DB
    //========================================================================
    function mkdate($giorno,$mese,$anno,$ora=0,$min=0,$sec=0,$stamptime=true){
        $format = "Y-m-d H:i:s";
        if ($stamptime==false) $format = "Y-m-d";
        $data = date("$format",mktime($ora,$min,$sec,$mese,$giorno,$anno));
        return $data;
    } //END FUNC

    //Restituisce il testo tradotto in italiano dei gg. della settimana e dei mesi
    //========================================================================
    function traslate($parola){
        switch ($parola) {
            case "Sunday": 		$tmp = "Dom"; 		break;
            case "Monday": 		$tmp = "Lun"; 		break;
            case "Tuesday": 	$tmp = "Mar"; 		break;
            case "Wednesday": 	$tmp = "Mer"; 		break;
            case "Thursday": 	$tmp = "Gio"; 		break;
            case "Friday": 		$tmp = "Ven"; 		break;
            case "Saturday": 	$tmp = "Sab"; 		break;
            case "January": 	$tmp = "Gennaio"; 	break; 		//Jan
            case "February": 	$tmp = "Febbraio"; 	break; 		//Feb
            case "March": 		$tmp = "Marzo"; 	break;		//Mar
            case "April": 		$tmp = "Aprile"; 	break;		//Apr
            case "May": 		$tmp = "Maggio"; 	break;		//May
            case "June": 		$tmp = "Giugno"; 	break;		//Jun
            case "July": 		$tmp = "Luglio"; 	break;		//Jul
            case "August": 		$tmp = "Agosto"; 	break;		//Aug
            case "September": 	$tmp = "Settembre"; break;		//Sep
            case "October": 	$tmp = "Ottobre"; 	break;		//Oct
            case "November": 	$tmp = "Novembre"; 	break;		//Nov
            case "December": 	$tmp = "Dicembre"; 	break;		//Dec
            default: 			$tmp = "";
        }
        return $tmp;
    } //END FUNC

    // da formato GG-MM-YYYY -> YYYY-MM-GG for DB
    //========================================================================
    function formatdb($time, $format="date") {
        // in ingresso accetta il formato italiano GG-MM-YYYY
        //if (strlen($time)==10) $time .= " 00:00:00";
        //([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})
        preg_match ("/([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})/", $time, $datetime);
        switch ($format) {
            case "date"	: $datetime = $datetime[3]."-".$datetime[2]."-".$datetime[1]; break;
            case "datetime"	: $datetime = $datetime[3]."-".$datetime[2]."-".$datetime[1]." ".$datetime[4].":".$datetime[5].":".$datetime[6]; break;
        }
        return($datetime);
    } //END FUNC


    // da formato time db a
    //========================================================================
    function fTime($time,$format="H:i") {
        // in ingresso accetta il formato time db
        preg_match ("/([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}/", $time, $newtime);
        switch ($format) {
            case "H:i"		: $newtime = $newtime[1].":".$newtime[2]; break;
        }
        return($newtime);
    } //END FUNC

    // Effettua la differenza (in gg.) fra due date.
    // 86400 = numero di giorni ovvero (60 sec * 60 min * 24 h)
    //========================================================================
    function dayto($start, $to){
        $day_start 	= strtotime(date("d/m/y",strtotime($start)));
        $day_to 	= strtotime(date("d/m/y",strtotime($to)));
        $intervallo = floor(($day_to-$day_start)/86400);
        return $intervallo;
    } //END FUNC

} // END CLASS