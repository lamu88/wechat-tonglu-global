<?php
if(!class_exists('Crawler')){
    require_once(dirname(__file__).DS.'crawler.php');
}
class Baidu extends Crawler{
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
    
    function __stats(){
        $this->htmlDOM->load($this->htmlTXT);
        $this->stats = $this->htmlDOM->find('div[id=tool]', 0);
        $this->stats = str_replace(array(',','.',' ','&nbsp;'),'',strip_tags($this->stats));
        $this->stats = eregi_replace('[^0-9]0[0-9]+', '', $this->stats);
        $this->stats = eregi_replace('[^0-9]+', '', $this->stats);
        $this->htmlDOM->clear();
        return $this->stats;
    }

    function & search($keyword){
        $this->__flush();
        $url = "http://www.baidu.com/s?wd=$keyword";
        $this->htmlTXT = $this->snoopy_get_con($url);
        $this->__stats();
    }
}
?>
