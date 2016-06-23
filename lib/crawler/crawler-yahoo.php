<?php
/**
 * @package Yahoo Crawler 1.1
 * @author Hay<xiaoguanhai@gmail.com>
 * @copyright 2010, http://www.tmper.com
 */
if(!class_exists('Crawler')){
    require_once(dirname(__file__).DS.'crawler.php');
}
class Yahoo extends Crawler{
    /**
     *
     * @var int The Results amaount.
     */
    var $stats   = null;
    var $htmlTXT = null;
    var $results = array();
    
    function __construct(){
        $this->htmlDOM = Import::htmlDOM();
    }

    function __destruct(){
        $this->htmlDOM->clear();
    }

    /**
     * @access private
     */
    function __flush(){
        $this->stats   = null;
        $this->htmlTXT = null;
        $this->results = array();
        $this->htmlDOM->clear();
    }

    /**
     * @access private
     */
    function __stats($content = null){
        $content
        ? $this->htmlDOM->load($content)
        : $this->htmlDOM->load($this->htmlTXT);
        $html = $this->htmlDOM->find('strong[id=resultCount]', 0);
        $this->stats = str_replace(array(',','.',' ','&nbsp;'),'',strip_tags($html));
        unset($html);
        $this->htmlDOM->clear();
        return $this->stats;
    }

    /**
     * @access private
     */
    function & __get_urls($content = null){
        $content
        ? $this->htmlDOM->load($content)
        : $this->htmlDOM->load($this->htmlTXT);
        $urls = array();
        $lis = $this->htmlDOM->find('div[id=web] div[class=res]');
        foreach($lis as $k=>$li){
            $h3 = $li->find('h3', 0);
            if(!is_object($h3))continue;
            $link = $h3->find('a', 0)->href;
//            $link = strstr($link, '/**');
//            $link = str_replace('/**', '', $link);
//            $link = urldecode($link);
            if(is_numeric(strpos($link,"/url?q="))){
                $link = str_replace("/url?q=","",$link);
            }
            if(is_numeric(strpos($link,"/interstitial?url="))){
                $link = str_replace("/interstitial?url=","",$link);
            }
            $link = eregi_replace('&amp;[a-zA-Z0-9_-]+=[a-zA-Z0-9_-]+', '', $link);

            $urlArr = @parse_url($link);
            if($link<> '' && isset($urlArr['host']) && !eregi('search.yahoo.com', $urlArr['host'])){
                if(is_numeric(strpos($link, "/search?hl=")) || is_numeric(strpos($link,"/search?p=")) || trim($link) == ''){
                    continue;
                }
                $plaintext = trim(strip_tags($li->find('div[class=sm-abs]', 0)));
                $urls[$k]['desc'] = $plaintext;
                $urls[$k]['link'] = $link;
                $urls[$k]['title'] = trim(strip_tags($h3));
            }
            unset($urlArr);
        }
        $this->htmlDOM->clear();
        unset($lis, $k, $li, $h3, $link, $plaintext, $texts);
        return $urls;
    }

    /**
     * Get the yahoo search results number.
     *
     * You only can use this function after search(string $uri).
     *
     * @access public
     * @return int The Yahoo search results number.
     */
    function stats(){
        return $this->stats;
    }

    /**
     *
     * Search Yahoo results.
     *
     * @param string $uri It can is a keyword, or a yahoo search url.
     * @return <Array> Search Results include site title, site desc and site link.
     * @access public
     */
    function & search($uri){
        $this->__flush();
        $urlArr = parse_url($uri);
        if(isset($urlArr['scheme']) && in_array($urlArr['scheme'], array('https', 'http')) && isset($urlArr['query']) && $urlArr['host']) {
            parse_str($urlArr['query'], $params);
            foreach($params as $k=>$val) {
                $this->$k = $val;
            }
            $url = $uri;
        }else {
            $url = "http://search.yahoo.com/search?p=$uri&fr=siteexplorer";
        }
        $this->htmlTXT = $this->curl_get_con($url);
        $checker = $this->moveChecker($this->htmlTXT);
        if($checker) {
            return $this->search($checker);
        }
        if(!$this->htmlTXT) {
            return array();
        }
        $this->stats   = $this->__stats();
        $this->results = $this->__get_urls();
        return $this->results;
    }

    /**
     *
     * @param <type> $htmlTXT
     * @return <type> 
     */
    function & str2rt($htmlTXT){
        $this->__flush();
        $this->htmlTXT = $htmlTXT;
        $checker = $this->moveChecker($this->htmlTXT);
        if($checker) {
            return $this->search($checker);
        }
        if(!$this->htmlTXT) {
            return array();
        }
        $this->stats   = $this->__stats();
        $this->results = $this->__get_urls();
        return $this->results;
    }

    /**
     * Check the link moved or not.
     * @param string $content the web page content.
     * @return boolean new url or faluse
     */
    function & moveChecker($content){
        if(eregi('The document has moved', $content)) {
            $this->htmlDOM->load($content);
            $output = $this->htmlDOM->find('a', 0)->href;
            $this->htmlDOM->clear();
        }else {
            $output = false;
        }
        return $output;
    }
}
?>
