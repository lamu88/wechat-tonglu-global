<?php
/*
*调用控制类之前配置的
*/
defined('SESS_PATH')
? $_this->Session   = Import::session(SESS_PATH, 0)
: $_this->Session   = Import::session(SYS_PATH.DS.'sess'.DS,0);

$_this->Cache  = Import::cache(SYS_PATH.'cache', $_this->args());


/*if(function_exists('IS_admin') && IS_admin()) {
    $_this->Cache->close = true;
}*/

//$_this->set('sql',$sql);

//包括前台公告信息
if(defined('SYS_PATH_THEME') || defined('SYS_PATH_WAP')){
	if(!class_exists('Common')){
			require_once(SYS_PATH.'inc/common.php');
	}
}
?>