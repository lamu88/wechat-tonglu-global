<?php
/**
 * @name Model
 * @version 1.01                                                               
 * @since 2010-08-29                                                           
 * @link http://www.cosplayprop.com
 * @package Cosplay 1.01 rec
 */
class Model{
    /**
     *
     * @var array
     */
    var $invalid     = array();

    /**
     * Format the data filter.
     * @example
     * 1. array('a'=>'noempty'); //this mean is $_POST['a'] must is no null var. or not verify function will return false
     * 2. array('a'=>array('noempty'); //like 1
     * 3. array('a'=>'b|SESSION'); //this mean is $_POST['a'] value must the same as $_SESSION['b'].
     * 4. array('a'=>array('noempty', 'b|SESSION')); //all both 1 and 3
     * 5. array('a'=>array('b|GET'); //this mean is $_POST['a'] value must the same as $_GET['b'].
     * @var <array>
     * @access numeric|noempty|email
     */
    var $validate    = array();
    var $invalidType = array();
    var $verify      = false;
    /**
     * @uses database login host
     * @var <string>
     */
    var $DB_HOST;
    /**
     * @uses database login username
     * @var <string>
     */
    var $DB_USER;
    /**
     * @uses database login password
     * @var <string>
     */
    var $DB_PASS;
    /**
     * @uses database name
     * @var <string>
     */
    var $DB_NAME;
    /**
     * Recording database connect resource target.
     * @var <object>
     */
    var $DBObject;

    /**
     * Call Database Class methods.
     *
     * @see /class/db-mysql.php
     * @param string $method
     * @param unkown $params
     * @return unkwn
     */
    function  __call($method, $params) {
        if (!$this->DBObject) {
            $this->DBconn();
        }
        $methods = get_class_methods($this->DBObject);
        if(in_array($method, $methods)){
            return call_user_func_array(array($this->DBObject, $method), $params);
        }
    }

    /**
     * Get Data from $_GET or $_POST
     *
     * If @param bool $filter is true, it will @return filter data. you can @see __filter method how it work.
     *
     * @param array $args
     * @param array $data
     * @param bool $filter
     * @return unkown
     */
    function __callData($args, $data = null, $filter = false){
        foreach($args as $val){
            if(isset($data[$val])){
                $data = $data[$val];
            }else{
                return null;
            }
        }
        return ($filter ? $this->__filter($data) : $data);
    }

    /**
     * Filter data.
     *
     * Use trim, strip_tags function to filter the data.
     *
     * @param unkown $data
     * @return unkown
     */
    function __filter($data){
        if(is_object($data)) {
            return $data;
        }else if(is_string($data)) {
            return trim(strip_tags($data));
        }

        foreach($data as $k=>$row) {
            $data[$k] = $this->__filter($row);
        }
        return $data;
    }

    /**
     * Check the data format.
     * It can check is numeric or not, is empty or not, is email or not.
     * @param string $type
     * @param unkown $name
     * @return bool
     */
    function __validate($type, $name){
        $value = $this->POST($name);
        switch($type){
            case 'numeric':
                if(!is_numeric($value)){
                    $this->invalid[$name] = 'Invalid numeric';
                    $this->invalidType[$name]['numeric'] = true;
                    return false;
                };
                break;
            case 'noempty':
                if(empty($value)){
                    $this->invalid[$name] = 'Can not be empty';
                    $this->invalidType[$name]['noempty'] = true;
                    return false;
                };
                break;
            case 'email':
                if(!ereg("^([a-zA-Z0-9~!#$%&_-])([.]?[0-9A-Za-z~!#$%&_-])*@[0-9A-Za-z~!#$%&_-]([.]?[0-9A-Za-z~!#$%&_-])*$", $value)){
                    $this->invalid[$name] = 'Invalid email';
                    $this->invalidType[$name]['email'] = true;
                    return false;
                };
                break;
        }
        return true;
    }

    /**
     * Check data is invalid data or not.
     *
     * @access private
     * @param string $name
     * @param unknow $value
     * @return bool
     */
    function __invalid($name, $value){
        if (!isset($this->validate[$name])) {
            return false;
        }
        $validate =  $this->validate[$name];
        if (is_array($validate)){
            foreach($validate as $k=>$val){
                if ($k === 'as') {
                    if (is_array($val)) {
                        foreach($val as $item){
                            if (preg_match('/\|SESSION/', $item)) {
                                $item = str_replace('|SESSION', '', $item);
                                $tg = $_SESSION[$item];
                            }else if (preg_match('/\|GET/', $item)) {
                                $item = str_replace('|GET', '', $item);
                                $tg = $this->GET($item);
                            }else {
                                $tg = $this->POST($item);
                            }
                            if($this->POST($name) != $tg){
                                $this->invalidType[$name]['as'][$item] = true;
                                $this->invalid[$name] = 'Not the same as '.$item;
                                return true;
                            }
                        }
                    }else {
                        if (preg_match ('/\|SESSION/', $val)) {
                            $val = str_replace('|SESSION', '', $val);
                            $tg = $_SESSION[$val];
                        }else if (preg_match ('/\|GET/', $val)) {
                            $val = str_replace('|GET', '', $val);
                            $tg = $this->GET($val);
                        }else {
                            $tg = $this->POST($val);
                        }
                        if($this->POST($name) != $tg){
                            $this->invalidType[$name]['as'][$val] = true;
                            $this->invalid[$name] = 'Not the same as '.$val;
                            return true;
                        }
                    }
                }else if($k === 'maxlen'){
                    $value = $this->POST($name);
                    if (strlen($value) > $val) {
                        $this->invalidType[$name]['maxlen'] = true;
                        $this->invalid[$name] = 'It is too long';
                        return true;
                    }
                }else {
                    if (!$this->__validate($val, $name)){
                        return true;
                    }
                }
            }
            return false;
        }else {
            if ($this->__validate($validate, $name)){
                return false;
            }else {
                return true;
            }
        }
    }

    /**
     * Verify Data.
     * @param <type> $arr
     * @param <type> $name
     * @return boolean
     */
    function __verify($arr, $name){
        $verify = true;
        if(is_array($arr)) {
            foreach($arr as $name2=>$value){
                if(!$this->__verify($value, $name.'.'.$name2)){
                    $verify = false;
                }else {
                    unset($this->invalid[$name]);
                }
            }
        }else {
            if($this->__invalid($name, $arr)){
                $verify = false;
            }
        }
        return $verify;
    }

    /**
     * Connect database
     */
    function DBconn(){
        if(!isset($GLOBALS['__Database__'])){
            $GLOBALS['__Database__'] = Import::db_mysql();
        }
        $this->DBObject = $GLOBALS['__Database__'];
    }

    /**
     * Return GET data.
     * 
     * @param <type> $name can use A.B.C to get $_POST[A][B][C] value.
     * @param <type> $value
     * @return <type> 
     */
    function & GET($var = null, $filter = false){
        if($var == ''){
            return ($filter ? $this->__filter($_GET) : $_GET);
        }else {
            return call_user_func_array(array(&$this, '__callData'), array(explode('.', $var), $_GET, $filter));
        }
    }

    function & gdata($var = null, $filter = false){
        return $this->GET($var, $filter);
    }

    /**
     * Get POST data.
     * @param string $name can use A.B.C to get $_POST[A][B][C] value.
     * @param bool $filter
     * @return unknown
     */
    function & POST($var = null, $filter = null){
        if($var == ''){
            return ($filter ? $this->__filter($_POST) : $_POST);
        }else {
            return call_user_func_array(array(&$this, '__callData'), array(explode('.', $var), $_POST, $filter));
        }
    }
    /**
     * for old version.
     * @param string $name can use A.B.C to get $_POST[A][B][C] value.
     * @param bool $filter
     * @return <type>
     */
    function & data($var = null, $filter = null) {
        return $this->POST($var, $filter);
    }

    /**
     * Get the Error message When you use @method invalid() to filter POST data.
     * @param <type> $name
     * @param <type> $value
     * @return <type>
     */
    function & invalid($name = null, $value = null){
        if (!empty($value)) {
            $this->invalid[$name] = $value;
        }else if ($name && isset($this->invalid[$name])) {
            return $this->invalid[$name];
        }else if(empty($name)) {
            return $this->invalid;
        }else {
            return null;
        }
    }

    /**
     * @todo get the invalid msg of type who had been filtered.
     * @param <type> $name
     * @param <type> $type
     * @param <type> $value
     * @return <type>
     * if you define $validate = array('a'=>'noempty');
     * And you post "a" data is empty.
     * You can use invalidType('a', 'noempty') return true.
     * OR use invalidType('a') return array('noempty');
     * if you post "a" is a noempty data, and this function will return nothing.
     */
    function invalidType($name = null, $type = null, $value = null) {
        if (!empty($name) && isset($this->invalidType[$name]) && empty($type)) {
            return $this->invalidType[$name];
        }else if (!empty($name) && !empty($type) && isset($this->invalidType[$name]) && empty($value)) {
            return $this->invalidType[$name][$type];
        }else if (!empty($name) && !empty($type) && !empty($value)&& isset($this->invalidType[$name][$type][$value])) {
            return $this->invalidType[$name][$type][$value];
        }else if(empty($name)) {
            return $this->invalidType;
        }else {
            return null;
        }
    }


    /**
     * Filter the data you have posted.
     * @return bool
     */
    function verify(){
        if (empty($_POST)) {
            return false;
        }
        if (empty($this->validate)) {
            return true;
        }
        $this->verify = true;
        foreach($_POST as $name=>$value){
            if(!$this->__verify($value, $name)){
                $this->verify = false;
            }
        }
        $this->afterValidate();
        return $this->verify;
    }

    /**
     * Do things when the filter end.
     * When finish filter data, it will run this function. you can define this method at children class.
     */
    function afterValidate(){
        
    }
	
	//新增加的方法
	//取代$_GET
	function G($key=""){
		$par_rt = isset($_SERVER['REQUEST_URI'])&&!empty($_SERVER['REQUEST_URI']) ? explode('?',$_SERVER['REQUEST_URI']) : "";
		if(count($par_rt)==2){
			$par_str = $par_rt[1];
			if(!empty($par_str)){
				$par_rts = explode('&',$par_str);
				if(!empty($par_rts)){
					foreach($par_rts as $str){
						if($str=='' || !strrchr($str,'=')) continue;
						$item = explode('=',$str);
						$urlarr[$item[0]] =  $item[1];
					}
				}
			}
			unset($par_rt,$par_rts);
		} 
		if(empty($key)) return $urlarr;
		return isset($urlarr[$key]) ?  $urlarr[$key] : "";
	}
	
	//新增加的方法
	//取代$_POST
	function P(){
	
	}
}
?>