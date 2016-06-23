<?php
class AreaController extends Controller{
 	function  __construct() {
		$this->css('content.css');
	}
	
	function index($id=0){
		 if(!($id>0)) $id = 0;
		 //添加
		 if(!empty($_POST) && !empty($_POST['region_name'])){
		 		$rn = trim($_POST['region_name']);
				//检查
				/*$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name='$rn'";
				$val = $this->App->findvar($sql);
				if(!empty($val)){
						$this->jump('',0,'该名称已经存在！');
				}else{*/
					$_POST['region_name'] = trim($_POST['region_name']);
					$this->App->insert('region',$_POST);
				//}
		 }
		 //删除
		 if(isset($_GET['op']) && $_GET['op']='del'){
		 		$ids = $_GET['ids'];
				if($ids>0){
					if($this->App->delete('region','region_id',$ids)){
						$this->jump('area.php?type=list'.($id>0?'&id='.$id:''));
					}
				}
		 }
		 
		 $sql = "SELECT * FROM `{$this->App->prefix()}region` WHERE parent_id='$id' ORDER BY region_name";
		 
		 $this->set('rt',$this->App->find($sql));
		 
		 $sql = "SELECT * FROM `{$this->App->prefix()}region` WHERE region_id='$id'";
		 $this->set('info',$this->App->findrow($sql));
		 $this->template('index');
	}
	
	function ajax_update_region_name($data=array()){
		$na = $data['val'];
		$id = $data['id'];
		
		$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name='$na' AND region_id!='$id'";
		$val = $this->App->findvar($sql);
		if(empty($val)){
			$this->App->update('region',array('region_name'=>$na),'region_id',$id);
		}else{
			echo '该名称已经存在!修改失败！';
		}
		exit;
	}
}
?>