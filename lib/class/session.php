<?php
/**
 * @name session.php
 * @copyright 2010
 * @version 1.01
 * @since 2010-08-27
 * @package Tmper 1.01 rec
 */
class Session extends Database{
    var $session_dir = '/sess';
    var $left_time = 604800;
    var $mysql = false;
    var $__last_visit = array();
    function  __construct($dir = null, $stag = 1, $session_id = null) {
        if(!headers_sent()){
	 // if(!isset($_SESSION)){
            if ($dir) {
                $this->session_dir = $dir;
            }

            if ($stag) {
                $str = '0123456789abcdefghijklmnopqrstuvwxyz';
                $len = strlen($str);
                for ($i=0; $i<$len; $i++) {
                    $dir = $this->session_dir.'/'.$str{$i}.'/';
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0777);
                    }
                }
                session_save_path("{$stag};".$this->session_dir);
            }else{
                session_save_path($this->session_dir);
            }
          
            ini_set('session.gc_maxlifetime', $this->left_time);
            if ($this->mysql === true) {
                if (!class_exists('Database')) {
                    require_once dirname(__file__).DS.'db-mysql.php';
                }
                session_set_save_handler(array($this, '__open'), array($this, '__close'), array($this, '__read'), array($this, '__write'), array($this, '__destroy'), array($this, '__gc'));
            }
            if ($session_id) {
                session_id($session_id);
            }
		//ini_set("session.use_trans_sid","On");

		//session_id(md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['PATH_TRANSLATED'].$_SERVER['SERVER_ADDR'].(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"")));
                @session_start();
        }else{ 
            echo '<!-- Session start failure! header had been send! you should check! -->';
        }		
    }

    function __destruct(){
        
    }

    function __open(){
        return true;
    }

    function __close(){
        return true;
    }

    function __read($session_id){
        $sql = "SELECT `value` FROM `".$this->prefix()."session` WHERE `session_id`='{$session_id}' AND expirY>".mktime()." LIMIT 1";
        $results = $this->findvar($sql);
        return $results;
    }

    function __write($session_id, $value){
        $sqldata['expiry'] = mktime() + $this->lifetime;
        $sqldata['value'] = $this->escape($value);
        $sqldata['session_id'] = $session_id;
        if(!empty($value)){
            $this->replace('session', $sqldata);
        }
        return true;
    }

    function __destroy($session_id){
        $sql = "DELETE FROM `".$this->prefix()."session` WHERE `session_id`='$session_id'";
        return $this->query($sql);
    }

    function __gc($maxlifetime){
        $sql = "DELETE FROM `".$this->prefix()."session` WHERE `expiry`<'".mktime()."'";
        return $this->query($sql);
    }

    function read($name){
        $vars = explode('.', $name);
        $output = $_SESSION;
        foreach($vars as $var){
            if(isset($output[$var])){
                $output = $output[$var];
            }else{
                return null;
            }
        }
        return $output;
    }

    function write($name, $data){
        if($name == '__last_visit'){
            if(count($this->__last_visit) > 10){
                array_shift($this->__last_visit);
            }
            $thisurl = Basic::thisurl();
            $this->__last_visit[md5($thisurl)] = $thisurl;
        }
        $vars = explode('.', $name);
        $str = null;
        foreach($vars as $var){
            $var == '' || $var == '[]'
            ? $str .= "[]"
            : $str .= "['$var']";
        }
        if(empty($data)){
            eval('unset($_SESSION'.$str.');');
        }else{
            eval('$_SESSION'.$str.'=$data;');
        }
    }

    function destroy(){
        session_destroy();
    }

    function flash($msg = null){
        if($msg){
            $this->write('__SESSION_FLASH__', $msg);
            $flash = $msg;
        }else{
            $flash = $this->read('__SESSION_FLASH__');
            $this->write('__SESSION_FLASH__', null);
        }
        return $flash;
    }
}
?>