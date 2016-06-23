<?php
/**
 * @name View
 * @version 1.6
 * @author ajin<cosplaytoy@google.com>
 * @since 2011-1-3
 * @package CosplayFU 1.01 rec
 */
class View{
    /**
     *
     * @var string
     */
    var $__viewdir = null;

    /**
     *
     * @var string
     */
    var $lastFunc  = null;

    /**
     *
     * @var string
     */
    var $lastSort  = null;
	/*
	* @var array or string
	*/ 
	var $viewcss = array();
	
    function  __construct() {
         $this->__siteurl = Basic::siteurl();
    }

    function  __destruct() {
        
    }

    function  __call($name, $arguments) {
        if (in_array($name, array('gdata', 'data', 'validate', 'invalid', 'GET', 'POST'))) {
            return call_user_func_array(array($this->Controller->App, $name), $arguments);
        }else if (in_array($name, array('module', 'func', 'parms', 'args', 'lang', 'url', 'action', 'sqlErr'))) {
            return call_user_func_array(array($this->Controller, $name), $arguments);
        }
    }

    /**
     *
     * @return array 返回数据库错误信息
     * @access private
     * 
     */
    function sqlErr(){
        return $this->Controller->App->getErr();
    }

    /**
     * 用于在Controller中加载View文件
     * @param string $name 视图文件名称<不包含扩展名>
     *
     */
    function load($name){
        //$this->__viewdir = APP_PATH.'/'.$this->Controller->__module.'/template';
		$dir = APP_PATH.'/'.$this->Controller->__module.'/template';
		if(is_dir($dir)){
			$this->__viewdir = $dir;
		}else{
			$this->__viewdir = dirname(APP_PATH).DS.'tpl';
		}
        $this->__viewname = $name;
        $layout = APP_PATH.DS.$this->Controller->__module.DS.'layout'.DS.$this->Controller->__layout.$this->Controller->__viewext;
        if (!file_exists($layout)) {
            defined('LAYOUT_PATH')
            ? $layout = LAYOUT_PATH.DS.$this->Controller->__layout.$this->Controller->__viewext
            : $layout = dirname(APP_PATH).'/layout/'.$this->Controller->__layout.$this->Controller->__viewext;
        } 
        if (file_exists($layout)) {
            extract($this->Controller->__varname);
            include($layout);
            if($this->Controller->__alert){
                echo '<script type="text/javascript">
                    alert("'.str_replace(array("\r\n", "\n","\r"), '', $this->Controller->__alert).'");
                        </script>';
            }
        }else{
            if (!headers_sent()) {
                header("HTTP/1.0 404 Not Found");
            }
            die('HTTP/1.0 404: MISS "../layout/'.$this->Controller->__layout.$this->Controller->__viewext.'"');
            exit;
        }
    }

    /**
     * HTML meta tag
     * @access public
     */
    function meta(){
        foreach($this->Controller->__viewmeta as $name=>$content){
            echo '<meta name="'.$name.'" content="'.$content.'" />'."\n";
        }
    }

    function title(){
        echo $this->Controller->__title;
    }

    function element($name, $vars = array()){ 
        extract($vars);
        $element = APP_PATH.DS.$this->Controller->__module.DS.'element'.DS.$name.$this->Controller->__viewext;
        if (file_exists($element)) {
            include($element);
        }else {
			$element = dirname(APP_PATH).DS.'tpl'.DS.$name.$this->Controller->__viewext;
			if(file_exists($element)){
				include($element);
			}else{
				$element = dirname(APP_PATH).DS.'element'.DS.$name.$this->Controller->__viewext;
				if (file_exists($element)) {
					include($element);
				}
			}
        }
    }
	
	function css($name = null){
        if(is_string($name)){
			 $fn = dirname(APP_PATH).DS.'tpl'.DS.$name;
			 if(file_exists($fn)){
			 	$this->viewcss[$name] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."tpl/{$name}".'" media="all" />';
			 }else{
            	$this->viewcss[$name] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."css/{$name}".'" media="all" />';
			 }
        }
        if(is_array($name)){
            foreach($name as $var){
				$fn = dirname(APP_PATH).DS.'tpl'.DS.$var;
			 	if(file_exists($fn)){
					$this->viewcss[$var] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."tpl/{$var}".'" media="all" />';
				}else{
                	$this->viewcss[$var] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."css/{$var}".'" media="all" />';
				}
            }
        }
		
		//加入临时设置的文件
		$cssarr = $this->Controller->__viewcss;
		if(!empty($cssarr)){
			foreach($cssarr as $var){
				$fn = dirname(APP_PATH).DS.'tpl'.DS.$var;
				if(file_exists($fn)){
					$this->viewcss[$var] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."tpl/{$var}".'" media="all" />';
				}else{
                	$this->viewcss[$var] = '<link type="text/css" rel="stylesheet" href="'.$this->__siteurl."css/{$var}".'" media="all" />';
				}
            }
		}
		
        return "\r\n".implode("\r\n", $this->viewcss)."\t\n";
    }
	
    function img($name = null){
		if(isset($this->__imgurl) && $this->__imgurl){
            return $this->__imgurl."{$name}";
        }
        $fn = APP_PATH.DS.$this->Controller->__module.DS.'images'.DS.$name; //模块里面的独立图片
        if(file_exists($fn)){
            return SITE_URL.(SYS_PATH == dirname(APP_PATH) ? '' : basename(dirname(APP_PATH)).'/').basename(APP_PATH).'/'.$this->Controller->__module."/images/{$name}";
        }else{
			$fn = dirname(APP_PATH).DS.'tpl'.DS.$name;
			if( file_exists( $fn ) ){
				$imgurl = $this->__siteurl."tpl/{$name}";
			}else{
				$imgurl = $this->__siteurl."images/{$name}";
				if( !file_exists( THIS_PATH."/images/{$name}" ) ){
					 $imgurl = SITE_URL.basename(dirname(APP_PATH))."/images/{$name}";
				}
			}
			return $imgurl;
        }
    }

     function js($name = null){ 
        if(is_string($name)){
			$fn = dirname(APP_PATH).DS.'tpl'.DS.$name;
			$fn2 = dirname(APP_PATH).DS.$name;
			if(file_exists($fn)&&is_file($fn)){
				$this->viewjs[$name] = '<script type="text/javascript" src="'.$this->__siteurl.'tpl/'.$name.'"></script> ';
			}elseif(file_exists($fn2)&&is_file($fn2)){
					$this->viewjs[$name] = '<script type="text/javascript" src="'.$this->__siteurl."{$name}".'"></script> ';
			}else{
					$this->viewjs[$name] = '<script type="text/javascript" src="'.$this->__siteurl."js/{$name}".'"></script> ';
			}
        }
        if(is_array($name)){
            foreach($name as $var){
				$fn = dirname(APP_PATH).DS.'tpl'.DS.$var;
				$fn2 = dirname(APP_PATH).DS.$var;
				if(file_exists($fn)&&is_file($fn)){
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl.'tpl/'.$var.'"></script> ';
				}elseif(file_exists($fn2)&&is_file($fn2)){
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl."{$var}".'"></script> ';
				}else{
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl."js/{$var}".'"></script> ';
				}
            }
        }
		
		//加入临时设置的JSW文件
		$jsarr = $this->Controller->__viewjs;
		if(!empty($jsarr)){
			foreach($jsarr as $var){ 
				$fn = dirname(APP_PATH).DS.'tpl'.DS.$var;
				$fn2 = dirname(APP_PATH).DS.$var;
				if(file_exists($fn)&&is_file($fn)){
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl.'tpl/'.$var.'"></script> ';
				}elseif(file_exists($fn2)&&is_file($fn2)){
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl."{$var}".'"></script> ';
				}else{
					$this->viewjs[$var] = '<script type="text/javascript" src="'.$this->__siteurl."js/{$var}".'"></script> ';
				}
            }
		}
        return implode("\r\n", $this->viewjs)."\t\n";
     }

    function content(){
        extract($this->Controller->__varname);
        $fn = $this->__viewdir.DS.$this->__viewname.$this->Controller->__viewext;
        if (file_exists($fn)) {
            include($fn);
        }
    }

    function sort($field, $mothods = array(), $_get = array()){
        $this->lastFunc = __FUNCTION__;
        $this->lastSort = $field;
        //
        if (empty($mothods)) {
            $mothods = $this->args();
        }
        if (empty($_get)) {
            $_get = $_GET;
        }
        if(isset($_get['desc'])){
            if( trim($_get['desc']) == $field){
                $this->__sort[$field] = 'desc';
            }
            unset($_get['desc']);
            $_get['asc'] = $field;
        }else{
            if(isset($_get['asc']) && trim($_get['asc']) == $field){
                $this->__sort[$field] = 'asc';
            }
            unset($_get['asc']);
            $_get['desc'] = $field;
        }
        return $this->url($mothods, $_get);
    }
    
    function orderby($field){
        if(isset($this->__sort[$field])) {
            return $this->__sort[$field];
        }else {
            return 'desc';
        }
    }

    function & vardata($varname){
        if(isset($this->Controller->$varname)){
            return $this->Controller->$varname;
        }else if(isset($this->Controller->__varname[$varname])) {
            return $this->Controller->__varname[$varname];
        }else return null;
    }

    /**
     * Html code
     */

    function simple_html_pageurl($pageurl = null){
        if(empty($pageurl)){
            return null;
        }
        $html = '<p class="pageurl">';
        $html .= '<span class="total">Page:'.$pageurl['__thispage__'].'/'.$pageurl['__totalpage__'].'<span>&nbsp;&nbsp;<a rel="nofollow noindex" href="'.$pageurl['__first__'].'">First</a>';
        $html .= '<a rel="nofollow noindex" href="'.$pageurl['__previous__'].'">Prev</a>';
        foreach($pageurl['__number__'] as $k=>$row){
            $html .= '<a rel="nofollow noindex" href="'.$row['href'].'" class="'.$row['class'].'">'.$k.'</a>';
        }
        $html .= '<a rel="nofollow noindex" href="'.$pageurl['__next__'].'">Next</a>';
        $html .= '<a rel="nofollow noindex" href="'.$pageurl['__last__'].'">Last</a>';
        $html .= '</p>';
        return $html;
    }

    function DB_debug(){ 
        echo '<pre>';
        $rt = $this->Controller->App->queries();
		print_r($rt);
        echo '</pre>';
    }

    
    function a($href, $attr = null, $con = null, $_get = array()){
        $orderby = null;
        if($this->lastFunc == 'sort'){
            if($this->GET('asc') && $this->GET('asc') == $this->lastSort && $this->orderby($this->lastSort) == 'asc') {
                $orderby = '/\\';
            }else if($this->GET('desc') && $this->GET('desc') == $this->lastSort && $this->orderby($this->lastSort) == 'desc') {
                $orderby = '\/';
            }
        }
        $this->lastFunc = __FUNCTION__;
        //
        $html = '<a href="'.$this->url($href, $_get).'" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        $html .= '>'.$con.$orderby.'</a>';
        return $html;
    }

    function image($name, $attr = null){
        $html = '<img src="'.(is_array($name) ? $this->url($name) : ((eregi('http://', $name) || eregi('https://', $name)) ? $name : $this->img($name))).'" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        $html .= ' />';
        return $html;
    }

    function script($name = null){
        return '<script type="text/javascript" src="'.$this->js($name).'"></script>';
    }

    function style($name){
        return '<link type="text/css" rel="stylesheet" href="'.$this->css($name).'" />';
    }

    function input($type, $name, $attr = null, $value = null){
        $html = '<input type="'.$type.'" name="'.$name.'" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        if ($value == '') {
            $value = $this->POST($name) ? $this->POST($name) : $this->GET($name);
        }
        $html .= 'value="'.$value.'" />';
        return $html;
    }

    function hidden($name, $attr = null, $value = null){
        return $this->input('hidden', $name, $attr, $value);
    }

    function text($name, $attr = null, $value = null){
        return $this->input('text', $name, $attr, $value);
    }

    function password($name, $attr = null, $value = null){
        return $this->input('password', $name, $attr, $value);
    }

    function file($name, $attr = null){
        return $this->input('file', $name, $attr, null);
    }

    function button($name = 'button', $attr = null, $value = 'Button'){
        return $this->input('button', $name, $attr, $value);
    }

    function submit($name = 'submit', $attr = null, $value = 'Submit'){
        return $this->input('submit', $name, $attr, $value);
    }

    function radio($name, $attr = null, $value = '', $val = ''){
        $html = '<input type="radio" name="'.$name.'" ';
        if ($val == '') {
            $val = $this->POST($name) ? $this->POST($name) : $this->GET($name);
        }
        if ($value == $val) {
            $attr['checked'] = 'checked';
        }
        if (!empty($attr)) {
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        $html .= 'value="'.$value.'" />';
        return $html;
    }

    function checkbox($name, $attr = null, $value = null){
        if (empty($value)) {
            $value = $this->POST($name) ? $this->POST($name) : $this->GET($name);
        }
        if ($value == $val) {
            $attr['checked'] = 'checked';
        }
        $html = '<input type="checkbox" name="'.$name.'" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        $html .= 'value="'.$val.'" />';
        return $html;
    }

    function select($name, $attr = null, $options = array(), $value = null){
        $html = '<select name="'.$name.'" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        $html .= '>';
        foreach($options as $opname=>$val){
            if (empty($value)) {
                $value = $this->POST($name) ? $this->POST($name) : $this->GET($name);
            }
            $value == $val
            ? $selected = 'selected'
            : $selected = ' ';
            $html .= '<option value="'.$val.'"'.$selected.'>'.$opname.'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    function textarea($name, $attr = null, $value = null){
        $html = '<textarea name="'.$name.'" rows="10" cols="10" ';
        if(!empty($attr)){
            foreach($attr as $attrname=>$val){
                $html .= $attrname.'="'.$val.'" ';
            }
        }
        if (empty($value)) {
            $value = $this->POST($name) ? $this->POST($name) : $this->GET($name);
        }
        $html .= '>'.$value.'</textarea>';
        return $html;
    }
}