<?php
//This file can be run as a cron job to track online status over time, then boil it down to a simple GUI rendered in HTML and CSS

require "PHPServerStatus.class.php";

class statistics extends PHPServerStatus{
    public function DoStats(){
        $online = 0;
        $offline = 0;

        $cache_files = glob($_SERVER['DOCUMENT_ROOT']."/phpss/cache/*.cache");

        foreach($cache_files as $file){
            $ping = PHPServerStatus::ReadData($file)[0];
            
            if($ping > 0){
                $online++;
            }else{
                $offline++;
            }
        }

        $result = -1;

        $percentage = ($online / ($online + $offline)) * 100;

        if($percentage <= 50){
            $result = 0;
        }elseif($percentage > 50 && $percentage < 90){
            $result = 1;
        }elseif($percentage >= 90){
            $result = 2;
        }
        
        $filename = date("d-m-Y_H-i-s").".stat";

        $title = date("d/m/Y")." at ".date("H:i:s")." GMT";

        PHPServerStatus::WriteData($title.",".$result, $_SERVER['DOCUMENT_ROOT']."/phpss/stats/".$filename);
    }
}

//clear cache first so we only count services we are actively tracking
array_map('unlink', glob($_SERVER['DOCUMENT_ROOT']."/phpss/cache/*.cache"));

$phpssobj = new PHPServerStatus();
$phpssobj -> GetStatus("gb", "local server", "localhost", "", 80, false, 300, true); //Bypass reading any cache files that may have been generated between clearing cache and now
$phpssobj -> GetStatus("fr", "external server",  "google.fr", "", 80, false, 300, true);

//Do stats
$statobj = new statistics;
$statobj -> DoStats();
?>