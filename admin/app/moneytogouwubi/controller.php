<?php
class MoneytogouwubiController extends Controller{	
	//构造函数，自动新建对象
 	function  __construct() {
		$this->css('content.css');
		$this->js(array('common.js','time/WdatePicker.js'));
	}
	
	//资金记录
	function mglog($data=array()){
		
		 //分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			   $page = 1;
		}
		$list = 30;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(cid) FROM `{$this->App->prefix()}moneytogouwubi`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT u.nickname,us.*,ufo.nickname AS foname,uto.nickname AS toname FROM `{$this->App->prefix()}moneytogouwubi` AS us LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = us.uid LEFT JOIN `{$this->App->prefix()}user` AS uto ON uto.user_id = us.accid LEFT JOIN `{$this->App->prefix()}user` AS ufo ON ufo.user_id = us.uid ORDER BY us.time DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set("rt",$rt);
		$this->template('mtb');
	}
	
	function del(){
		$id= isset($_GET['id']) ? $_GET['id'] : 0;
		if($id > 0){
			if($this->App->delete('moneytogouwubi','cid',$id)){
				$this->jump(ADMIN_URL.'moneytogouwubi.php?type=mglog');exit;
			}
		}
	}
	
}
?>