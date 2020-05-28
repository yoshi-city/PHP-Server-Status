<?php
class PHPServerStatus{

    private $flag;
    private $name;
    private $domain;
    private $ip;
    private $port;
    private $show_port;
    private $interval;

    private $cache_location;
    
    function __construct($flag, $name, $domain, $ip, $port, $show_port, $interval){
        //Init global properties
        $this -> flag       = $flag;
        $this -> name       = $name;
        $this -> domain     = $domain;
        $this -> ip         = $ip;
        $this -> port       = $port;
        $this -> show_port  = $show_port;
        $this -> interval   = $interval;
    }

    private function InitCache(){
        $cache_name = md5(strip_tags($this->name.$this->domain.$this->ip.$this->port));
        $this -> cache_location = $_SERVER['DOCUMENT_ROOT']."/phpss/cache/".$cache_name.".cache";
    }
    
    private function ReadCache(){
        $cache_data = file_get_contents($this -> cache_location);
        $cache_data = explode(",", $cache_data);
        return $cache_data;
    }

    private function WriteCache($data){
        $cache_file = fopen($this->cache_location, 'w');
        fwrite($cache_file, $data);
        fclose($cache_file);
    }

    private function CheckCache(){
        $result = false;

        if(file_exists($this->cache_location) == true){
            if(time() < $this -> ReadCache()[1]){
                $result = true;
            }
        }

        return $result;
    }

    private function PerformTest(){
        if(!$this->ip){
            $this->ip = gethostbyname($this->domain);
        }

        $start_time = null;
        $start_time = microtime(TRUE);

        if(fsockopen($this->ip, $this->port, $errno, $errstr, 12)){
            $status = 1;
            $ping = ((round(microtime(TRUE) - $start_time, 5)) * 1000);    
        }else{
            $ping = -1;
        }

        $expiry = time() + $this->interval;
        $savedata = $ping.",".$expiry;
        $this -> WriteCache($savedata);

        return $ping;
    }

    private function BuildHTML($ping, $timechecked){
        //Build HTML output based on information gathered

        //Country flag and service name
        $html["flag"] = "<img class='flag' src='/phpss/img/flags/".strtolower($this->flag).".png' alt='".$this->flag." flag' title='".strtoupper($this->flag)."'>";
        $html["name"] = $this->name;

        //If service is unreachable
        if($ping < 0){
            $html["status"] = "<span class='offline'>OFFLINE</span>";
            $html["ping"] = "<img src='/phpss/img/Alert.png' alt='!' title='Server is not responding'>"; //Display a nice icon since there is no ping time
        }else{
            $html["status"] = "<span class='online'>ONLINE</span>";
            $html["ping"] = $ping." ms";
        }   

        //If no domain provided, fall back to IP
        if(!$this->domain){
            $html["address"] = $this->ip;
        }else{
            $html["address"] = $this->domain;
        }

        //Should the port be shown?
        if($this->show_port == true){
            $html["port"] = ":".$this->port;
        }else{
            $html["port"] = "";
        }

        //Convert unix time to human readable format
        require_once("timeago.class.php");
        $html["timeago"] = timeago("@$timechecked");


        //Remove whatever you want to customise output
        return "
            <tr>
            <td class='text-center'>".$html['flag']."</td>
            <td class='text-left'>".$html['name']."</td>
            <td class='text-center'>".$html['address'].$html['port']."</td>
            <td class='text-center'>".$html['status']."</td>
            <td class='text-center'>".$html['ping']."</td>	
            <td class='text-right'>".$html['timeago']."</td>
            </tr>
        ";  
    }

    public function GetStatus(){
        $this -> InitCache();

        if($this -> CheckCache() == true){
            //Use the cache data
            $cache_data     = $this -> ReadCache();
            $ping           = $cache_data[0];
            $timechecked    = $cache_data[1] - $this->interval;
       }else{
            //Do a new test
            $ping = $this -> PerformTest();
            $timechecked = time();
        }

        return $this -> BuildHTML($ping, $timechecked);
    }
}
?>
