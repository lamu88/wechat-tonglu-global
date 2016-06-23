<?php
if(!class_exists('Crawler')){
    require_once(dirname(__file__).DS.'crawler.php');
}
class Alexa extends Crawler{
    var $stats = null;
    var $htmlTXT = null;

    function __construct(){
        $this->htmlDOM = Import::htmlDOM();
    }

    function __destruct(){
        $this->htmlDOM->clear();
    }

    function __flush(){
        $this->stats = null;
        $this->htmlTXT = null;
        $this->htmlDOM->clear();
    }

    function & search($url){
        $this->__flush();
        $alx_url = "http://www.alexa.com/siteinfo/$url";
        $this->htmlTXT = $this->curl_get_con($alx_url);
        $alex_data = array('0','0','0','0','0');
        $this->htmlDOM->load($this->htmlTXT);
        $this->statsHTML = $this->htmlDOM->find('table[id=siteStats]', 0);
        foreach($this->htmlDOM->find('table[id=siteStats] td') as $k=>$obj){
            $html = $obj->find('div', 0);
            $alex_data[$k] = intval(str_replace(array(',','.','&nbsp;',' ','No','data') ,'' , strip_tags($html)));
        }
        $rt['alexa_traffic_rank'] = $alex_data['1'];
        $rt['alexa_sites_linking_in'] = $alex_data['2'];
        unset($html, $tmp, $alex_data, $alx_url, $k, $obj);
        $this->htmlDOM->clear();
        return $rt;
    }
}
?>