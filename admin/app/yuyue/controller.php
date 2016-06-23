<?php
class YuyueController extends Controller{
 	function  __construct() {
		$this->css('content.css');
	}
	function bmorderlist($data=array()){
		$id = $data['id'];
		if($id > 0){
			if($this->App->delete('cx_baoming_order','id',$id)){
				$this->jump(ADMIN_URL.'yuyue.php?type=bmorderlist');
				exit;
			}
		}
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 30;
		$start = ($page-1)*$list;
		
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_baoming_order`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT tb1.*,tb2.title,tb2.img,u.nickname FROM `{$this->App->prefix()}cx_baoming_order` AS tb1 LEFT JOIN `{$this->App->prefix()}cx_baoming` AS tb2 ON tb2.id = tb1.bid LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = tb1.user_id ORDER BY tb1.id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		
		$this->set('rt',$rt);
		$this->template('bmorderlist'); 
	}
	
	function baominglist($data=array()){
		$id = isset($data['id']) ? $data['id'] : '0';
		if($id > 0){
			if($this->App->delete('cx_baoming','id',$id)){
				$this->jump(ADMIN_URL.'yuyue.php?type=baominglist',0,'已删除');
				exit;
			}
			
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` ORDER BY id DESC";
		
		$rt = $this->App->find($sql);
		
		$this->set('rt',$rt);
		$this->template('baominglist');
	}
	
	function baominginfo($data=array()){
		$this->js(array("kindeditor/kindeditor.js"));
		$id = isset($data['id']) ? $data['id'] : '0';
		if($id > 0){
			$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` WHERE id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			
			if(!empty($_POST)){
				if($this->App->update('cx_baoming',$_POST,'id',$id)){
					 $this->action('common','showdiv',$this->getthisurl());
				}
			}
		
		}else{
			if(!empty($_POST)){
				$_POST['addtime'] = mktime();
				if($this->App->insert('cx_baoming',$_POST)){
					 $this->action('common','showdiv',$this->getthisurl());
				}
			}
		}
		
		$this->set('rt',$rt);
		$this->template('baominginfo');
	}
}
?>