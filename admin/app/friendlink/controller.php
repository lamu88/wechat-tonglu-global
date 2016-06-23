<?php

class FriendlinkController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数
		*/
		$this->css('content.css');
	}
	function lists(){
		$sql="SELECT * FROM `{$this->App->prefix()}friend_link`";
		$this->set('lists',$this->App->find($sql));
		$this->template('friendlink_list');
	}
	
	function add_edit($type='add',$id=0){
		$rt = array();
		if($type=='edit'){
			if(empty($id) || !(Import::basic()->int_preg($id))){
					$this->jump('friendlink.php?type=list');
					exit;
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}friend_link` WHERE link_id='$id'";	
			$rt = $this->App->findrow($sql);
		}
		$this->set('rt',$rt);
		$this->set('type',$type);
		$this->template('friendlink_edit');
	}
	
	function ajax_edit_add($data=array(),$id=0){
		if(!empty($data['width']) && !empty($data['height']) && !empty($data['link_logo'])){
			if(!empty($id)){
				$sql = "SELECT link_logo FROM `{$this->App->prefix()}friend_link` WHERE link_id='{$id}'";
				$var = $this->App->findvar($sql);
				if($data['link_logo']!=$var){
					Import::fileop()->delete_file(SYS_PATH.$var); //删除文件
					$q = dirname($var);
					$h = basename($var);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
					
					if(file_exists(SYS_PATH.$data['link_logo'])){
						Import::img()->thumb(SYS_PATH.$data['link_logo'],SYS_PATH.$data['link_logo'],$data['width'],$data['height']);
					}
			   }		
			}
		}
		
		if(empty($id)){
		  if($this->App->insert('friend_link',$data)){
			$this->action('system','add_admin_log','添加友情链接：'.$data['link_name']);
		  }
		  else{
			echo "无法添加友情链接，意外错误！";
		  }
		}else{
		  if($this->App->update('friend_link',$data,'link_id',$id)){
			$this->action('system','add_admin_log','修改友情链接：'.$data['link_name']);
		  }else{
			echo "数据未变动，无需更新！";
		  }
		}
		exit;
	}
	
	function ajax_dels($id=0){
		if(empty($id)){
			echo "非法删除，ID为空！";
		}else{
			$sql = "SELECT link_logo FROM `{$this->App->prefix()}friend_link` WHERE link_id='{$id}'";
			$var = $this->App->findvar($sql);
			if($this->App->delete('friend_link','link_id',$id)){
				Import::fileop()->delete_file(SYS_PATH.$var);
					$q = dirname($var);
					$h = basename($var);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
		}
	}
}
?>