<?php

!defined('DS') ? define('DS',DIRECTORY_SEPARATOR) : "";

class Crawler{
    
    var $results;
    var $status;
    var $useragent = 'Opera/9.20 (Windows NT 5.1; U; en)';
    var $ip = '66.249.65.176';
    var $snoopy_cookies = array();
    var $curl_cookies = null;

    function snoopy_get_con($url, $post = array()){
        if(!$this->Snoopy){
            require_once(dirname(dirname(__FILE__)).'/class/class-snoopy.php');
            $this->Snoopy = & new Snoopy();
        }
        $this->Snoopy -> agent = $this->useragent();
        $this->Snoopy -> expandlinks = true;
        $this->Snoopy -> rawheaders["X_FORWARDED_FOR"] = $this->ip();
        $this->Snoopy -> rawheaders["Pragma"] = "no-cache";
        if(!empty($this->snoopy_cookies)){
            foreach($this->snoopy_cookies as $key=>$val){
                $this->Snoopy->cookies[$key] = $val;
            }
        }
        empty($post)
        ? $this->Snoopy->fetch($url)
        : $this->Snoopy->submit($url, $post);
        $this->results = $this->Snoopy -> results;
        $this->status = $this->Snoopy -> status;
        return $this->results;
    }

    function curl_get_con($url, $post = array()){
        $header = array('Cache-Control: no-cache');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($this->curl_cookies){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->curl_cookies);
        } 
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__file__).DS.'tmp/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__file__).DS.'tmp/cookies.txt');
        if(!empty($post)){
            curl_setopt($ch, CURLOPT_POST, $post);
        }
        $this->results = curl_exec($ch);
        $request = curl_getinfo($ch);
        $this->status = $request['http_code'];
        curl_close($ch);
        return $this->results;
    }

    function cfile_get_con($url){
        ini_set('useragent',$this->useragent);
        $content = @file_get_contents($url);
        return $content;
    }

    function results(){
        return $this->results;
    }

    function status(){
        return $this->status;
    }

    function useragent($useragent = null){
        $agent[]="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/4.0; GTB0.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; .NET CLR 1.1.4322)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Alexa Toolbar; mxie)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Maxthon)";
        $agent[]="Opera/9.20 (Windows NT 5.1; U; en)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
        $agent[]="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
	$agentxc = $agent[(round(mktime()/10)%10)];
        empty($useragent)
        ? $this->useragent = $agentxc
        : $this->useragent = $useragent;
        unset($agent);
        return $this->useragent;
    }

    function  ip($ip = null){
        for($i=0;$i<4;$i++)$ip[] = rand(1,244);
        empty($ip)
        ? $this->ip = implode('.',$ip)
        : $this->ip = $ip;
        return $this->ip;
    }
}
?>
