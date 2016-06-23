<?php
class MubanController extends Controller{
 	function  __construct() {
           $this->css('content.css');
	}
	
	function index($data=array()){ 
		$thismubanid = $this->App->findvar("SELECT mubanid FROM `{$this->App->prefix()}systemconfig` WHERE type = 'basic' LIMIT 1");
		$this->set('thismubanid',$thismubanid);
		
		$arr = array();
		for($i=1;$i<19;$i++){
			$arr[$i]['img'] = ADMIN_URL.'images/muban/'.$i.'.png';
		}
		$this->set('arr',$arr);
		$this->template('index');
	}
	
	function indexnew($data=array()){ 
		$thismubanid = $this->App->findvar("SELECT mubanid FROM `{$this->App->prefix()}systemconfig` WHERE type = 'basic' LIMIT 1");
		$this->set('thismubanid',$thismubanid);
		
		$arr = array();
		for($i=24;$i<25;$i++){
			$arr[$i]['img'] = ADMIN_URL.'images/muban/'.$i.'.png';
		}
		$this->set('arr',$arr);
		$this->template('index');
	}
	
	function ajax_save_muban($data=array()){ 
		$id = $data['id'];
		if($this->App->update('systemconfig',array('mubanid'=>$id),'type','basic')){
			echo "保存成功";
		}else{
			echo "保存失败";
		}
		exit;
	}
	
}
?>