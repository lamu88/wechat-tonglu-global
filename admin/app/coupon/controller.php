<?php
class CouponController extends Controller{
 	function  __construct() {
        $this->css('content.css');
		$this->js(array('common.js','time/WdatePicker.js'));
	}
	
	//优惠劵
    function index($id=0){
	   if($id>0){
	   		if($this->App->delete('bonus_type','type_id',$id)){
				$this->jump('coupon.php?type=list'); exit;
			}
	   }
	   
	   $sql = "SELECT tb1.*,COUNT(tb2.bonus_id) AS zcount,COUNT(tb3.bonus_id) AS ucount FROM `{$this->App->prefix()}bonus_type` AS tb1 LEFT JOIN `{$this->App->prefix()}bonus_list` AS tb2 ON tb1.type_id=tb2.bonus_type_id LEFT JOIN `{$this->App->prefix()}bonus_list` AS tb3 ON tb1.type_id=tb3.bonus_type_id AND tb3.used_time!='0' GROUP BY tb1.type_id";
	   $rt = $this->App->find($sql);
	   $this->set('rt',$rt);
	   $this->template('couponlist');
	}
	
	function coupon_type($id=0){
		//检查表单
		if(!empty($_POST)){
			if(empty($_POST['type_name'])){
				$this->jump('',0,'类型名称不能为空！'); exit;
			}
			if(!($_POST['type_money']>0)){
				$this->jump('',0,'红包金额必须大于0！'); exit;
			}
			(isset($_POST['send_start_date'])&&!empty($_POST['send_start_date'])) ? $_POST['send_start_date'] = strtotime($_POST['send_start_date']) : "";
			(isset($_POST['send_end_date'])&&!empty($_POST['send_end_date'])) ? $_POST['send_end_date'] = strtotime($_POST['send_end_date']) : "";
			(isset($_POST['use_start_date'])&&!empty($_POST['use_start_date'])) ? $_POST['use_start_date'] = strtotime($_POST['use_start_date']) : "";
			(isset($_POST['use_end_date'])&&!empty($_POST['use_end_date'])) ? $_POST['use_end_date'] = strtotime($_POST['use_end_date']) : "";
			
		}
		$rt = array();
		if($id>0){ //编辑
			if(!empty($_POST)){
				if($this->App->update('bonus_type',$_POST,'type_id',$id)){
					$this->jump('',0,'修改成功！'); exit;
				}else{
					$this->jump('',0,'修改失败！'); exit;
				}
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}bonus_type` WHERE type_id='$id'";
			$rt = $this->App->findrow($sql);
			$type = 'edit';
		}else{ //添加
			if(!empty($_POST)){
				if($this->App->insert('bonus_type',$_POST)){
					$this->jump('',0,'添加成功！'); exit;
				}else{
				echo '<script> alert("添加失败！");</script>';
				$rt = $_POST;
				}
			}
			$type = 'add';
		}
		$this->set('rt',$rt);
		$this->set('type',$type);
	 	$this->template('coupon_type');
	}
	
	//红包查看
	function coupon_view($id=0){
		if(isset($_GET['op'])&&$_GET['op']=='del' && intval($_GET['delid'])>0){ //删除
			if($this->App->delete('bonus_list','bonus_id',intval($_GET['delid']))){
				$this->jump('coupon.php?type=couponview&id='.$id); exit;
			}
		}
		$list = 15;
		$page = (isset($_GET['page'])&&intval($_GET['page'])> 0) ? intval($_GET['page']) : 1;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(bonus_id) FROM `{$this->App->prefix()}bonus_list` WHERE bonus_type_id='$id'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        $this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.type_name,tb2.send_type,tb3.user_name FROM `{$this->App->prefix()}bonus_list` AS tb1 LEFT JOIN `{$this->App->prefix()}bonus_type` AS tb2 ON tb1.bonus_type_id=tb2.type_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id=tb3.user_id WHERE tb1.bonus_type_id='$id' ORDER BY tb1.bonus_id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
        $this->set('rt',$rt);
		$send_by = $_GET['send_by'];
		$this->template('coupon_view'.$send_by);
	}
	
	function coupon_send(){
		$this->js(array('jquery.json-1.3.js'));
		$type_id = $_GET['type_id'];
		$send_by = $_GET['send_by'];
		if($send_by==0){
			//用户级别
			$sql = "SELECT `level_name`,`lid` FROM `{$this->App->prefix()}user_level` ORDER BY lid ASC";
			$rt['user_jibie'] = $this->App->find($sql);
			//获取省列表
			$rt['province'] = $this->action('user','get_regions',1);
			
			$this->set('rt',$rt);
		}else if($send_by==3){
			//添加红包
			if(isset($_POST) && !empty($_POST['bonus_sum'])){
				$bonus_sum = $_POST['bonus_sum'];
				$num = $this->App->findvar("SELECT MAX(bonus_sn) FROM `{$this->App->prefix()}bonus_list`");
				$num = $num ? floor($num / 10000) : 100000;
				for ($i = 0; $i < $bonus_sum; $i++)
				{
					$bonus_sn = ($num + $i) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
					$dd = array('bonus_type_id'=>$type_id,'bonus_sn'=>$bonus_sn);
					$this->App->insert('bonus_list',$dd);
				}
				$this->jump(ADMIN_URL.'coupon.php?type=couponview&id='.$type_id);exit;
			}
			$send_type = $this->App->findrow("SELECT type_money,type_name,type_id FROM `{$this->App->prefix()}bonus_type` WHERE type_id='$type_id'");
			$this->set('send_type',$send_type);
		}

		$this->template('coupon_send'.$send_by);
	}
	
	//红包派发
	function ajax_couponsend($data= array()){
		$type = $data['tt']; //派发类型
		$ids = $data['ids'];
		$type_id = $data['type_id'];
		
		switch($type){
			case 'selectuser':  //选择的用户派发红包
				$uids = @explode('|',$ids);
				if(!empty($uids)){
					$num = $this->App->findvar("SELECT MAX(bonus_sn) FROM `{$this->App->prefix()}bonus_list`");
					$num = $num ? floor($num / 10000) : 100000;
					foreach($uids as $id)
					{
						if(!($id>0)) continue;
						$bonus_sn = ($num + $i) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
						$dd = array('bonus_type_id'=>$type_id,'bonus_sn'=>$bonus_sn,'user_id'=>$id);
						if($this->App->insert('bonus_list',$dd)){
							//插入成功的一步
						}else{
							echo "发送过程意外错误";
							exit;
						}
					} //end foreach
				}
				break;
			case 'userlevel': 
				$count = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE user_rank='$ids' AND active='1'");
				$list = 50;
				$tt = 1;
				if($count>$list){
					$tt = ceil($count/$list);
				}
				$num = $this->App->findvar("SELECT MAX(bonus_sn) FROM `{$this->App->prefix()}bonus_list`");
				$num = $num ? floor($num / 10000) : 100000;
				for($i=0;$i<$tt;$i++){
					$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_rank='$ids' AND active='1'";
					$rt = $this->App->findcol($sql);
					if(!empty($rt)){
						foreach($rt as $id){
							if(!($id>0)) continue;
							$bonus_sn = ($num + $i) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
							$dd = array('bonus_type_id'=>$type_id,'bonus_sn'=>$bonus_sn,'user_id'=>$id);
							if($this->App->insert('bonus_list',$dd)){
								//插入成功的一步
							}else{
								echo "发送过程意外错误";
								exit;
							}
						}
					}
				}
				break;
		} //end switch
	}
}
?>