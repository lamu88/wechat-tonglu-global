<?php

class GroupbuyController extends Controller{

 	function  __construct() {
           $this->css('content.css');
	}
	
	//团购列表
    function index(){
	   //分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			   $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(group_id) FROM `{$this->App->prefix()}goods_groupbuy`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.is_delete,tb2.is_on_sale FROM `{$this->App->prefix()}goods_groupbuy` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id ORDER BY tb1.group_id DESC LIMIT $start,$list";
		
		$this->set('rt',$this->App->find($sql));
		$this->template('groupbuy_index');
	}
	
	/*添加及编辑团购信息
	 @$id:大于0时，为编辑页面，否则为添加团购
	*/
	function groupinfo($id=0){ 
                $this->js(array('time/WdatePicker.js',"edit/kindeditor.js"));
                $rt = array();
				$_GET['cat_id'] = 0;
				$_GET['brand_id'] = 0;
                if($id>0){ //编辑
						 if(!empty($_POST)){
						 		//$_POST['start_time'] = strtotime($_POST['start_time']);
                               // $_POST['end_time'] = strtotime($_POST['end_time']);
								
								$number = $_POST['number'];
								$price = $_POST['price'];
								
								$dd = array();
								if(!empty($number)){
									foreach($number as $k=>$val){
										if(!($val>0) || !($price[$k]>0)) continue;
										$dd[] = array('number'=>$val,'price'=>$price[$k],'group_id'=>$id);
									}
								}
								
								$data['price'] = $_POST['prices'];
                                $data['group_name'] = $_POST['group_name'];
								
								if(isset($_POST['start_time'])&&!empty($_POST['start_time'])){
									$_POST['start_time'] =  strtotime($_POST['start_time'].' '.$_POST['xiaoshi_start'].':'.$_POST['fen_start'].':'.$_POST['miao_start']);
								}
								if(isset($_POST['end_time'])&&!empty($_POST['end_time'])){
									 $_POST['end_time'] =  strtotime($_POST['end_time'].' '.$_POST['xiaoshi_end'].':'.$_POST['fen_end'].':'.$_POST['miao_end']);
								}
								unset($_POST['xiaoshi_start'],$_POST['fen_start'],$_POST['miao_start']);
								unset($_POST['xiaoshi_end'],$_POST['fen_end'],$_POST['miao_end']);
				
                                $data['start_time'] = $_POST['start_time'];
                                $data['end_time'] = $_POST['end_time'];
                                $data['points'] = $_POST['points'] > 0 ? intval($_POST['points']) : 0;
                                $data['goods_id'] = $_POST['goods_id'];
                                $data['desc'] = $_POST['desc'];
								$data['qingdan'] = $_POST['qingdan'];
                                $data['active'] = $_POST['active']==1 ? $_POST['active'] : 0; //print_r($_POST); exit;
                                if($this->App->update('goods_groupbuy',$data,'group_id',$id)){
										if(!empty($dd)){
											foreach($dd as $row){
												$this->App->insert('goods_groupbuy_price',$row);
											}
										}
										unset($data,$dd);
                                        $this->jump('',0,'更新成功！'); exit;
                                }else{
                                        $this->jump('',0,'更新失败！'); exit;
                                }
						 
						 }
                        $sql = "SELECT tb1.*,tb2.goods_name,tb2.cat_id,tb2.brand_id,tb2.is_delete,tb2.is_on_sale FROM `{$this->App->prefix()}goods_groupbuy` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id = tb2.goods_id WHERE tb1.group_id='$id' LIMIT 1";
                        $rt = $this->App->findrow($sql);
						if(empty($rt)){
							$this->jump('groupbuy.php?type=list'); exit;
						}
						$_GET['cat_id'] = $rt['cat_id'];
						$_GET['brand_id'] = $rt['brand_id'];
						
                        $sql = "SELECT tb2.* FROM `{$this->App->prefix()}goods_groupbuy` AS tb1";
                        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_groupbuy_price` AS tb2 ON tb1.group_id = tb2.group_id";
                        $sql .= " WHERE tb1.group_id = '$id'";
                        $rt['groupgoods'] = $this->App->find($sql);
                }else{ //添加
                        if(!empty($_POST)){
                               // $_POST['start_time'] = strtotime($_POST['start_time']);
                                //$_POST['end_time'] = strtotime($_POST['end_time']);
								
								$number = $_POST['number'];
								$price = $_POST['price'];
								
								$dd = array();
								if(!empty($number)){
									foreach($number as $k=>$val){
										if(!($val>0) || !($price[$k]>0)) continue;
										$dd[] = array('number'=>$val,'price'=>$price[$k]);
									}
								}
								
								if(empty($dd)){
									$this->jump('',0,'请先输入价格等级！'); exit;
								}
								
								$data['price'] = $_POST['prices'];
                                $data['group_name'] = $_POST['group_name'];
								
								if(isset($_POST['start_time'])&&!empty($_POST['start_time'])){
									$_POST['start_time'] =  strtotime($_POST['start_time'].' '.$_POST['xiaoshi_start'].':'.$_POST['fen_start'].':'.$_POST['miao_start']);
								}
								if(isset($_POST['end_time'])&&!empty($_POST['end_time'])){
									 $_POST['end_time'] =  strtotime($_POST['end_time'].' '.$_POST['xiaoshi_end'].':'.$_POST['fen_end'].':'.$_POST['miao_end']);
								}
								unset($_POST['xiaoshi_start'],$_POST['fen_start'],$_POST['miao_start']);
								unset($_POST['xiaoshi_end'],$_POST['fen_end'],$_POST['miao_end']);
								
                                $data['start_time'] = $_POST['start_time'];
                                $data['end_time'] = $_POST['end_time'];
                                $data['points'] = $_POST['points'] > 0 ? intval($_POST['points']) : 0;
                                $data['goods_id'] = $_POST['goods_id'];
                                $data['desc'] = $_POST['desc'];
								$data['qingdan'] = $_POST['qingdan'];
                                $data['active'] = $_POST['active']==1 ? $_POST['active'] : 0;
                                if($this->App->insert('goods_groupbuy',$data)){
										$iid = $this->App->iid();
										foreach($dd as $row){
											$row['group_id'] = $iid;
											$this->App->insert('goods_groupbuy_price',$row);
										}
										unset($data,$dd);
                                        $this->jump('',0,'添加成功！'); exit;
                                }else{
                                        $this->jump('',0,'添加失败！'); exit;
                                }
                                $rt = $_POST; 
                        }
                }
		
				//分类列表
                 $this->set('catelist',$this->action('common','get_goods_cate_tree'));
                 //品牌列表
                 //$sql = "SELECT brand_name,brand_id FROM `{$this->App->prefix()}brand` ORDER BY sort_order ASC, brand_id DESC";
                 //$this->set('brandlist',$this->App->find($sql));
				 $this->set('brandlist',$this->action('common','get_brand_cate_tree'));		
			
		$this->set('rt',$rt);
		$this->template('groupinfo');
	}
	
	//ajax获取商品
	function ajax_get_group_goods($data = array()){
		$cid = $data['cat_id'];
		$bid = $data['brand_id'];
		$key = $data['keyword'];
		
		$comd = array();
		$w = "";
		if($cid>0){
			$cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
			$comd[] = 'tb1.cat_id IN('.implode(",",$cids).') OR tb3.cat_id = '.intval($cid);
		}

		if($bid>0)  $comd[] = 'tb1.brand_id='.intval($bid);
		
		if(!empty($key))    $comd[] = "(tb1.goods_name LIKE '%".trim($key)."%')";

		if(!empty($comd))   $w = ' WHERE '.implode(' AND ',$comd);

		
		$orderby = ' ORDER BY tb1.`goods_id` DESC';
		$sql = "SELECT tb1.goods_id, tb1.goods_name FROM  `{$this->App->prefix()}goods` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}category_sub_goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .=" $w $orderby LIMIT 10";
		$rt = $this->App->find($sql);
		if(!empty($rt)){
			$str = "";	
			foreach($rt as $row){		
				$str .= '<option value="'.$row['goods_id'].'">'.$row['goods_name'].'</option>'."\n";
			}
			echo $str;
		}else{
			echo '<option value="0">无找到可匹配的商品结果</option>';
		}
		unset($comd,$rt);
		exit;
	}
	
	/*
	AJAX删除团购商品价格
	*/
	function ajax_del_group_goods($id=0){
		if(!($id>0)){ echo "传送ID非法！"; exit;}
		$this->App->delete('goods_groupbuy_price','gpid',$id);
	}
	
	/*
	AJAX删除团购山坡
	*/
	function ajax_delgroup($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;	
		if(!empty($ids)){
			$sql = "delete from `{$this->App->prefix()}goods_groupbuy` WHERE group_id IN(".implode(',',$id_arr).")"; 
			$this->App->query($sql);
			$this->action('system','add_admin_log','删除团购商品：'.@implode(',',$id_arr));
		}
		exit;
	}
}
?>