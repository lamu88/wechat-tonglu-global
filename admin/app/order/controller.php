<?php
class OrderController extends Controller{

 	function  __construct() {
          //  $this->css('content.css');
		//	$this->css('content.css');
			$this->css(array('content.css','calendar.css'));  //look  添加时间显示样式calendar.css
			$this->js(array('calendar.js','calendar-setup.js','calendar-zh.js'));  //look  添加时间显示特效js
	}
	function ajax_import_order_data($data=array()){
		$pagestart = $data['pagestart'];
		$pageend = $data['pageend'];
		if(!($pagestart > 0)) $pagestart = 1;
		$pagestart = $pagestart-1;
		
		if(!($pageend > 0)) $pageend = 1;
		
		$list = 12;
		$start = $list * $pagestart;
		$end = $list * $pageend;
		
		$zlist = ceil($end/$list);
		$sql = "SELECT tb1.*,tb2.goods_name FROM `{$this->App->prefix()}goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id WHERE tb1.pay_status = '1' ORDER BY tb1.order_id DESC LIMIT $start,$end";
		//$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '1' ORDER BY order_id DESC LIMIT $start,$end";
		//exit($sql);
		unset($data);
		
		$rt = $this->App->find($sql);
		
		  header("Content-Type:text/html;charset=utf-8");
		  header("Content-type:application/vnd.ms-excel");
		  header("Cache-Control: no-cache");
		  header("Pragma: no-cache");
		  header("Content-Disposition:filename=已付款订单(page:".($pagestart+1)."-page:".$pageend.").xls");
		  //微信昵称  真实姓名 保证金 营业额  总资金 级别 推荐代理 邮箱 电话 地址
		  $str1 = '';
		  if(!empty($rt)){
			  $str1  ='<table border="1" cellspacing="0" cellpadding="0">';
			  $str1 .='<tr><td style="text-align:center;" width="120">编号</td><td style="text-align:center;" width="200">产品名称</td><td style="text-align:center;" width="70">收货人</td><td style="text-align:center;" width="70">金额</td><td style="text-align:center;" width="70">数量</td><td style="text-align:center;" width="110">手机</td><td style="text-align:center;">地址</td></tr>';
			  foreach($rt as $row){
			  		$order_id = $row['order_id'];
					$number = $this->App->findvar("SELECT goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='{$order_id}' LIMIT 1");
					$province = $row['province'];
					$provincename = $this->App->findvar("SELECT region_name FROM `{$this->App->prefix()}region` WHERE region_id='{$province}' LIMIT 1");
					$city = $row['city'];
					$cityname = $this->App->findvar("SELECT region_name FROM `{$this->App->prefix()}region` WHERE region_id='{$city}' LIMIT 1");
					
					$district = $row['district'];
					$districtname = $this->App->findvar("SELECT region_name FROM `{$this->App->prefix()}region` WHERE region_id='{$district}' LIMIT 1");
					$row['address'] = $provincename.$cityname.$districtname.$row['address'];
			  		$str1 .= '<tr><td>['.$row['order_sn'].']</td><td>'.$row['goods_name'].'</td><td>'.$row['consignee'].'&nbsp;</td><td>'.$row['order_amount'].'&nbsp;</td><td>'.$number.'</td><td>['.$row['mobile'].']</td><td>'.$row['address'].'</td></tr>';
			  }
			  $str1 .='</table>';
		  }
		  echo $str1;
		   
	}
	
	function order_print(){
			$this->layout('kong');
			$ids = $_GET['ids'];
			$ids_rt = explode('-',$ids);
			if(empty($ids_rt)) die("非法错误，传送数据为空");
			//修改以打印状态
			$sql = "UPDATE `{$this->App->prefix()}goods_order_info` SET is_prints = '1' WHERE order_id IN(".implode(',',$ids_rt).")";
			$this->App->query($sql);
			
			 $sql = "SELECT distinct tb1.order_id, tb1.order_sn, tb1.shipping_name ,tb1.pay_name, tb1.add_time,tb1.consignee,u.user_name, tb1.goods_amount,tb1.order_amount,tb1.offprice,tb1.mobile,tb1.tel,tb1.email,tb1.best_time,tb1.postscript,tb1.shipping_fee,tb1.shipping_id,tb1.address FROM `{$this->App->prefix()}goods_order_info` AS tb1";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb2.order_id=tb1.order_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id=tb1.shop_id"; //便利店
			 $sql .=" WHERE tb1.order_id IN(".implode(',',$ids_rt).") ORDER BY tb1.add_time DESC";
			
			 $orderlist = $this->App->find($sql);
			 if(!empty($orderlist)){
				 foreach($orderlist as $k=>$row){
					$rt[$row['order_sn']][$k] = $row;
					
					$sql= "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb,is_gift,status,goods_unit,goods_number,from_jifen,buy_more_best FROM `{$this->App->prefix()}goods_order` WHERE order_id='$row[order_id]' ORDER BY rec_id DESC";
					$rt[$row['order_sn']][$k]['goods'] = $this->App->find($sql);
					
					$oid = $row['order_id'];
					//收货地址
					$sql = "SELECT tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district,tb5.region_name AS towns, tb6.region_name AS villages FROM `{$this->App->prefix()}goods_order_info` AS tb1";
					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";
					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";
					$sql .=" WHERE tb1.order_id ='$oid' LIMIT 1";
					$rt[$row['order_sn']][$k]['dizhi'] = $this->App->findrow($sql);
			
				 }
			 }
			 unset($orderlist);
			
			$sql = "SELECT site_name,company_url,custome_phone,custome_email FROM `{$this->App->prefix()}systemconfig` WHERE type = 'basic' LIMIT 1";
			$this->set('rts',$this->App->findrow($sql));
			
			$this->set('rt',$rt);
			$this->template('order_print');
	}
	
	//订单列表
	function order_list(){
             //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY `order_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }

            //条件
            $comd = array();
			
            if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }
            if(isset($_GET['order_sn'])&&!empty($_GET['order_sn']))
                    $comd[] = "order_sn LIKE '%".trim($_GET['order_sn'])."%'";
			/***********  look添加 日期查询 开始  ********************************/	
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
							
            if(isset($_GET['consignee'])&&!empty($_GET['consignee']))
                    $comd[] = "consignee LIKE '%".trim($_GET['consignee'])."%'";
			
			
            $w = ""; 
            if(!empty($comd)){
                $w = ' WHERE '.@implode(' AND ',$comd);
            }
            $list = 12;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` $w";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
        
            $sql = "SELECT order_id, order_sn,sn_id,shipping_id,shipping_id_true,is_view, user_id, order_status, shipping_status, pay_status, consignee, add_time,is_prints, SUM( order_amount + shipping_fee ) AS tprice FROM `{$this->App->prefix()}goods_order_info` $w GROUP BY order_id $orderby LIMIT $start,$list";
            $rt = $this->App->find($sql);
            $orderlist = array();
            if(!empty($rt)){
                foreach($rt as $row){
					//更新通知提醒
					$isview = $row['is_view'];
					if($isview=='0'){
						$this->App->update('goods_order_info',array('is_view'=>'1'),'order_id',$row['order_id']);
					}
					
					$uid = $row['user_id'];
					$shoppingname = '';
					$shipping_code = '';
					$sid = $row['shipping_id_true'];
					if($sid > 0){
						$srt = $this->App->findrow("SELECT shipping_name,shipping_code FROM `{$this->App->prefix()}shipping_name` WHERE shipping_id = '$sid' LIMIT 1");
						$shoppingname = isset($srt['shipping_name']) ? $srt['shipping_name'] : '';
						$shipping_code = isset($srt['shipping_code']) ? $srt['shipping_code'] : '';
					}
					if(empty($row['order_id'])||empty($row['order_sn'])) continue;
                    $orderlist[] = array(
                        'order_id'=>$row['order_id'],
                        'order_sn'=>$row['order_sn'],
                        'user_id'=>$row['user_id'],
                        'consignee'=>$row['consignee'],
						'sn_id'=>$row['sn_id'],
						'nickname'=>$this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1"),
						'shoppingname'=>$shoppingname,
						'shipping_code'=>$shipping_code,
						'shipping_id'=>$row['shipping_id'],
						'shipping_id_true'=>$row['shipping_id_true'],
                        'tprice'=>$row['tprice'],
                        'order_status'=>$row['order_status'],
                        'shipping_status'=>$row['shipping_status'],
                        'pay_status'=>$row['pay_status'],
						'is_prints'=>$row['is_prints'],
                        'add_time'=>(!empty($row['add_time'])? date('Y-m-d H:i:s',$row['add_time']) : '无知'),
                        'status'=>$this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status'])
                        );
                }
            }
            $this->set('orderlist',$orderlist);
			
			$shoppinglist = $this->App->find("SELECT * FROM `{$this->App->prefix()}shipping_name`");
			$this->set('shoppinglist',$shoppinglist);
					
			//选择模板
            $ty = isset($_GET['tt'])&&!empty($_GET['tt']) ? trim($_GET['tt']) : "";
			switch($ty){
				case 'delivery': //发货单列表
					$this->template('goods_order_delivery_list');
					break;
				case 'back': //退货单列表
					$this->template('goods_order_back_list');
					break;
				default:
					$this->template('goods_order_list');
					break;
			}
            
	}
	
	//订单详情信息
	function order_info($id=0){
            if(empty($id)) { $this->jump('goods_order.php?type=order_list',0,'订单ID为空！'); exit;}
			$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb5.user_name,tb4.region_name AS district,tb6.region_name AS town ,tb7.region_name AS village FROM `{$this->App->prefix()}goods_order_info` AS tb1"; // look添加tb5.user_name
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.town";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb7 ON tb7.region_id = tb1.village";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb5 ON tb5.user_id = tb1.shop_id"; // look添加
			$sql .=" WHERE tb1.order_id='$id'";
			$rt['orderinfo'] = $this->App->findrow($sql);
            if(empty($rt['orderinfo'])) { $this->jump('goods_order.php?type=order_list',0,'信息为空！'); exit;}
			$isview = $rt['orderinfo']['is_view'];
			if($isview=='0'){
				$this->App->update('goods_order_info',array('is_view'=>'1'),'order_id',$id);
			}
            $rt['orderinfo']['status'] = $this->get_status($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']);
			
            $sql = "SELECT tb1.*,tb2.goods_thumb,tb2.goods_unit,tb2.goods_brief,tb2.goods_number AS storage,tb2.goods_id, IFNULL(tb3.brand_name, '') AS brand_name FROM `{$this->App->prefix()}goods_order` AS tb1";  //look 添加
			$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id = tb2.goods_id";
			$sql .= " LEFT JOIN `{$this->App->prefix()}brand` AS tb3 ON tb2.brand_id = tb3.brand_id";
			$sql .= " WHERE tb1.order_id = '$id'";
			$rt['ordergoods']= $this->App->find($sql);
			$rt['order_action_button'] = $this->get_order_action_button($rt['orderinfo']['order_status'],$rt['orderinfo']['pay_status'],$rt['orderinfo']['shipping_status']); //返回订单操作按钮
			
			//订单操作信息
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_action` WHERE order_id='$id' ORDER BY log_time DESC";
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
			unset($rs);
            $this->template('goods_order_info');
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

        //选择订单的所在状态
        function select_statue($id=""){
            if(empty($id)) return "";
            switch ($id){
                case '-1':
                    return "";
                    break;
                case '11':
                    return "order_status='0'";
                    break;
                case '200':
                    return "order_status='2' AND shipping_status='0' AND pay_status='0'";
                    break;
                case '210':
                    return "order_status='2' AND shipping_status='0' AND pay_status='1'";
                    break;
				case '2x0'://代发货
                    return "order_status='2' AND shipping_status='0'";
                    break;
                case '214':
                    return "order_status='2' AND shipping_status='4' AND pay_status='1'";
                    break;
                case '1':
                    return "order_status='1'";
                    break;
                case '4':
                    return "order_status='4'";
                    break;
                case '3':
                    return "order_status='3'";
                    break;
                case '2':
                    return "pay_status='2'";
                    break;
				case '5'://退货申请单
                    return "order_status='6'";
                    break;
				case '6'://换货申请单
                    return "order_status='7'";
                    break;
				case '7'://退款申请单
                    return "order_status='5'";
                    break;
				case '222': //已发货
					return "shipping_status='2'";
					break;
                default :
                    return "";
                    break;
            }
        }

		//获取当前可以操作的按钮
		/*
		if(没确认){
		  =>确认、付款、取消、无效
			
		}else if(确认){
		  if(没支付){
			 =>支付、取消、无效
			 
		  }else if(已支付){
			 if(没发货){
			   =>发货、设为未付款、取消（设为没付款、没发货）
			 }else if(已发货){
			   =>未发货、已收货、退货
			 }else if(配货中){
			   =>设为没付款、取消
			 }else if(已收货){
			   =>退货
			 }     
		
		  }else if(退款){
			 if(已发货){
			   =>未发货、退货、
			 }else if(配货中){
			   =>未发货、退货、
			 }else if(已收货){
			   =>未发货、退货、
			 }     
		  }
		
		}else if(取消){
		  =>确认、移除
		}else if(退货){
		  =>确认
		}else if(无效){
		 =>确认、移除
		}
		*/
		function get_order_action_button($order_status=0,$pay_status=0,$shipping_status=0){
			$str = "";
			if($order_status==0){ 	//没确认(没付款、没发货)
				$str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
				$str .= '<input value="发货" class="order_action" type="button" id="202">'."\n";
				$str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
				$str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
				$str .= '<input value="无效" class="order_action" type="button" id="400">'."\n";
				
			}else if($order_status==2){   //已经确认
			    if($pay_status==0){ //没支付
					$s2 = '0';
					if($pay_status=='1') $s = '1'; else $s = '0';
					if($shipping_status=='0'){
						$str .= '<input value="发货" class="order_action" type="button" id="2'.$s.'2">'."\n";
					}elseif($shipping_status=='2'){//已经发货
						$s2 = '2';
						$str .= '<input value="收货" class="order_action" type="button" id="215">'."\n";
					}
					if($pay_status!='1'){
				    $str .= '<input value="付款" class="order_action" type="button" id="21'.$s2.'">'."\n";
					}
				    $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
				    $str .= '<input value="无效" class="order_action" type="button" id="400">'."\n";
				
				}else if($pay_status==1){ //已支付
					if($shipping_status==0){ //未发货
						 if($pay_status=='1') $s = '1'; else $s = '0';
					    	 $str .= '<input value="发货" class="order_action" type="button" id="2'.$s.'2">'."\n";
						 if($pay_status!='1'){
							 $str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
						 }
				   		 $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
				    	 $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
						 
					 }else if($shipping_status==2){ //已发货
					     $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
					     $str .= '<input value="设为未发货" class="order_action" type="button" id="210">'."\n";
						 if($pay_status!='1'){
						 	$str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
						 }
				   		 $str .= '<input value="收货" class="order_action" type="button" id="215">'."\n";
				    	 $str .= '<input value="退货" class="order_action" type="button" id="324">'."\n";
						 
					 }else if($shipping_status==1){ //配货中
					     $str .= '<input value="设为未支付" class="order_action" type="button" id="200">'."\n";
				   		 $str .= '<input value="取消" class="order_action" type="button" id="100">'."\n";
					 }else if($shipping_status==5){ //已收货
					     $str .= '<input value="退货" class="order_action" type="button" id="324">'."\n";
						 if($pay_status!='1'){
						 	$str .= '<input value="付款" class="order_action" type="button" id="210">'."\n";
						 }
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
			}else if($order_status==5){ //同意退款
			    $str .= '<input value="确认退款" class="order_action" type="button" id="720">'."\n";
			}
			else if($order_status==6){ //同意退货
			    $str .= '<input value="确认退货" class="order_action" type="button" id="340">'."\n";
			}
			else if($order_status==7){ //同意退货
			     $str .= '<input value="确认" class="order_action" type="button" id="200">'."\n";
				$str .= '<input value="移除" class="order_action" type="button" id="remove">'."\n";
			}
			return $str;
		}
		
		//ajax订单状态操作记录
		function ajax_op_status($data=array()){
			@set_time_limit(300); //最大运行时间
			if(strlen($data['opstatus'])!=3) {echo  "非法操作"; exit; }
			if(empty($data['opid'])){echo  "非法操作"; exit; }
			
 			$datas['order_status'] = substr($data['opstatus'],0,1);
			$datas['pay_status'] = substr($data['opstatus'],1,1);
			$datas['shipping_status'] = substr($data['opstatus'],-1);
			$order_id = $data['opid'];
			//当前购买用户
			$pu = $this->App->findrow("SELECT user_id,order_sn FROM `{$this->App->prefix()}goods_order_info` WHERE order_id = '$order_id' LIMIT 1");
			$uid = isset($pu['user_id']) ? $pu['user_id'] : 0; 
			$order_sn = isset($pu['order_sn']) ? $pu['order_sn'] : ''; 
				
			/*if($datas['shipping_status']=='5' || $datas['shipping_status']=='2'){ //收货，开通代理
				
				if($uid > 0){
					//标记当前用户所有下级为该代理会员
					//加入代理关系表
					$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");
					if($rank=='1'){
						$this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);
						
						//$this->action('user','update_user_tree',$uid,$uid);
						
						$this->action('user','update_daili_tree',$uid);//更新代理关系
					}
					
				}
			}*/
			
			//发货
			if($datas['shipping_status']=='2'){
				//查找物流单
				/*$oid = $data['opid'];
				$sql = "SELECT sn_id,shipping_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_id='$oid' LIMIT 1";
				$snidr = $this->App->findrow($sql);
				$sn_id = isset($snidr['sn_id']) ? $snidr['sn_id'] : '';
				$shipping_id = isset($snidr['shipping_id']) ? $snidr['shipping_id'] : 0;
				if(empty($sn_id)){
					if($shipping_id > 0){
						$sn = $this->App->findvar("SELECT shipping_sn FROM `{$this->App->prefix()}shipping_sn` WHERE shipping_id='$shipping_id' AND is_use='0' ORDER BY id ASC LIMIT 1");
						if(!empty($sn)){
							$this->App->update('shipping_sn',array('is_use'=>'1','usetime'=>mktime()),'shipping_sn',$sn);
							$datas['sn_id'] = $sn;
						}
					}
				}*/
				//发货通知
				$rr = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
				$pwecha_id = isset($rr['wecha_id']) ? $rr['wecha_id'] : '';
				$nickname = isset($rr['nickname']) ? $rr['nickname'] : '';
				if(!empty($pwecha_id) && !empty($nickname)){
					$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname,'order_sn'=>$order_sn),'fahuo');
				}
			}
			
			if($data['opstatus']=='720'){ //退款操作
				//已经返的佣金
				$sql = "SELECT * FROM  `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'";
				$rr = $this->App->find($sql);
				if(!empty($rr))foreach($rr as $item){
					$money = $item['money'];
					$uids = $item['uid']; //返佣用户
					$buyuid = $item['buyuid'];
					$cid = $item['cid'];
					$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`-$money,`mymoney` = `mymoney`-$money WHERE user_id = '$uids'";
					$this->App->query($sql);
					$money = -$money;
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$this->App->insert('user_money_change',array('buyuid'=>$buyuid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$money,'changedesc'=>'申请退款','time'=>mktime(),'uid'=>$uids));
				}
				//返佣缓存
				$sql = "SELECT * FROM  `{$this->App->prefix()}user_money_change_cache` WHERE order_sn='$order_sn'";
				$rr = $this->App->find($sql);
				if(!empty($rr))foreach($rr as $item){
					$money = $item['money'];
					$uids = $item['uid']; //返佣用户
					$buyuid = $item['buyuid'];
					$cid = $item['cid'];
					$money = -$money;
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$this->App->insert('user_money_change_cache',array('buyuid'=>$buyuid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$money,'changedesc'=>'申请退款','time'=>mktime(),'uid'=>$uids));
				}
				
				//积分
				$sql = "SELECT * FROM  `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'";
				$rr = $this->App->find($sql);
				if(!empty($rr))foreach($rr as $item){
					$points = $item['points'];
					$uids = $item['uid'];
					$cid = $item['cid'];
					$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`-$points,`points_ucount` = `points_ucount`-$points WHERE user_id = '$uids'";
					$this->App->query($sql);
					//$this->App->delete('user_point_change','cid',$cid);
					$points = -$points;
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'points'=>$points,'changedesc'=>'申请退款','time'=>mktime(),'uid'=>$uids));
				}
				
			}
			
			$this->App->update('goods_order_info',$datas,'order_id',$data['opid']); //更新状态
			$datas['order_id'] = $data['opid'];
			$datas['action_note'] = !empty($data['opremark']) ? $data['opremark'] : "---";
			$datas['log_time'] = mktime();
			$datas['action_user'] = $this->Session->read('adminname');
			$this->App->insert('goods_order_action',$datas);  //操作记录
			//查询操作
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_action` WHERE order_id='$data[opid]' ORDER BY log_time DESC";
			$rt = $this->App->find($sql);
			if(!empty($rt)){
				foreach($rt as $k=>$row){
				    $rt[$k] = $row;
					$rt[$k]['status']=  $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
					$rt[$k]['log_time'] = !empty($row['log_time']) ? date('Y-m-d H:i:s',$row['log_time']) : '无知';
					$os = $this->get_status($row['order_status'],'tt','tt');
					$rt[$k]['order_status'] = !empty($os) ? str_replace(',','',$os) : "";
					$ss = $this->get_status('tt','tt',$row['shipping_status']);
					$rt[$k]['shipping_status'] = !empty($ss) ? str_replace(',','',$ss) : "";
					$ps = $this->get_status('tt',$row['pay_status'],'tt');
					$rt[$k]['pay_status'] = !empty($ps) ? str_replace(',','',$ps) : "";;
				}
			}
			//邮件发送
			
			//支付
			//返佣金
			if($datas['pay_status']=='1'){
				$order_id = $data['opid'];
				$order_sn = $this->App->findvar("SELECT order_sn FROM `{$this->App->prefix()}goods_order_info` WHERE order_id = '$order_id' LIMIT 1");
				$this->pay_successs_tatus2($order_sn);
			}
			
			$this->set('action_info',$rt);
			$this->fetch('goods_order_action_ajax');
	}
	
	function format_price($price=0){
		if(empty($price)) return '0.00';
		return number_format($price, 2, '.', '');
	}
	
	function _firtuids($uid=0){
		$ut = array();
		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
		$uids = $this->App->findcol($sql);
		if(!empty($uids))foreach($uids as $uid){
			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
			if($ur!='1'){
				$ut[] = $uid;
			}else{
					/********************第二次*************************/
						$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
						$uids = $this->App->findcol($sql);
						if(!empty($uids))foreach($uids as $uid){
							$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
							if($ur!='1'){
								$ut[] = $uid;
							}else{
									/********************第三次*************************/
										$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
										$uids = $this->App->findcol($sql);
										if(!empty($uids))foreach($uids as $uid){
											$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
											if($ur!='1'){
												$ut[] = $uid;
											}else{
													/********************第四次*************************/
														$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
														$uids = $this->App->findcol($sql);
														if(!empty($uids))foreach($uids as $uid){
															$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
															if($ur!='1'){
																$ut[] = $uid;
															}else{
																	/********************第五次*************************/
																		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																		$uids = $this->App->findcol($sql);
																		if(!empty($uids))foreach($uids as $uid){
																			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																			if($ur!='1'){
																				$ut[] = $uid;
																			}else{
																					/********************第六次*************************/
																							$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																							$uids = $this->App->findcol($sql);
																							if(!empty($uids))foreach($uids as $uid){
																								$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																								if($ur!='1'){
																									$ut[] = $uid;
																								}else{
																										/********************第七次*************************/
																											$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																											$uids = $this->App->findcol($sql);
																											if(!empty($uids))foreach($uids as $uid){
																												$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																												if($ur!='1'){
																													$ut[] = $uid;
																												}else{
																														/********************第八次*************************/
																															$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																															$uids = $this->App->findcol($sql);
																															if(!empty($uids))foreach($uids as $uid){
																																$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																																if($ur!='1'){
																																	$ut[] = $uid;
																																}else{
																																	break;
																																}
																																
																															}
																														/********************************************/
																												}
																												
																											}
																										/********************************************/
																								}
																								
																							}
																					/********************************************/
																			}
																			
																		}
																	/********************************************/
															}
															
														}
													/********************************************/
											}
											
										}
									/********************************************/
							}
							
						}
					/********************************************/
			}
			
		}
		
		return $ut;
	} //end function
	
	function update_daili_tree($uid=0){
		if($uid>0){
				$dd = array();
				$dd['uid'] = $uid;
				$dd['p1_uid'] = 0;
				$dd['p2_uid'] = 0;
				$dd['p3_uid'] = 0;
				
				$p1_uid = $this->return_daili_uid($uid); //最近分销
			
				$firtuids = array();
				if($p1_uid > 0 ){
					$dd['p1_uid'] = $p1_uid;
					$p2_uid = $this->return_daili_uid($p1_uid);
					
					if($p2_uid > 0 ){
						$dd['p2_uid'] = $p2_uid;
						$p3_uid = $this->return_daili_uid($p2_uid);
						
						if($p3_uid > 0 ){
							$dd['p3_uid'] = $p3_uid;
							/*$p4_uid = $this->return_daili_uid($p3_uid);
							if($p4_uid > 0){
								$dd['p4_uid'] = $p4_uid;
							}*/
						}
					}
				}
				
				//
				$sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
				$id = $this->App->findvar($sql);
				
				if($id > 0){
					$this->App->update('user_tuijian_fx',$dd,'id',$id);
				}else{
					$this->App->insert('user_tuijian_fx',$dd);
				}
			
			//
			$firtuids = $this->_firtuids($uid); //当前开通用户的最近一层分销用户
			
			$aup = array();
			if(!empty($firtuids))foreach($firtuids as $u){ //
				$dds = array();
				$dds['uid'] = $u;
				$dds['p1_uid'] = $uid;
				$dds['p2_uid'] = $dd['p1_uid'];
				$dds['p3_uid'] = $dd['p2_uid'];
				
				$aup[] = $dds;
				
				$firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$u'");
				if(!empty($firtuids2))foreach($firtuids2 as $uu){ //
				
					$dds = array();
					$dds['uid'] = $uu;
					$dds['p1_uid'] = $u;
					$dds['p2_uid'] = $uid;
					$dds['p3_uid'] = $dd['p1_uid'];
					
					$aup[] = $dds;
					
					$firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$uu'");
					if(!empty($firtuids3))foreach($firtuids3 as $uuu){ //
						
						$dds = array();
						$dds['uid'] = $uuu;
						$dds['p1_uid'] = $uu;
						$dds['p2_uid'] = $u;
						$dds['p3_uid'] = $uid;
						
						$aup[] = $dds;
						
					}//end foreach
					unset($firtuids3);
				} //end foreach
				unset($firtuids2);
			} //end foreach
			unset($firtuids);
			
			if(!empty($aup))foreach($aup as $up){
				$this->App->update('user_tuijian_fx',$up,'uid',$up['uid']);
			}
			unset($aup);
		}
	}
	
	function return_daili_uid($uid=0,$k=0){
		if(!($uid > 0)){
			return 0;
		}
		$puid = 0;
		if($k<20){
				$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
				$puid = $this->App->findvar($sql);
				if($puid > 0){
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$puid' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if($rank == '1'){
						++$k;
						$this->return_daili_uid($puid,$k);
					}else{
						return $puid;
					}
				}else{
					return 0;
				}
		}
		return $puid;
	}
	
	//收款后返佣
	function pay_successs_tatus2($order_sn=''){
		set_time_limit(300); //最大运行时间
				
		//上三级返佣金
		if(empty($order_sn))exit;
		$pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		$tt = "false";
		//if($pay_status!='1'){
			//检查
			$sql = "SELECT cid FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'"; //资金
			$cid = $this->App->findvar($sql);
			if($cid > 0){
				return false;
				exit;
			}else{
				$sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'"; //积分
				$cid = $this->App->findvar($sql);
				if($cid > 0){
					return false;
					exit;
				}else{
					$tt = "true";
				}
			}
		//}
			
		if($tt == 'true' && !empty($order_sn)){
			$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
			$parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
			$parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0; //分享者
			$parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0; //分享者
			$parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0; //分享者
			
			$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
			$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
			$order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
			$uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
			$pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
			
			$order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
		
			//改变销量
			$sql = "SELECT goods_id,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id = '$order_id'";
			$rd = $this->App->find($sql);
			if(!empty($rd))foreach($rd as $rdd){
				$gid = $rdd['goods_id'];
				$number = $rdd['goods_number'];
				$sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+'$number' , `goods_number` = `goods_number`- '$number' WHERE goods_id = '$gid' LIMIT 1";
				$this->App->query($sql);
			}
			unset($rd);
			
			$ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			$nickname = $ni['nickname'];
			
			//自动升级
			$sql = "SELECT lid,money FROM `{$this->App->prefix()}user_level` ORDER BY money DESC";
			$sj = $this->App->find($sql);
			$zmo = $this->App->findvar("SELECT order_amount FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='1' LIMIT 1");
			$ra = $ni['user_rank'];
			$raa = $ra;
			if(!empty($sj))foreach($sj as $item){
				$mo = $item['money'];
				$lid = $item['lid'];
				if($zmo >= $mo && $mo > 0){
					if($ra=='1'){ //普通会员升级为分销
						$ra = $lid;
						$this->App->update('user',array('user_rank'=>$lid),'user_id',$uid);
					}elseif($ra=='12' && $lid != '1' && $lid < 12){ 
						$ra = $lid;
						$this->App->update('user',array('user_rank'=>$lid),'user_id',$uid);
					}elseif($ra=='11' && $lid != '1' && $lid < 11){ 
						$ra = $lid;
						$this->App->update('user',array('user_rank'=>$lid),'user_id',$uid);
					}
				}
				
			}
			unset($ni,$sj);
			$newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			if($raa=='1' && $newrank !='1'){//变更升级
				$this->update_daili_tree($uid);//更新代理关系
			}
			
			//配置信息
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1";
			$rts = $this->App->findrow($sql);
			$openfx_minmoney = empty($rts['openfx_minmoney']) ? 0 : intval($rts['openfx_minmoney']);
			
			if($rts['openfxbuy']=='1' && $zmo >= $openfx_minmoney){ 
				//付款开通代理  兼容旧版
				if($uid > 0){
					//$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");
					if($newrank=='1'){
						$this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);
						
						$this->update_daili_tree($uid);//更新代理关系
					}
				}
			}
			
			$thismonth = date('Y-m-d',mktime());
			$thism = date('Y-m',mktime());

			//计算资金，便于下面返佣
			//计算每个产品的佣金
			$sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
			$moneys = $this->App->find($sql);
			
			//购物者根据消费金额送积分
			$pointnum =  $rts['pointnum'];
			$pointnum_ag =  isset($rts['pointnum_ag'])&&!empty($rts['pointnum_ag']) ? $this->format_price($rts['pointnum_ag']) : 1;
			$points = intval($order_amount * $pointnum * $pointnum_ag);
			if($points > 0 ){
					//购买者送积分
					$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$uid'";
					$this->App->query($sql);
					$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points,'changedesc'=>'消费返积分','time'=>mktime(),'uid'=>$uid));
			}
			
			$moeysall = 0;
			if(!empty($moneys))foreach($moneys as $row){
				if($row['takemoney1'] > 0){
					$moeysall +=$row['takemoney1'] * $row['goods_number'];
				}
			}
			
			//购买者返佣
			$moeys = 0;
			$ticheng360_1 =  $rts['ticheng360_1'];
			if($ticheng360_1 > 0 ){
					$off = $ticheng360_1/100;
					$moeys = $this->format_price($moeysall * $off);
					if($moeys > 0){
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";
						$this->App->query($sql);
						$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'消费返佣金','time'=>mktime(),'uid'=>$uid,'level'=>'10'));
					}
			}
			
			$record = array();
			$moeys = 0;
				
			//一级返佣金
			if($parent_uid > 0){ 
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0){
								$off = $rts['ticheng180_1']/100;
							}
						}elseif($rank=='11'){//高级分销商
							if($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0){
								$off = $rts['ticheng180_h1_1']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0){
								$off = $rts['ticheng180_h2_1']/100;
							}
						}
						
					//}

					
					$moeys = $this->format_price($moeysall * $off);
					if(!empty($moeys)){
						$record['puid1_money'] = $moeys;
						$record['p_uid1'] = $parent_uid;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid'";
						$this->App->query($sql);
						$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid,'level'=>'1'));
						
						//返一级积分
						$tjpointnum =  isset($rts['tjpointnum']) ? $rts['tjpointnum'] : 0;
						$tjpointnum_ag =  isset($rts['tjpointnum_ag'])&&!empty($rts['tjpointnum_ag']) ? $this->format_price($rts['tjpointnum_ag']) : 1;
						$points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);
						if($points2 > 0){
							//送积分
							$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid'";
							$this->App->query($sql);
							$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points2,'changedesc'=>'客户消费返积分','time'=>mktime(),'uid'=>$parent_uid));
						}
					}
				}
			}
			
			$moeys = 0;
			//二级返佣金
			if($parent_uid2 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0){
								$off = $rts['ticheng180_2']/100;
							}
						}elseif($rank=='11'){//高级分销商
							if($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0){
								$off = $rts['ticheng180_h1_2']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0){
								$off = $rts['ticheng180_h2_2']/100;
							}
						}
						
					//}
					$moeys = $this->format_price($moeysall * $off);
					if(!empty($moeys)){
						$record['puid2_money'] = $moeys;
						$record['p_uid2'] = $parent_uid2;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid2'";
						$this->App->query($sql);
						$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid2,'level'=>'2'));
						
						//返二级积分
						$tjpointnum =  isset($rts['tjpointnum2']) ? $rts['tjpointnum2'] : 0;
						$tjpointnum_ag =  isset($rts['tjpointnum_ag2'])&&!empty($rts['tjpointnum_ag2']) ? $this->format_price($rts['tjpointnum_ag2']) : 1;
						$points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);
						if($points2 > 0){
							//送积分
							$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid2'";
							$this->App->query($sql);
							$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points2,'changedesc'=>'二级客户消费返积分','time'=>mktime(),'uid'=>$parent_uid2));
						}
					}
				}
			}
			
			$moeys = 0;
			//三级返佣金
			if($parent_uid3 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0){
								$off = $rts['ticheng180_3']/100;
							}
						}elseif($rank=='11'){//高级分销商
							if($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0){
								$off = $rts['ticheng180_h1_3']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0){
								$off = $rts['ticheng180_h2_3']/100;
							}
						}
						
					//}
					$moeys = $this->format_price($moeysall * $off);
					if(!empty($moeys)){
						$record['puid3_money'] = $moeys;
						$record['p_uid3'] = $parent_uid3;
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid3'";
						$this->App->query($sql);
						$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid3,'level'=>'3'));
						
						//返三级积分
						$tjpointnum =  isset($rts['tjpointnum3']) ? $rts['tjpointnum3'] : 0;
						$tjpointnum_ag =  isset($rts['tjpointnum_ag3'])&&!empty($rts['tjpointnum_ag3']) ? $this->format_price($rts['tjpointnum_ag3']) : 1;
						$points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);
						if($points2 > 0){
							//送积分
							$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid3'";
							$this->App->query($sql);
							$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points2,'changedesc'=>'三级客户消费返积分','time'=>mktime(),'uid'=>$parent_uid3));
						}
					}
				}
			}
			
			$moeys = 0;
			//四级返佣金
			if($parent_uid4 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0){
								$off = $rts['ticheng180_4']/100;
							}
						}elseif($rank=='11'){//高级分销商
							if($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0){
								$off = $rts['ticheng180_h1_4']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0){
								$off = $rts['ticheng180_h2_4']/100;
							}
						}
						
					//}
					$moeys = $this->format_price($moeysall * $off);
					if(!empty($moeys)){
						$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid4'";
						$this->App->query($sql);
						$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid4,'level'=>'4'));
						
					}
				}
			}
			
			if(!empty($record)){
				$record['oid'] = $order_id;
				$record['uid'] = $uid;
				$record['date_y'] = date('Y',mktime());
				$record['date_m'] = date('Y-m',mktime());
				$record['date_d'] = date('Y-m-d',mktime());
				$this->App->insert('user_money_record',$record);
			}
			
		}//end if
	}//end function
	
	
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
		
			//支付方式
		function paymentlist(){
			//删除
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				if($id>0){
				$this->App->delete('payment','pay_id',$id);
				}
				
			$rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}payment`");
			$this->set('rt',$rt);
			 $this->template('paymentlist');
		}
		
		function paymentinfo($id=0){
			$data = array();
			if(!empty($_POST)){
				$data['pay_name'] = $_POST['pay_name'];
				$data['pay_desc'] = $_POST['pay_desc'];
				$data['pay_fee'] = $_POST['pay_fee'];
				$data['pay_config'] = serialize(array('pay_no'=>(isset($_POST['pay_no'])?$_POST['pay_no']:""),'pay_code'=>(isset($_POST['pay_code'])?$_POST['pay_code']:""),'pay_idt'=>(isset($_POST['pay_idt'])?$_POST['pay_idt']:"")));
			
			}
			if($id>0){
				if(isset($data)&&!empty($data)){
					
					if($this->App->update('payment',$data,'pay_id',$id)){
						$this->action('system','add_admin_log','更新支付方式：'.$data['pay_name']);
						$this->action('common','showdiv',$this->getthisurl());
					}else{
						$this->jump('payment.php?type=info&id='.$id,0,'更新失败！'); exit;
					}
				}
				$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}payment` WHERE pay_id='$id' LIMIT 1");
				$pay_config = unserialize($rt['pay_config']);
				$rt['pay_no'] = $pay_config['pay_no'];
				$rt['pay_code'] = $pay_config['pay_code'];
				$rt['pay_idt'] = $pay_config['pay_idt'];
				$type = 'edit';
			}else{
				if(!empty($data)){
					
					if($this->App->insert('payment',$data)){
						$this->action('system','add_admin_log','添加支付方式：'.$data['pay_name']);
						$this->action('common','showdiv',$this->getthisurl());
					}else{
						$this->jump('payment.php?type=info',0,'添加失败！'); exit;
					}
				}
				$type = 'add';
			}
			$this->set('type',$type);
			$this->set('rt',$rt);
			if($id=='4'){
				$this->template('paymentinfowx');
			}else{
				$this->template('paymentinfo');
			}
		}
		
		function ajax_pay_activeop($data=array()){
			if(empty($data['id'])) die("非法操作，ID为空！");
			$type = $data['type']; 
			$sdata = array();
			$sdata['enabled']= $data['active'];
			$this->App->update('payment',$sdata,'pay_id',$data['id']);
			unset($data,$sdata);
		}
		
		//配送方式方式
		function deliverylist(){
			//删除
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				if($id>0){
				$this->App->delete('shipping','shipping_id',$id);
				}
				
			$rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}shipping`");
			$this->set('rt',$rt);
			 $this->template('deliverylist');
		}
		//配送方式信息
		function deliveryinfo($id=0){
			if($id>0){
				$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$id' LIMIT 1");
				if(isset($_POST)&&!empty($_POST)){
					if($this->App->update('shipping',$_POST,'shipping_id',$id)){
						$this->jump('delivery.php?type=info&id='.$id,0,'更新成功！'); exit;
					}else{
						$this->jump('delivery.php?type=info&id='.$id,0,'更新失败！'); exit;
					}
				}
				$type = 'edit';
			}else{
				if(isset($_POST)&&!empty($_POST)){
					if($this->App->insert('shipping',$_POST)){
						$this->jump('delivery.php?type=info',0,'添加成功！'); exit;
					}else{
						$this->jump('delivery.php?type=info',0,'添加失败！'); exit;
					}
				}
				$type = 'add';
			}
			$this->set('type',$type);
			$this->set('rt',$rt);
			 $this->template('deliveryinfo');
		}
		
		//配送区域列表
		function delivery_area_list($id=0){
			if(!empty($_GET['delid'])&&intval($_GET['delid'])>0){
				$this->App->delete('shipping_area','shipping_area_id',intval($_GET['delid']));
			}
			$rt = array();
			if($id>0){
				$sql = "SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_id='$id'";
				$rs = $this->App->find($sql);
				if(!empty($rs)){
					foreach($rs as $row){
							$areaid = !empty($row['configure']) ? json_decode($row['configure']) : array();
							if(!empty($areaid)){
								$row['configure'] = $this->App->findcol("SELECT region_name FROM `{$this->App->prefix()}region` WHERE region_id IN (".@implode(',',$areaid).") ORDER BY region_id ASC");
							}
							$rt[] = $row;
					}
					unset($rs);
				}
			}

			$this->set('rt',$rt);
		 	$this->template('delivery_area_list');
		}
		
/***************  look添加 开始    *************************************************************************/		
//产品总销量
	function product_order_list(){
             //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY `order_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }

            //条件
			/*	$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id";*/
			// 查询供应商
			$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1  WHERE tb1.user_rank='10' ORDER BY tb1.user_id";
		$this->set('uidlist',$this->App->find($sql));
			
			
			//查询配送店
			$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` As tb1 WHERE tb1.user_rank = '12' ORDER BY tb1.user_id ";
			$this->set('pslist',$this->App->find($sql));
			
			
			
			
            $comd = array();
			/*if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }*/
		
            if(isset($_GET['status'])&&!empty($_GET['status'])){
                    $st = $this->select_statue($_GET['status']);
                    !empty($st)? $comd[] = $st : "";
            }
			
			/***********  look添加 日期查询 开始  ********************************/	
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
							
			/***********  look添加 日期查询 结束  ********************************/	
			
			
			
            if(isset($_GET['order_sn'])&&!empty($_GET['order_sn']))
                    $comd[] = "order_sn LIKE '%".trim($_GET['order_sn'])."%'";
            if(isset($_GET['consignee'])&&!empty($_GET['consignee']))
                    $comd[] = "consignee LIKE '%".trim($_GET['consignee'])."%'";
			if(isset($_GET['psid'])&&!empty($_GET['psid'])&&$_GET['psid']=='-1'){
					$comd[] = " shipping_id = '4' ";
			}		
			if(isset($_GET['psid'])&&!empty($_GET['psid'])&&$_GET['psid']!='-1'){
                    $comd[] = " shop_id = $_GET[psid] ";	
					}
			
            $w = ""; 
            if(!empty($comd)){
                $w = ' WHERE '.@implode(' AND ',$comd);
            }
			
		//	print_r ($w);
		//	exit;
		//	print_r ($comd);
			
		//	exit;
			
            $list = 12;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` $w";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
          /*  $sql = "SELECT order_id, order_sn, user_id, order_status, shipping_status, pay_status, consignee, add_time, SUM( goods_amount + shipping_fee ) AS tprice FROM `{$this->App->prefix()}goods_order_info` $w GROUP BY order_id $orderby LIMIT $start,$list";
		  
		    $sql = "SELECT tb1.*,tb2.goods_thumb,tb2.goods_number AS storage,tb2.goods_id, IFNULL(tb3.brand_name, '') AS brand_name FROM `{$this->App->prefix()}goods_order` AS tb1";
			$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id = tb2.goods_id";
			$sql .= " LEFT JOIN `{$this->App->prefix()}brand` AS tb3 ON tb2.brand_id = tb3.brand_id";
			$sql .= " WHERE tb1.order_id = '$id'";
		  */
		  $sql = "SELECT order_id  FROM `{$this->App->prefix()}goods_order_info` $w GROUP BY order_id $orderby ";  //取得订单表id
		
		/*************************  能获取到供应商商品的订单  ****************************************
		
		$sql = "SELECT tb1.order_id , tb2.goods_id ,tb3.uid ,tb4.user_name  FROM `{$this->App->prefix()}goods_order_info` AS tb1"; 
		$sql .= " LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id = tb2.order_id";
		$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb2.goods_id = tb3.goods_id";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb3.uid = tb4.user_id";
		$sql .= " $w GROUP BY tb1.order_id $orderby";
		
		************************************************************************************************/
		
        $rt = $this->App->find($sql);
		
		
		//开始截取订单表id的值  用	‘，’分开；
	//	echo mysql_error();
	//	exit;
		
	//	print_r($rt);
	//	exit;
		
	//	$sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_id = $_GET[psid] ";
	//	$rs = $this->App->find($sql);
	//	echo $_GET['order_sn'];
		
	//	echo $_GET['psid'];
	//	exit;
	//	print_r($rs);
		
			$str = '';
			foreach($rt as $value){
				$str .= $value['order_id'].',';
									}
			$goods_id = rtrim($str,',');
			
		/*	$user_id = ' ' ;
			foreach($comd as $value){
				$user_id .= $value['tb3.uid'].',';
									}
			print_r ($user_id);*/
			
			
			
			if(isset($_GET['uid'])&&intval($_GET['uid'])>0)
			$user_id = 'and tb3.user_id ='. intval($_GET['uid']). ' '; // 组装 多个where条件 
		//	print_r ($user_id);
			
		//开始查询 订购各个商品的总数	
			$sql = "SELECT tb1.goods_bianhao,tb1.goods_name, tb1.goods_id,sum(tb1.goods_number) as numb,tb2.uid,tb2.market_price , tb2.pifa_price , tb3.user_name,tb3.user_id  FROM `{$this->App->prefix()}goods_order` AS tb1";
			$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id";
			$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid";
			
			$sql .= " WHERE order_id in ($goods_id) $user_id GROUP BY goods_id order by tb2.uid LIMIT $start,$list";
			$rt = $this->App->find($sql);
	//	echo mysql_error ();
            $orderlist = array();
            if(!empty($rt)){
                foreach($rt as $row){
				//	if(empty($row['order_id'])||empty($row['order_sn'])) continue;
                    $orderlist[] = array(
						'goods_bianhao'=>$row['goods_bianhao'],
                        'goods_name'=>$row['goods_name'],
						'numb'=>$row['numb'],
						'uid'=>$row['uid'],
						'user_id'=>$row['user_id'],
						'user_name'=>$row['user_name'],
						'market_price'=>$row['market_price'],
						'pifa_price'=>$row['pifa_price'],
						'goods_id'=>$row['goods_id']
						/*
						'order_id'=>$row['order_id'],
                        'order_sn'=>$row['order_sn'],
                        'user_id'=>$row['user_id'],
                        'consignee'=>$row['consignee'],
                        'tprice'=>$row['tprice'],
                        'order_status'=>$row['order_status'],
                        'shipping_status'=>$row['shipping_status'],
                        'pay_status'=>$row['pay_status'],
                        'add_time'=>(!empty($row['add_time'])? date('Y-m-d H：i:s',$row['add_time']) : '无知'),
                        'status'=>$this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status'])*/
                        );
                }
            }
			
            $this->set('orderlist',$orderlist);

			
			//选择模板
            $ty = isset($_GET['tt'])&&!empty($_GET['tt']) ? trim($_GET['tt']) : "";
			switch($ty){
				case 'delivery': //发货单列表
					$this->template('goods_order_delivery_list');
					break;
				case 'back': //退货单列表
					$this->template('goods_order_back_list');
					break;
				default:
					$this->template('product_order_list');
					break;
			}
            
	}

/***************  look添加 结束    *************************************************************************/		
		
		//配送区域信息
		function delivery_area_info($cid=0,$id=0){
			if($id>0){
				if(!empty($_POST['regions'])){
						   $data['shipping_id'] = $cid;
							$data['shipping_area_name'] = $_POST['shipping_area_name'];
							$data['shipping_desc'] = $_POST['shipping_desc'];
							$data['item_fee'] = $_POST['item_fee'];
							$data['step_item_fee'] = $_POST['step_item_fee'];
							$data['weight_fee'] = $_POST['weight_fee'];
							$data['step_weight_fee'] = $_POST['step_weight_fee'];
							$data['max_money'] = $_POST['max_money'];
							$data['configure'] = json_encode($_POST['regions']);
							$data['type'] = $_POST['fee_compute_mode'];
							if($this->App->update('shipping_area',$data,'shipping_area_id',$id)){
								$this->jump('',0,'修改成功！'); exit;
							}else{
								$this->jump('',0,'修改失败！'); exit;
							}
						 } //end if
						$type = 'edit';
						$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_area_id='$id'");
						if(empty($rt)){
						$this->jump('delivery.php?type=arealist&id='.$cid);
						}
						$areaid = !empty($rt['configure']) ? json_decode($rt['configure']) : array();
						if(!empty($areaid)){
						$rt['configure'] = $this->App->find("SELECT * FROM `{$this->App->prefix()}region` WHERE region_id IN (".implode(',',$areaid).") ORDER BY region_id ASC");
						
						}
			
			}else{
				if(!empty($_POST)){ 
					if(!empty($_POST['shipping_area_name'])){
                                            	if(!empty($_POST['regions'])){
						   $data['shipping_id'] = $cid;
                                                    $data['shipping_area_name'] = $_POST['shipping_area_name'];
                                                    $data['shipping_desc'] = $_POST['shipping_desc'];
                                                    $data['item_fee'] = $_POST['item_fee'];
                                                    $data['step_item_fee'] = $_POST['step_item_fee'];
                                                    $data['weight_fee'] = $_POST['weight_fee'];
                                                    $data['step_weight_fee'] = $_POST['step_weight_fee'];
                                                    $data['max_money'] = $_POST['max_money'];
                                                    $data['configure'] = json_encode($_POST['regions']);
                                                    $data['type'] = $_POST['fee_compute_mode'];
                                                    if($this->App->insert('shipping_area',$data)){
                                                        $this->jump('',0,'添加成功！'); exit;
                                                    }
                                                 } //end if
                                            
					}else{
						echo '<script> alert("配送方式不能为空！");</script>';
					}
				}
				$type = 'add';
			}
			$this->set('type',$type);
			$this->set('rt',$rt);
			 $this->template('delivery_area_info');
		}
		
		
		//导入订单数据
		
		function order_import(){
			  header("Content-Type:text/html;charset=utf-8");
			  header("Content-type:application/vnd.ms-excel");
			  header("Cache-Control: no-cache");
			  header("Pragma: no-cache");
			  header("Content-Disposition:filename=facebook_".date('Y-m-d',time()).".xls");
		  

			  if(!empty($rt)){ 
			  $data=array();
			  $str1  ='<table border="1" cellspacing="0" cellpadding="0">';
			  $str1 .='<tr><td>&nbsp;</td><th colspan="3" style="background:#ccc; border:1px solod #000; height:40px;font-size:20px">Facebook Data</th></tr>';
			   $data[]='<tr><td>&nbsp;</td><th width="120">Date</th><th width="100">Total Pages</th><th width="100">Total Fans</th></tr>';
				foreach($rt as $k=>$row){	
				   $data[]='<tr><td>&nbsp;</td><td width="120">'.$row["date"].'</td><td width="100">'.$row["total_role"].'</td><td width="100">'.$row["totalfans"].'</td></tr>';
				} //end foreach
			  $str1 .=implode(' ',$data);
			  $str1 .='</table>';
			  } // end if 
		}
	
		#################订单提醒###################
		function ajax_checkorder(){
			$last_check = mktime()-900;
			/* 新订单 */
			$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE is_view='0' AND add_time >= '$last_check' LIMIT 1";
			$new_orders = $this->App->findvar($sql);

			if ($new_orders>0)
			{
				echo '1';exit;
			}
			else
			{
				echo '0';exit;
			}
		}
}
?>