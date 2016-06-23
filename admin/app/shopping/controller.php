<?php
class ShoppingController extends Controller{

 	function  __construct() {
			$this->css(array('content.css','calendar.css'));  //look  添加时间显示样式calendar.css
			$this->js(array('calendar.js','calendar-setup.js','calendar-zh.js'));  //look  添加时间显示特效js
	}
		
		//配送方式方式
		function shoppinglist(){
			//删除
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				if($id>0){
				$this->App->delete('shipping_name','shipping_id',$id);
				}
				
			$rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}shipping_name`");
			$this->set('rt',$rt);
			 $this->template('deliverylist');
		}
		//配送方式信息
		function shoppinginfo($data=array()){
			$id = isset($data['id']) ? $data['id'] : 0;
			if($id>0){
				$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}shipping_name` WHERE shipping_id='$id' LIMIT 1");
				if(isset($_POST)&&!empty($_POST)){
					if($this->App->update('shipping_name',$_POST,'shipping_id',$id)){
						$this->jump('shopping.php?type=shoppinginfo&id='.$id,0,'更新成功！'); exit;
					}else{
						$this->jump('shopping.php?type=shoppinginfo&id='.$id,0,'更新失败！'); exit;
					}
				}
				$type = 'edit';
			}else{
				if(isset($_POST)&&!empty($_POST)){
					if($this->App->insert('shipping_name',$_POST)){
						$this->jump('shopping.php?type=shoppinginfo',0,'添加成功！'); exit;
					}else{
						$this->jump('shopping.php?type=shoppinginfo',0,'添加失败！'); exit;
					}
				}
				$type = 'add';
			}
			$this->set('type',$type);
			$this->set('rt',$rt);
			 $this->template('deliveryinfo');
		}
		

	function shoppingsn($data=array()){
		$rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}shipping_name`");
		$this->set('rt',$rt);
		
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : 1;
		if(empty($page)){
				$page = 1;
		}
		$list = 20;
		$start = ($page-1)*$list;
		$comd = array();
		$w = "";
		if(isset($_GET['sid'])&&$_GET['sid'] > 0) $comd[] = "tb1.shipping_id = '".intval($_GET['sid'])."'";
		if(isset($_GET['keyword'])&&!empty($_GET['keyword'])) $comd[] = "tb1.shipping_sn LIKE '%".trim($_GET['keyword'])."%'";
		if(!empty($comd)){
			$w = "WHERE ".implode(' AND ',$comd);
		}
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}shipping_name` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w";
		$tt = $this->App->findvar($sql);
		$rts['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT tb1.*,tb2.shipping_name FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}shipping_name` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w ORDER BY tb1.id DESC LIMIT $start,$list";
		$rts['lists'] = $this->App->find($sql);
		$this->set('rts',$rts);
		$this->template('shoppingsn');
	}
	
	function ajax_add_mark_sn($data=array()){
		$sid = intval($data['shopping_id']);
		$ptid = intval($data['shipping_sn']);
		
		if($sid > 0 && $ptid > 0 ){
			$sql = "SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE shipping_sn = '$ptid' LIMIT 1";
			$id = $this->App->findvar($sql);
			if($id > 0){
			
			}else{
				$this->App->insert('shipping_sn',array('shipping_id'=>$sid,'shipping_sn'=>$ptid,'addtime'=>mktime()));
			}
		}
		exit;
	}
	
	//生成物流号码
	function ajax_submit_mark_sn($data=array()){
		$sid = $data['sid'];
		$ptid = $data['ptid'];
		$startptid = $data['startptid'];
		$endptid = $data['endptid'];
		if($sid > 0 && $ptid > 0 && $startptid > 0 && $endptid > 0 && $endptid > $startptid){
			$k= 0;
			for($i=$startptid;$i<=$endptid;$i++){
				++$k;
				if($k>300) break;
				$sn = $ptid.$i;
				$sql = "SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE shipping_sn = '$sn' LIMIT 1";
				$id = $this->App->findvar($sql);
				if($id > 0){
				
				}else{
					$this->App->insert('shipping_sn',array('shipping_id'=>$sid,'shipping_sn'=>$sn,'addtime'=>mktime()));
				}
			}
			
		}
		exit;
	}
	
	//物流单关联订单
	function ajax_shopping_op($data){
		$oid = $data['oid'];
		$sn = $data['val'];
		$sid = $data['sid'];
		if($oid > 0 && $sid>0 && !empty($sn)){
			$ssn = $this->App->findvar("SELECT sn_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_id='$oid' LIMIT 1");
			if(!empty($ssn)&&$ssn!==$sn){
				$this->App->update('shipping_sn',array('addtime'=>'0','usetime'=>'0','is_use'=>'0'),'shipping_sn',$ssn); //更新原来的
			}
			
			$sql = "SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE shipping_sn='$sn' LIMIT 1";
			$id = $this->App->findvar($sql);
			if($id > 0){
				$this->App->update('shipping_sn',array('shipping_id'=>$sid,'usetime'=>mktime(),'is_use'=>'1'),'shipping_sn',$sn);
			}else{
				$this->App->insert('shipping_sn',array('shipping_sn'=>$sn,'shipping_id'=>$sid,'usetime'=>mktime(),'addtime'=>mktime(),'is_use'=>'1'));
			}
			$this->App->update('goods_order_info',array('sn_id'=>$sn,'shipping_id_true'=>$sid),'order_id',$oid);
			
		}
	}
}
?>