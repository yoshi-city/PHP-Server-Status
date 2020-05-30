<?php
class showstats{

    public function ShowStats(){

        $stat_files = glob($_SERVER['DOCUMENT_ROOT']."/phpss/stats/*.stat");

        //Last 96 statistics entries = 24 hours of checking every 15 minutes
        $stat_files = array_slice($stat_files, count($stat_files) - 96, count($stat_files));

        $html  = "<link rel='stylesheet' type='text/css' href='/phpss/css/tables.css' />
        <table class='status-history'><tr>";

        foreach($stat_files as $file){

            $contents = file_get_contents($file);
            $data = explode(",", $contents);

            if($data[1] == 0){
                $html .= "<td class='bad tooltip'><div class='tooltiptext'>".$data[0]."<br>Major Disruption Detected</div></td>";
            }elseif($data[1] == 1){
                $html .= "<td class='ok tooltip'><div class='tooltiptext'>".$data[0]."<br>Minor Disruption Detected</div></td>";
            }elseif($data[1] == 2){
                $html .= "<td class='good tooltip'><div class='tooltiptext'>".$data[0]."<br>No Disruption Detected</div></td>";
            }
        }

        $html .= "</tr></table>";

        return $html;
    }

}
?>