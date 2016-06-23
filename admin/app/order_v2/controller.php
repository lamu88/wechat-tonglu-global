<?php
class Order_v2Controller extends Controller{
 	function  __construct() {
		  $this->css(array('content.css','calendar.css'));
		  $this->js(array('calendar.js','calendar-setup.js','calendar-zh.js'));
	}
	
	function shoppingsn($data=array()){
		$rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}shipping`");
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
		$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}shipping` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w";
		$tt = $this->App->findvar($sql);
		$rts['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT tb1.*,tb2.shipping_name FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}shipping` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w ORDER BY tb1.id DESC LIMIT $start,$list";
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
	
	function orderlist($data=array()){
		   //分页
           $page= isset($_GET['page']) ? $_GET['page'] : 1;
           if(empty($page)){
                $page = 1;
           }
		   $list = 20;
		   $start = ($page-1)*$list;
		
		    if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }
            if(isset($_GET['order_sn'])&&!empty($_GET['order_sn'])) $comd[] = "order_sn LIKE '%".trim($_GET['order_sn'])."%'";
			
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) && empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time1'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
			
			if(isset($_GET['add_time2'])&&!empty($_GET['add_time2']) && empty($_GET['add_time1'])){
                    $comd[] = "add_time <= ". strtotime($_GET['add_time2']);
			}
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) &&isset($_GET['add_time2'])&& !empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time2'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
							
            if(isset($_GET['consignee'])&&!empty($_GET['consignee'])) $comd[] = "consignee LIKE '%".trim($_GET['consignee'])."%'";
			
			$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info_daigou`";
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,du.nickname AS dunickname,uu.nickname AS uunickname,pu.nickname AS punickname FROM `{$this->App->prefix()}goods_order_info_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS du ON du.user_id=tb1.daili_uid LEFT JOIN `{$this->App->prefix()}user` AS uu ON uu.user_id=tb1.user_id LEFT JOIN `{$this->App->prefix()}user` AS pu ON pu.user_id=tb1.parent_uid ORDER BY tb1.order_id DESC LIMIT $start,$list";
			$lists = $this->App->find($sql);
			$rt['lists'] = array();
			if(!empty($lists))foreach($lists as $k=>$row){
				$rt['lists'][$k] = $row;
				$oid = $row['order_id'];
				$rt['lists'][$k]['gimg'] = $this->App->findcol("SELECT goods_thumb FROM `{$this->App->prefix()}goods_order_daigou` WHERE order_id='$oid'");
				$rt['lists'][$k]['status'] = $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
			}
		
		 	$this->set('rt',$rt);
			$this->template('goods_order_list');
	}
	
	function orderlist_yifa($data=array()){
		   //分页
           $page= isset($_GET['page']) ? $_GET['page'] : 1;
           if(empty($page)){
                $page = 1;
           }
		   $list = 20;
		   $start = ($page-1)*$list;
		
		    if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }
            if(isset($_GET['order_sn'])&&!empty($_GET['order_sn'])) $comd[] = "order_sn LIKE '%".trim($_GET['order_sn'])."%'";
			
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) && empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time1'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
			
			if(isset($_GET['add_time2'])&&!empty($_GET['add_time2']) && empty($_GET['add_time1'])){
                    $comd[] = "add_time <= ". strtotime($_GET['add_time2']);
			}
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) &&isset($_GET['add_time2'])&& !empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time2'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
							
            if(isset($_GET['consignee'])&&!empty($_GET['consignee'])) $comd[] = "consignee LIKE '%".trim($_GET['consignee'])."%'";
			
			$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}goods_order_address` WHERE shipping_status ='2'";
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
			$sql .=" WHERE tb1.shipping_status ='2' ORDER BY id DESC LIMIT $start,$list";	
			$res = $this->App->find($sql);
			$ress = '';
			if(!empty($res))foreach($res as $k=>$row){
				$ress[$k] = $row;
				$rid = $row['rec_id'];
				$id = $row['id'];
				$sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix()}goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix()}goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
				$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
				$sql .=" WHERE tb3.rec_id = '$rid' LIMIT 1";
				$rts = $this->App->findrow($sql);
				$ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
				$ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
				$ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
				$sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb2.id = tb1.rid";
				$sql .=" WHERE tb1.rid='$id' LIMIT 1";
				$ress[$k]['shipping_sn'] = $this->App->findvar($sql);
			}
			$this->set('rt',$ress);
//print_r($ress);
			$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		    $this->set('sp',$this->App->find($sql));
			$this->template('orderlist_yifa');
	}
	
	function orderlist_daifa($data=array()){
		   //分页
           $page= isset($_GET['page']) ? $_GET['page'] : 1;
           if(empty($page)){
                $page = 1;
           }
		   $list = 20;
		   $start = ($page-1)*$list;
		
		    if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }
            if(isset($_GET['order_sn'])&&!empty($_GET['order_sn'])) $comd[] = "order_sn LIKE '%".trim($_GET['order_sn'])."%'";
			
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) && empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time1'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
			
			if(isset($_GET['add_time2'])&&!empty($_GET['add_time2']) && empty($_GET['add_time1'])){
                    $comd[] = "add_time <= ". strtotime($_GET['add_time2']);
			}
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) &&isset($_GET['add_time2'])&& !empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time2'])+24*60*60 ;
                    $comd[] = "add_time >= ". strtotime($_GET['add_time1']) ."&&add_time < " .$t;
			}
							
            if(isset($_GET['consignee'])&&!empty($_GET['consignee'])) $comd[] = "consignee LIKE '%".trim($_GET['consignee'])."%'";
			$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_daigou` AS tb2  ON tb2.rec_id = tb1.rec_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_info_daigou` AS tb3  ON tb3.order_id = tb2.order_id";
			$sql .=" WHERE tb1.shipping_status ='0' AND tb3.pay_status = '2'";echo $sql;
			//$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}goods_order_address` WHERE shipping_status ='0'";
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
			$sql .=" WHERE tb1.shipping_status ='0' ORDER BY id DESC LIMIT $start,$list";	
			$res = $this->App->find($sql);
			$ress = '';
			if(!empty($res))foreach($res as $k=>$row){
				$ress[$k] = $row;
				$rid = $row['rec_id'];
				$id = $row['id'];
				$sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix()}goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix()}goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
				$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
				$sql .=" WHERE tb3.rec_id = '$rid' LIMIT 1";
				$rts = $this->App->findrow($sql);
				$ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
				$ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
				$ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
				$sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb2.id = tb1.rid";
				$sql .=" WHERE tb1.rid='$id' LIMIT 1";
				//$ress[$k]['shipping_sn'] = $this->App->findvar($sql);
			}
			$this->set('rt',$ress);

			$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		    $this->set('sp',$this->App->find($sql));
			$this->template('orderlist_daifa');
	}
	
	function update_shipping_id($id=0,$sid=0){
		//ID:goods_order_address id AND shipping_sn rid
		if($id > 0 && $sid > 0){
			// 
			$tt = 'false';
			$sql = "SELECT is_use FROM `{$this->App->prefix()}shipping_sn` WHERE rid = '$id' LIMIT 1";
			$is_use = $this->App->findvar($sql);
			if($is_use=='1'){
				$this->App->update('shipping_sn',array('is_use'=>'0','usetime'=>'0'),'rid',$id);
				//重新选择物流单号
				$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
				if($ids > 0){
					$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
				}else{
					$tt = 'true';
				}
			}else{
				//选择物流单号
				$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
				if($ids > 0){
					$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
				}else{
					$tt = 'true';
				}
			}
			if($tt=='false'){
				$this->App->update('goods_order_address',array('shipping_id'=>$sid),'id',$id);
			}
		}
	}
	
	function update_shipping_id2($data=array()){
		$id = $data['id'];
		$sid = $data['sid'];
		if($id > 0 && $sid > 0){
			$sql = "SELECT is_use FROM `{$this->App->prefix()}shipping_sn` WHERE rid = '$id' LIMIT 1";
			$is_use = $this->App->findvar($sql);
			if($is_use=='1'){
				$this->App->update('shipping_sn',array('is_use'=>'0','usetime'=>'0'),'rid',$id);
				//重新选择物流单号
				$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
				if($ids > 0){
					$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
				}else{
					echo "2";exit;
				}
			}else{
				//选择物流单号
				$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
				if($ids > 0){
					$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
				}else{
					echo "2";exit;
				}
			}
			$this->App->update('goods_order_address',array('shipping_id'=>$sid),'id',$id);
		}
	}
	
  	//发货操作
	function ajax_fahuo($data=array()){
		$rid = $data['rid'];
		$oid = $data['oid'];
		
		$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.rec_id='$rid'";	
		$res = $this->App->find($sql);
		$ress = '';
		if(!empty($res))foreach($res as $k=>$row){
			$ress[$k] = $row;
			$rid = $row['rec_id'];
			$id = $row['id'];
			$sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix()}goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix()}goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
			$sql .=" WHERE tb3.rec_id = '$rid' LIMIT 1";
			$rts = $this->App->findrow($sql);
			$ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
			$ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
			$ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
			$sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix()}shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb2.id = tb1.rid";
			$sql .=" WHERE tb1.rid='$id' LIMIT 1";
			$ress[$k]['shipping_sn'] = $this->App->findvar($sql);
		}
		$this->set('rt',$ress);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$this->set('sp',$this->App->find($sql));
		
		$sql = "SELECT shipping_id FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
		$this->set('sid',$this->App->findvar($sql));
		
		$this->set('oid',$oid);
		$this->set('rid',$rid);
		$this->template('ajax_fahuo');
	}
	function ajax_fahuo_op($data=array()){
		$id = $data['id'];
		$rid = $data['rid'];
		if($rid > 0){ //批量发货
			$this->App->update('goods_order_address',array('shipping_status'=>'2'),'rec_id',$rid);
			//
			$sql = "SELECT order_id FROM `{$this->App->prefix()}goods_order_daigou` WHERE rec_id ='$rid' LIMIT 1";
			$oid = $this->App->findvar($sql);
			if($oid > 0){
				//更新状态
				$sql = "SELECT tb1.id FROM `{$this->App->prefix()}goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_daigou` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb2.order_id = '$oid' AND tb1.shipping_status != '2' LIMIT 1";
				$id = $this->App->findvar($sql);
				if($id>0){
					$this->App->update('goods_order_info_daigou',array('shipping_status'=>'3'),'order_id',$oid);
				}else{
					$this->App->update('goods_order_info_daigou',array('shipping_status'=>'2'),'order_id',$oid);
				}
				
				$sql = "SELECT shipping_id FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
				$ssid = $this->App->findvar($sql);
				
				//选择物流单号
				$sql = "SELECT id,shipping_id FROM `{$this->App->prefix()}goods_order_address` WHERE rec_id='$rid'";
				$ll = $this->App->find($sql);
				if(!empty($ll))foreach($ll as $row){
					$id = $row['id'];
					$sid = $row['shipping_id'];
					if(!($sid > 0)) $sid = $ssid;
					$sql = "SELECT is_use FROM `{$this->App->prefix()}shipping_sn` WHERE rid = '$id' LIMIT 1";
					$is_use = $this->App->findvar($sql);
					if($is_use=='1'){
						$this->App->update('shipping_sn',array('is_use'=>'0','usetime'=>'0'),'rid',$id);
						//重新选择物流单号
						$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
						if($ids > 0){
							$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
						}else{}
					}else{
						//选择物流单号
						$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
						if($ids > 0){
							$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
						}else{}
					}
				}
			}
		}else{ //单个地址发货
			$this->App->update('goods_order_address',array('shipping_status'=>'2'),'id',$id);
			//
			$sql = "SELECT tb1.order_id FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb2.rec_id = tb1.rec_id WHERE tb2.id ='$id' LIMIT 1";
			$oid = $this->App->findvar($sql);
			if($oid > 0){
				$sql = "SELECT tb1.id FROM `{$this->App->prefix()}goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_daigou` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb2.order_id = '$oid' AND tb1.shipping_status != '2' LIMIT 1";
				$idl = $this->App->findvar($sql);
				if($idl>0){
					$this->App->update('goods_order_info_daigou',array('shipping_status'=>'3'),'order_id',$oid); //部分发货
				}else{
					$this->App->update('goods_order_info_daigou',array('shipping_status'=>'2'),'order_id',$oid);
				}
				
				$sql = "SELECT shipping_id FROM `{$this->App->prefix()}goods_order_address` WHERE id='$id' LIMIT 1";
				$sid = $this->App->findvar($sql);
				if(!($sid > 0)){
					$sql = "SELECT shipping_id FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
					$sid = $this->App->findvar($sql);
				}
				
				$sql = "SELECT is_use FROM `{$this->App->prefix()}shipping_sn` WHERE rid = '$id' LIMIT 1";
				$is_use = $this->App->findvar($sql);
				if($is_use=='1'){
					$this->App->update('shipping_sn',array('is_use'=>'0','usetime'=>'0'),'rid',$id);
					//重新选择物流单号
					$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
					if($ids > 0){
						$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
					}else{}
				}else{
					//选择物流单号
					$ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
					if($ids > 0){
						$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime(),'rid'=>$id),'id',$ids);
					}else{}
				}
				
			}
		}
		
	}
	
	function order_info($data=array()){
			$this->css('jquery_dialog.css');
			$this->js('jquery_dialog.js');
			$orderid = $data['id'];
			$sql= "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id='$orderid'";	
			$rt['orderinfo'] = $this->App->findrow($sql);
			if(empty($rt['orderinfo'])){
				$this->jump(ADMIN_URL.'goods_order_v2.php?type=orderlist');exit;
			}
			$sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$orderid' GROUP BY tb2.rec_id";
			$goodslist = $this->App->find($sql);
			if(!empty($goodslist))foreach($goodslist as $k=>$row){
				$rt['goodslist'][$k] = $row;
				$rec_id = $row['rec_id'];
				$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
				$sql .=" WHERE tb1.rec_id='$rec_id'";	
				$rt['goodslist'][$k]['ress'] = $this->App->find($sql);
			}
	
			$status = $this->get_status($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']);
			$rt['status'] = explode(',',$status);
		
			$rt['order_action_button'] = $this->get_order_action_button($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']); //返回订单操作按钮
			
			//订单操作信息
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_action_v2` WHERE order_id='$id' ORDER BY log_time DESC";
			$rs = $this->App->find($sql);
			$rt['action_info'] = array();
			if(!empty($rs)){
				foreach($rs as $k=>$row){
				    $rt['action_info'][$k] = $row;
					$rt['action_info'][$k]['status']=  $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
					$rt['action_info'][$k]['log_time'] = !empty($row['log_time']) ? date('Y-m-d H:i:s',$row['log_time']) : '无知';
					$os = $this->get_status($row['order_status'],'tt','tt');
					$rt['action_info'][$k]['order_status'] = !empty($os) ? str_replace(',','',$os) : "";
					$ss = $this->get_status('tt','tt',$row['shipping_status']);
					$rt['action_info'][$k]['shipping_status'] = !empty($ss) ? str_replace(',','',$ss) : "";;
					$ps = $this->get_status('tt',$row['pay_status'],'tt');
					$rt['action_info'][$k]['pay_status'] = !empty($ps) ? str_replace(',','',$ps) : "";;
				}
			}
			
			$this->set('rt',$rt);
			$this->template('order_info');
	}
	
   //获取操作状态按钮
	function ajax_get_status_button($var=0){
			if(strlen($var) != 3) return;
			
			$order_status = substr($var,0,1);
			$pay_status = substr($var,1,1);
			$shipping_status = substr($var,-1);
			
			$str = $this->get_order_action_button($order_status,$pay_status,$shipping_status);
			die($str);
	}
		
    //ajax 处理订单状态
    function  ajax_order_bathop($ids=0,$type=""){
			@set_time_limit(600); //最大运行时间
			
            if(empty($ids)){ echo "没有找到需要删除的产品！"; exit;}
            if(empty($type)){ echo "没有指定的操作类型！"; exit;}
            $id_arr = @explode('+',$ids);

            switch ($type){
                case 'bathdel':
                    //批量删除订单
                    $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")");
                    if(!empty($now_status)){
                        $afterarr = array();
                        foreach($now_status as $k=>$status){
                            if(in_array($status,array('0','2','3'))){
                                //$str = "部分操作不能完成，例如：确认、退货、刚下的的订单不能操作了！";
                                $afterarr[] = $id_arr[$k];
                            }
                        }
                        if(!empty($afterarr)){
                            $id_arr_ = array_diff($id_arr, $afterarr);
                            unset($id_arr);
                            $id_arr = $id_arr_;
                            unset($id_arr_);
                        }
                    }

                    //$sql = "DELETE FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")";
                    //$this->App->query($sql);
                    if(!empty($id_arr)){
                    	$this->App->delete('goods_order_info','order_id',$id_arr);  //订单表
                    	$this->App->delete('goods_order','order_id',$id_arr);  //订单商品表
						$this->App->delete('goods_order_action','order_id',$id_arr);  //订单操作记录表
                    	$this->action('system','add_admin_log','批量删除商品订单：'.@implode(',',$id_arr));
                    }else{
							echo "无法进行该操作！";exit;
					}
                    break;
                 case 'bathconfirm':
                    //批量确认订单
                    //查询当前的订单状态，如果当前的状态为取消[1]、失效[4]、那么将不能再确认了
                    $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")");
                    if(!empty($now_status)){
                        $afterarr = array();
                        foreach($now_status as $k=>$status){
                            if(in_array($status,array('1','4'))){
                                //$str = "部分操作不能完成，例如：失效、取消的订单不能操作了！";
                                $afterarr[] = $id_arr[$k];
                            }
                        }
                        if(!empty($afterarr)){
                            $id_arr_ = array_diff($id_arr, $afterarr);
                            unset($id_arr);
                            $id_arr = $id_arr_;
                            unset($id_arr_);
                        }
                    }
                    if(!empty($id_arr)){
                        if($this->App->update('goods_order_info',array('order_status'=>'2'),'order_id',$id_arr)){
							$sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix()}goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(".implode(',',$id_arr).")";
							$emails = $this->App->find($sql);
							if(!empty($emails))foreach($emails as $row){
								//确认后，发送mail
								$datas = array();
								if(!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['confirm_order']=='1'){
									$datas['user_name'] = $row['user_name'];
									$datas['uid'] = $row['user_id'];
									$datas['order_sn'] = $row['order_sn'];
									$datas['email'] = $row['email'];
									$datas['orderinfourl'] = SITE_URL.'user.php?act=orderinfo&order_id='.$row['order_id'];
									$this->action('email','send_confirm_order',$datas);
									unset($datas);
								}
							}
							
						}
                        $this->action('system','add_admin_log','批量确认订单：'.@implode(',',$id_arr));
                    }else{
							echo "无法进行该操作！";exit;
					}
                    break;
                 case 'bathcancel':
                    //批量取消订单
                    //查询当前的订单状态，如果当前的状态为确认[2]、退货[3]、那么将不能再取消了了
                    $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")");
                    $str = "";
                    if(!empty($now_status)){
                        $afterarr = array();
                        foreach($now_status as $k=>$status){
                            if(in_array($status,array('2','3'))){
                                $afterarr[] = $id_arr[$k];
                            }
                        }
                        if(!empty($afterarr)){
                            $id_arr_ = array_diff($id_arr, $afterarr);
                            unset($id_arr);
                            $id_arr = $id_arr_;
                            unset($id_arr_);
                        }
                    }
                    if(!empty($id_arr)){
                    	$this->App->update('goods_order_info',array('order_status'=>'1'),'order_id',$id_arr);
						
						$sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix()}goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(".implode(',',$id_arr).")";
						$emails = $this->App->find($sql);
						if(!empty($emails))foreach($emails as $row){
							//订单取消后，发送mail
							$datas = array();
							if(!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['cancel_order']=='1'){
								$datas['user_name'] = $row['user_name'];
								$datas['uid'] = $row['user_id'];
								$datas['order_sn'] = $row['order_sn'];
								$datas['email'] = $row['email'];
								$datas['orderinfourl'] = SITE_URL.'user.php?act=orderinfo&order_id='.$row['order_id'];
								$this->action('email','send_cancel_order',$datas);
								unset($datas);
							}
						}
							
                    	$this->action('system','add_admin_log','批量取消订单：'.@implode(',',$id_arr));
                    }else{
							echo "无法进行该操作！";exit;
					}
                    break;
                 case 'bathinvalid':
                    //批量失效订单
                    //查询当前的订单状态，如果当前的状态为确认[2]、退货[3]、那么将不能再失效了
                    $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")");
                    $str = "";
                    if(!empty($now_status)){
                        $afterarr = array();
                        foreach($now_status as $k=>$status){
                            if(in_array($status,array('2','3'))){
                                //$str = "部分操作不能完成，例如：确认、退货的订单不能操作了！";
                                $afterarr[] = $id_arr[$k];
                            }
                        }
                        if(!empty($afterarr)){
                            $id_arr_ = array_diff($id_arr, $afterarr);
                            unset($id_arr);
                            $id_arr = $id_arr_;
                            unset($id_arr_);
                        }
                    }
                    if(!empty($id_arr)){
                    	$this->App->update('goods_order_info',array('order_status'=>'4'),'order_id',$id_arr);
						
						$sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix()}goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(".implode(',',$id_arr).")";
						$emails = $this->App->find($sql);
						if(!empty($emails))foreach($emails as $row){
							//订单取消后，发送mail
							$datas = array();
							if(!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['orders_invalid']=='1'){
								$datas['user_name'] = $row['user_name'];
								$datas['uid'] = $row['user_id'];
								$datas['order_sn'] = $row['order_sn'];
								$datas['email'] = $row['email'];
								$datas['orderinfourl'] = SITE_URL.'user.php?act=orderinfo&order_id='.$row['order_id'];
								$this->action('email','send_invalid_order',$datas);
								unset($datas);
							}
						}
							
                    	$this->action('system','add_admin_log','批量失效订单：'.@implode(',',$id_arr));
                    }else{
							echo "无法进行该操作！";exit;
					}
                    break;

            }

   }
		
	//订单的状态
   function get_status($oid=0,$pid=0,$sid=0){ //分别为：订单 支付 发货状态
            $str = '';
            switch($oid){
                case '0':
                    $str .= '未确认,';
                    break;
                case '1':
                    $str .= '<font color="red">取消</font>,';
                    break;
                case '2':
                    $str .= '确认,';
                    break;
                case '3':
                    $str .= '<font color="red">退货</font>,';
                    break;
                case '4':
                    $str .= '<font color="red">无效</font>,';
                    break;
            }

           switch($pid){
                case '0':
                    $str .= '未付款,';
                    break;
                case '1':
                    $str .= '已付款,';
                    break;
                case '2':
                    $str .= '已退款,';
                    break;
            }

            switch($sid){
                case '0':
                    $str .= '未发货';
                    break;
                case '1':
                    $str .= '配货中';
                    break;
                case '2':
                    $str .= '已发货';
                    break;
                case '3':
                    $str .= '部分发货';
                    break;
                case '4':
                    $str .= '退货';
                    break;
                case '5':
                    $str .= '已收货';
                    break;
            }
            return $str;
  }
  
  function get_order_action_button($order_status=0,$pay_status=0,$shipping_status=0){
			$str = "";
			if($order_status==0){ 	//没确认(没付款、没发货)
				$str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
				$str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
				$str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
				$str .= '<input value="无效" class="order_action" type="button" id="400">'."\n";
				
			}else if($order_status==2){   //已经确认
			    if($pay_status==0){ //没支付
				    $str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
				    $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
				    $str .= '<input value="无效" class="order_action" type="button" id="400">'."\n";
				
				}else if($pay_status==1){ //已支付
					if($shipping_status==0){ //未发货
					     $str .= '<input value="发货" class="order_action" type="button" id="212">'."\n";
				   		 $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
				    	 $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
						 
					 }else if($shipping_status==2){ //已发货
					     $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
					     $str .= '<input value="设为未发货" class="order_action" type="button" id="210">'."\n";
				   		 $str .= '<input value="收货" class="order_action" type="button" id="215">'."\n";
				    	 $str .= '<input value="退货" class="order_action" type="button" id="324">'."\n";
						 
					 }else if($shipping_status==1){ //配货中
					     $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
				   		 $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
					 }else if($shipping_status==5){ //已收货
					     $str .= '<input value="退货" class="order_action" type="button" id="324">'."\n";
					 }     
				}else if($pay_status==2){ //退款
				    if($shipping_status==2){ //已发货
					     $str .= '<input value="设为未发货" class="order_action" type="button" id="120">'."\n";
				   		 $str .= '<input value="退货" class="order_action" type="button" id="124">'."\n";
					 }else if($shipping_status==1){ //配货中
					      $str .= '<input value="设为未发货" class="order_action" type="button" id="120">'."\n";
				   		  $str .= '<input value="退货" class="order_action" type="button" id="124">'."\n";
					 }else if($shipping_status==5){  //已收货
					      $str .= '<input value="设为未发货" class="order_action" type="button" id="120">'."\n";
				   		  $str .= '<input value="退货" class="order_action" type="button" id="124">'."\n";
					 }     
				}
			}else if($order_status==1){  //取消
			  	$str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
				$str .= '<input value="移除" class="order_action" type="button" id="remove">'."\n";
			}else if($order_status==4){ //无效
			    $str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
			}else if($order_status==3){ //退货
			    $str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
				$str .= '<input value="移除" class="order_action" type="button" id="remove">'."\n";
			}
			return $str;
	}
		
}
?>