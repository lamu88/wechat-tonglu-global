<?php
/**
 * @author Hay<xiaoguanhai@gmail.com>
 * @link http://www.tmper.com/
 * @version 2.0
 * @copyright 2011
 */
if(!class_exists('Crawler')){
    require_once(dirname(__file__).DS.'crawler.php');
}
class Google extends Crawler{
    //var $htmlDOM = null;
    var $regional         = 'com';
    //var $htmlTXT = '';
    //var $stats = '';
    //var $prs = array();
    var $usleep           = 6000000;
    var $prusleep         = 700000;
    var $cache            = false;
    var $pr_cache_timeout = 604800;
    var $cache_timeout    = 432000;
    //var $results = array();
   // var $cachefile = null;
    //var $tmpDir = null;
    var $cookies          = false;
    
    function __construct($param = array()){
        if(!empty($param)){
            foreach($param as $var=>$value)$this->$var = $value;
        }
        $this->htmlDOM = & Import::htmlDOM();
        $this->Docpro = & Import::docpro();
    }
    
    function __destruct(){
        $this->htmlDOM->clear();
    }

    function init($zone, $lang = null, $lr = null){
        $this->regional = $zone;
        $this->lr = $lr;
        $this->hl = $lang;
        $this->htmlDOM = & Import::htmlDOM();
    }

    function zone($zone = null){
        if($zone){
            $this->regional = $zone;
        }
        return $this->regional;
    }

    function lr($lr = null){
        if($lr){
            $this->lr = $lr;
        }
        return $this->lr;
    }

    function hl($hl = null) {
        if($hl){
            $this->hl = $hl;
        }
        return $this->hl;
    }

    function lang($lang = null){
        $this->hl($lang);
    }

    function number($number = null){
        if($number){
            $this->num = $number;
        }
        return $this->num;
    }

    function __flush(){
        if(!$this->regional){
            die('"Regional" not exists');
        }
        if(!$this->hl){
            die('"hl" not exists');
        }
        $this->prs = array();
        $this->q = '';
        $this->htmlTXT = '';
        $this->urls = array();
        $this->htmlDOM->clear();
    }

    function tmpDir($dir){
        $this->tmpDir = $dir;
    }

    function & __cache($url, $indexed = 'GG'){
        if (defined('CACHE_PATH')) {
            return CACHE_PATH.DS.$indexed.DS.$this->regional.DS.substr(md5($url), 0, 2).DS.md5($url).'.html';
        }else if($this->tmpDir) {
            return $this->tmpDir.DS.$indexed.DS.$this->regional.DS.substr(md5($url), 0, 2).DS.md5($url).'.html';
        }else {
            return dirname(dirname(__FILE__)).DS.$indexed.DS.$this->regional.DS.substr(md5($url), 0, 2).DS.md5($url).'.html';
        }
    }

    function & __stats($html = null){
        $this->htmlDOM->load($this->htmlTXT);
        $stats = $this->htmlDOM->find('div[id=resultStats]', 0);
        $this->statsHTML = $stats;
        $stats = str_replace(array(',', '.', ' ', '&nbsp;'), '', strip_tags($stats));
        $stats = eregi_replace('\([^\)]+\)', '', $stats);
        $stats = eregi_replace('[^0-9]+', '', $stats);
        $this->htmlDOM->clear();
        return $stats;
    }

    /**
     * Get pr url.
     * @param <type> $domain
     * @return <type>
     */
    function & prurl($domain){
        $url  = "http://toolbarqueries.google.com/search?client=navclient-auto";
        $url .= "&ch=".$this->hash($this->hash_url($domain))."&ie=UTF-8&oe=UTF-8&features=Rank&q=info:";
        $url .= urlencode($domain);
        return $url;
    }

    /**
     * Change pr rank(Rank_x:x:x} to int.
     * @param <type> $con
     * @return <type>
     */
    function str2pr($con){
        ereg("Rank_[0-9]{1}:[0-9]{1}:([0-9]{1,2})", $con, $regs);
        isset($regs['1'])
        ? $pr_str = $regs['1']
        : $pr_str = '';
        $pr_str == ''
        ? $pr =  -1
        : $pr =  intval($pr_str);
        return $pr;
    }

    /**
     * Get get pr.
     * @param <type> $domain
     * @return <type>
     */
    function & get_pr($domain){
        $urlarr = parse_url($domain);
        isset($urlarr['host'])
        ? $this->host = $urlarr['host']
        : $this->host = null;
        unset($urlarr);

        $url  = "http://toolbarqueries.google.{$this->regional}/search?client=navclient-auto";
        $url .= "&ch=".$this->hash($this->hash_url($domain))."&ie=UTF-8&oe=UTF-8&features=Rank&q=info:";
        $url .= urlencode($domain);
        $cfn = & $this->__cache($url, 'PR');
        if(file_exists($cfn) && mktime()- filemtime($cfn) <= $this->pr_cache_timeout && $this->cache === true){
            $con = file_get_contents($cfn);
        }else{
            $this->prurl = $url;
            $con = $this->curl_get_con($url);
            if($this->ban($con)){
                echo "Google Ban!";
            }else{
                usleep($this->prusleep);
            }
        }
        ereg("Rank_[0-9]{1}:[0-9]{1}:([0-9]{1,2})", $con, $regs);
        isset($regs['1'])
        ? $pr_str = $regs['1']
        : $pr_str = '';
        $pr_str == ''
        ? $pr =  -1
        : $pr =  intval($pr_str);
        unset($url, $con, $regs, $pr_str);
        if($pr >= 0 && $this->cache === true && !$this->ban($con)){
            $this->Docpro->write($cfn, $con);
        }
        return $pr;
    }

    function & set_com_cookies(){
        $fn = dirname(__file__).DS.'/tmp/google_com_cookies.tmp';
        if(!file_exists($fn) || mktime()-filemtime($fn) >= 86400){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://www.google.com/ncr');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__file__).DS.'/tmp/google_com_cookies.tmp');
            curl_exec($ch);
            curl_getinfo($ch);
            curl_close($ch);
        }
        usleep($this->usleep);
        return dirname(__file__).DS.'/tmp/google_com_cookies.tmp';
    }
    /**
     * Get Google result from html content.
     * @param <type> $htmlText
     * @param <type> $options
     * @return <type>
     */
    function & str2rt($htmlText, $options = array('links')){
        $this->htmlTXT = $htmlText;
        $this->stats = & $this->__stats();
        $rt = $this->__get_urls();
        if(empty($options) || in_array('pr', $options)){
            foreach($rt as $k=>$row){
                $rt[$k]['pr'] = $this->get_pr($row['link']);
                $rt[$k]['pr_link'] = $this->prurl;
                if($rt[$k]['pr'] >= 0){
                    $this->prs[] = $rt[$k]['pr'];
                }
                $rt[$k]['host'] = $this->host;
                usleep(rand(700000, 800000));
            }
        }
        unset($url, $k, $row);
        if(!empty($rt) && $this->cache === true && !$this->ban()){
            $this->Docpro->write($cfn, $this->htmlTXT);
        }
        return $rt;
    }
    /**
     * Get Google result to xml from html content.
     * @param <type> $htmlText
     * @param <type> $options
     * @return string
     */
    function & str2xml($htmlText, $options = array('links')){
        $rt = $this->getrt($htmlText, $options);
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<root>\n";
        if(isset($rt) && !empty($rt)){
            foreach($rt as $k=>$row) {
                $xml .= "\t<item>\n";
                foreach($row as $k=>$val){
                    $xml .= "\t\t<{$k}><![CDATA[{$val}]]></{$k}>\n";
                }
                $xml .= "\t</item>\n";
            }
        }
        $xml .= "</root>\n";
        return $xml;
    }

    function & search2xml($uri, $options = array('links')) {
        $rt = $this->search($uri, $options);
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<root>\n";
        if(isset($rt) && !empty($rt)){
            foreach($rt as $k=>$row) {
                $xml .= "\t<item>\n";
                foreach($row as $k=>$val){
                    $xml .= "\t\t<{$k}><![CDATA[{$val}]]></{$k}>\n";
                }
                $xml .= "\t</item>\n";
            }
        }
        $xml .= "</root>\n";
        return $xml;
    }

    function & search($uri, $options = array('links')){
        $urlArr = parse_url($uri);
        if(isset($urlArr['scheme']) && in_array($urlArr['scheme'], array('https', 'http')) && isset($urlArr['query']) && $urlArr['host']) {
            parse_str($urlArr['query'], $params);
            foreach($params as $k=>$val) {
                $this->$k = $val;
            }
            $hostArr = explode('.', $urlArr['host']);
            $this->regional = $hostArr[count($hostArr)-1];
            $url = $uri;
        }else {
            $this->q = $uri;
            $url = "http://www.google.{$this->regional}/search?q=".$this->q;
            if($this->hl){
                $url .= "&hl={$this->hl}";
            }
            if($this->num){
                $url .= "&num={$this->num}";
            }
            if($this->lr){
                $url .= "&lr={$this->lr}";
            }
            if($this->gl){
                $url .= "&gl={$this->gl}";
            }

        }
        //
        $this->__flush();
        //
        $cfn = & $this->__cache($url, 'GG');
        if(file_exists($cfn) && mktime()- filemtime($cfn) <= $this->cache_timeout && $this->cache === true){
            $this->htmlTXT = file_get_contents($cfn);
        }else{
            if($this->cookies === true) {
                $this->curl_cookies = $this->set_com_cookies();
            }
            $this->htmlTXT = $this->curl_get_con($url);//$this->snoopy_get_con($url);
            if(eregi('<h1>302 Moved</h1>', $this->htmlTXT)){
                usleep($this->usleep);
                $this->htmlTXT = $this->cfile_get_con($url);//$this->snoopy_get_con($url);
            }
            if($this->ban()){
                return false;
            }
            usleep($this->usleep);
        }
        $this->stats = & $this->__stats();
        $rt = $this->__get_urls();
        if(empty($options) || in_array('pr', $options)){
            foreach($rt as $k=>$row){
                $rt[$k]['pr'] = $this->get_pr($row['link']);
                $rt[$k]['pr_link'] = $this->prurl;
                if($rt[$k]['pr'] >= 0){
                    $this->prs[] = $rt[$k]['pr'];
                }
                $rt[$k]['host'] = $this->host;
                usleep(rand(700000, 800000));
            }
        }
        unset($url, $k, $row);
        if(!empty($rt) && $this->cache === true && !$this->ban()){
            $this->Docpro->write($cfn, $this->htmlTXT);
        }
        return $rt;
    }

    function & __get_urls(){
        $this->htmlDOM->load($this->htmlTXT);
        $urls = array();
        foreach($this->htmlDOM->find('div[id=res] li[class=g]') as $k=>$li){
            $h3 = $li->find('h3[class=r]', 0);
            if(!is_object($h3))continue;
            $link = $h3->find('a', 0)->href;
            if(is_numeric(strpos($link,"/url?q="))){
                $link = str_replace("/url?q=","",$link);
            }
            if(is_numeric(strpos($link,"/interstitial?url="))){
                $link = str_replace("/interstitial?url=","",$link);
            }
            $link = eregi_replace('&amp;[a-zA-Z0-9_-]+=[a-zA-Z0-9_-]+', '', $link);
            //
            $urlArr = @parse_url($link);
            if($link<> '' && !eregi('http:\/\/[^.]+.google', $link) && isset($urlArr['host'])){
                if(is_numeric(strpos($link, "/search?hl=")) || is_numeric(strpos($link,"/search?q=")) || trim($link) == ''){
                    continue;
                }
                $plaintext = trim(strip_tags($li->find('div[class=s]', 0), '<cite>'));
                $texts = explode('<cite>', $plaintext);
                $plaintext = $texts['0'];
                $urls[$k]['desc'] = $plaintext;
                $urls[$k]['link'] = $link;
                $urls[$k]['title'] = trim(strip_tags($h3));
                //host
                $urlarr = parse_url($link);
                isset($urlarr['host'])
                ? $urls[$k]['host'] = $urlarr['host']
                : $urls[$k]['host'] = null;
            }
        }
        $this->htmlDOM->clear();
        unset($k, $li, $h3, $link, $plaintext, $texts);
        return $urls;
    }

    function & products(){
        $this->__flush();
        $url = "http://www.google.{$this->regional}/products";
        $this->htmlTXT = $this->curl_get_con($url);
        $this->htmlDOM->load($this->htmlTXT);
        foreach($this->htmlDOM->find('table[id=sample_q] td[width=20%]') as $k=>$val){
            $val = strip_tags((string)$val);
            $val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
            $rt[] = trim($val);
        }
        unset($url, $k, $val);
        return $rt;
    }

    function search_category($keyword){
        $url = "http://www.google.com.hk/search?hl=zh-TW&q=".urlencode($keyword)."&cat=gwd/Top";
        $this->htmlTXT = $this->snoopy_get_con($url);
        $a = (string)$this->htmlDOM->find('div[id=res] div[class=g] table', 0)->find('a', 0);
        return strip_tags($a);
    }

    //http://wenda.tianya.cn
    function tianya_search($keyword){
        $url = 'http://wenda.tianya.cn/wenda/search?q='.urlencode($keyword).'&tab=wtmtoc&sort=wsmor';
        return $url;
        //
        $this->htmlDOM->load($con);
        $table_0 = $this->htmlDOM->find('div[id=res] div[class=g] table', 0);
        if(!is_object($table_0))return null;
        $a = $table_0->find('a', 0);
        return strip_tags($a);
    }

    function tianya_search_results($con){
        $this->htmlDOM->load($con);
        $data = array();
        foreach($this->htmlDOM->find('div[class=wspsrCSS]') as $k=>$wspsrtCSS){
            $data[$k]['url'] = $wspsrtCSS->find('div[class=wspsrtCSS] a', 0)->href;
            if(!eregi('http://',$data[$k]['url']))$data[$k]['url'] = 'http://wenda.tianya.cn'.$data[$k]['url'];
            $data[$k]['title'] = trim(eregi_replace('[ ]+', ' ', strip_tags($wspsrtCSS->find('div[class=wspsrtCSS] a', 0))));
            $data[$k]['category'] = trim(eregi_replace('[ ]+', ' ', str_replace(array('[',']'), '', trim(strip_tags($wspsrtCSS->find('div[class=wspsrtCSS] a', 1))))));
            $data[$k]['desc'] = trim(eregi_replace('[ ]+', ' ', strip_tags($wspsrtCSS->find('div', 1))));
        }
        return $data;
    }

    function & get_tianya_thread($con){
        $this->__flush();
        $this->htmlDOM->load($con);
        foreach($this->htmlDOM->find('div[class=wpcppmcCSS]') as $k=>$item){
            if($k == 0)$this->results[$k]['type'] = 'question';
            else if($k == 1)$this->results[$k]['type'] = 'best_answer';
            else $this->results[$k]['type'] = 'answer';
            $this->results[$k]['content'] = '';
            $this->tmp = $item->find('div[class=wpcpdCSS]', 0)->innertext;
            $this->results[$k]['content'] .= Basic::html2br($this->tmp);
            $this->tmp = $item->find('div[class=wpcpchrCSS] span[class=wpcpaCSS]', 0)->innertext;
            $this->results[$k]['user'] = trim(strip_tags($this->tmp));
        }
        unset($item, $k);
        return $this->results;
    }

    function tianya_ban($con){
        return false;
    }

    function translation($con, $from, $to){
        $request['url'] = 'http://ajax.googleapis.com/ajax/services/language/translate';
        $request['params'] = array(
            'v'=>'1.0',
            'q'=>$con,
            'langpair'=>$from.'|'.$to
            );
        return $request;
    }
    
    /**
     *
     * @param <type> $Hashnum
     * @return <type>
     * 
     */
    function hash($Hashnum){
        $CheckByte = 0;
        $Flag = 0;
        $HashStr = sprintf('%u', $Hashnum) ;
        $length = strlen($HashStr);
        for ($i = $length - 1;$i >= 0;$i --){
            $Re = $HashStr{$i};
            if (1 == ($Flag % 2)) {
                $Re += $Re;
                $Re = (int)($Re / 10) + ($Re % 10);
            }
            $CheckByte += $Re;
            $Flag ++;
        }
        $CheckByte %= 10;
        if (0 !== $CheckByte) {
            $CheckByte = 10 - $CheckByte;
            if (1 === ($Flag%2) ) {
                if (1 === ($CheckByte % 2)) {
                    $CheckByte += 9;
                }
                $CheckByte >>= 1;
            }
        }
        unset($Flag, $length, $i, $Re);
        return '7'.$CheckByte.$HashStr;
    }

    function hash_url($String){
        $Check1 = $this -> str2num($String, 0x1505, 0x21);
        $Check2 = $this -> str2num($String, 0, 0x1003F);
        $Check1 >>= 2;
        $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
        $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
        $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
        $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
        $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
        unset($Check1, $Check2);
        return ($T1 | $T2);
    }

    function str2num($Str, $Check, $Magic){
        $Int32Unit = 4294967296;  // 2^32
        $length = strlen($Str);
        for ($i = 0; $i < $length; $i++) {
            $Check *= $Magic;
            //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
            //  the result of converting to integer is undefined
            if ($Check >= $Int32Unit) {
                $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
                // - 2^31
                $Check = ($Check < -2147483647) ? ($Check + $Int32Unit) : $Check;
            }
            $Check += ord($Str{$i});
        }
        unset($Int32Unit, $length, $i);
        return $Check;
    }

    //Check Google Ban
    function ban($con = null){
        if(empty($con)){
            $con = $this->htmlTXT;
        }
        if(eregi("<h1>We're sorry...</h1>", $con)){
            return true;
        }else{
            return false;
        }
    }
}
?>
