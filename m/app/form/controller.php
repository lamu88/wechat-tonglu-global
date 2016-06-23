<?php
class FormController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
	}
	
	function index(){
		$this->title('提交表单');
		if(!defined(NAVNAME)) define('NAVNAME', "提交表单");
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/form_index');
	}
}
?>