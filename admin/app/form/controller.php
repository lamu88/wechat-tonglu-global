<?php
class FormController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
		$this->css('content.css');
	}
	
	function lists(){
		$w = "";
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY tb1.`'.$_GET['desc'].'` DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY tb1.`'.$_GET['asc'].'` ASC';
		}else {
		  	  $orderby = ' ORDER BY tb1.`mes_id` DESC';
		}
		
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT tb1.mes_id,tb1.user_name,tb1.companyurl,tb1.companyname,tb1.trade,tb1.jobs,tb1.comment_title,tb1.mobile,tb1.telephone,tb1.fax,tb2.nickname FROM `{$this->App->prefix()}message` AS tb1";
		//如果有用户id的话
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id";
		$sql .=" $orderby LIMIT $start,$list";
		$this->set('meslist',$this->App->find($sql));
		
		$this->template('mes_list');
	}
}
?>