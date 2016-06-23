<?php
 /*
 * 这是一个后台产品处理类
 */
class GoodsController extends Controller{
 	function  __construct() {
			$this->css('content.css');
	}
	
	//商家绑定微信
	function bingweixin($data=array()){
		$id = $data['id'];//当前商家的uid
		if(!empty($_POST)){
					$this->App->update('user',$_POST,'user_id',$id);
					$iid = $_POST['shopid'];
					$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id='{$iid}' LIMIT 1";
					$uid = $this->App->findvar($sql);//关联的微信uid
					
					if($uid > 0){
						$rr = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");
						$pwecha_id = isset($rr['wecha_id']) ? $rr['wecha_id'] : '';
						$nickname = isset($rr['nickname']) ? $rr['nickname'] : '';
						if(!empty($pwecha_id) && !empty($nickname)){
							$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname,'sn'=>$uid),'bindweixin');
						}
					}
					$this->action('common','showdiv',$this->getthisurl());
		}
		$sql = "SELECT shopid FROM `{$this->App->prefix()}user` WHERE user_id='{$id}'";
		$uuid = $this->App->findvar($sql);
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='{$uuid}'";
		$rt = $this->App->findrow($sql);
		$this->set('rt',$rt);
		$this->template('bingweixin');
	}
	
	function ajax_u_name_shopid($rt=array()){
		$name = $rt['searchval'];
		if(empty($name)) die('');
		$sql = "SELECT nickname,user_id FROM `{$this->App->prefix()}user` WHERE user_rank!='10' AND (nickname LIKE '%$name%' OR mobile_phone LIKE '%$name%') LIMIT 10";
		$vv = $this->App->find($sql);
		if(empty($vv)) die("");
		$str = '';
		foreach($vv as $row){
			$str .= '<option value="'.$row[user_id].'">'.$row[nickname].'</option>';
		}
		echo $str;exit;
	}
	
	function goods_tuijian($data=array()){
		$id = isset($data['id']) ? $data['id'] : '0';
		if($id > 0){
			$this->App->delete('goods_tuijian','id',$id);
			$this->jump(ADMIN_URL.'goods.php?type=goods_tuijian');exit;
		}
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.shop_price,tb2.pifa_price,tb2.goods_thumb FROM `{$this->App->prefix()}goods_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id = tb2.goods_id ORDER BY tb1.id DESC";
		$rt = $this->App->find($sql);
		
		$this->set('rt',$rt);
		$this->template('goods_tuijian');
	}
	function goods_tuijian_info($data=array()){
		$this->js(array("edit/kindeditor.js"));
		$this->js('time/WdatePicker.js');
			
		$id = isset($data['id']) ? $data['id'] : '0';
		if($id > 0){
			$sql = "SELECT tb1.*,tb2.goods_name FROM `{$this->App->prefix()}goods_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE tb1.id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			
			if(!empty($_POST)){
				if($this->App->update('goods_tuijian',$_POST,'id',$id)){
					 $this->action('common','showdiv',$this->getthisurl());
				}
			}
		
		}else{
			if(!empty($_POST)){
				if($this->App->insert('goods_tuijian',$_POST)){
					 $this->action('common','showdiv',$this->getthisurl());
				}
			}
		}
		
		$this->set('rt',$rt);
		$this->set('id',$gid);
		$this->template('goods_tuijian_info');
	}
	
	function ajax_goods_name($rt=array()){
		$name = $rt['searchval'];
		if(empty($name)) die('');
		$sql = "SELECT goods_id,goods_name FROM `{$this->App->prefix()}goods` WHERE goods_name LIKE '%$name%' LIMIT 10";
		$vv = $this->App->find($sql);
		$str = '';
		if(!empty($vv))foreach($vv as $row){
			$str .= '<option value="'.$row[goods_id].'">'.$row[goods_name].'</option>';
		}
		echo $str;exit;
	}
	
	//商品列表页面
	function goods_list(){
            //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY tb1.`sort_order` ASC,tb1.`goods_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
            //查询条件
            $w="";
            $ws="";
            if(isset($_GET)&&!empty($_GET)){
                //$art = array('cat_id','brand_id','is_on_sale');
                $comd = array();
				if(isset($_GET['is_delete']) && $_GET['is_delete'] =='1'){
					$comd[] = "tb1.is_delete='1'";
					$showtpl = "goods_list_delete";
				}else{
					$comd[] = "tb1.is_delete='0'";
					$showtpl = "goods_list";
				}
			
			
                if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0){
                    $cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
					$comd[] = 'tb1.cat_id IN ('.implode(",",$cids).')';
				}
		
				if(isset($_GET['uid'])&&intval($_GET['uid'])>0)
                    $comd[] = 'tb1.uid='.intval($_GET['uid']);
			/*
				if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0)
					 $comd[] = 'tb1.cat_id ='.intval($_GET['cat_id']);
			*/
                if(isset($_GET['brand_id'])&&intval($_GET['brand_id'])>0)
                    $comd[] = 'tb1.brand_id='.intval($_GET['brand_id']);
				/*
                if(isset($_GET['is_on_sale'])&&($_GET['is_on_sale']=='0'||$_GET['is_on_sale']=='1'))
                    $comd[] = 'tb1.is_on_sale='.$_GET['is_on_sale'];*/
				if(isset($_GET['is_goods_attr'])&&!empty($_GET['is_goods_attr'])){
					switch(trim($_GET['is_goods_attr'])){
						case 'is_on_sale1':
							$comd[] = "tb1.is_on_sale='1'";
							break;
						case 'is_on_sale0':
							$comd[] = "tb1.is_on_sale='0'";
							break;
						case 'is_hot':
							$comd[] = "tb1.is_hot='1'";
							break;
						case 'is_new':
							$comd[] = "tb1.is_new='1'";
							break;
						case 'is_best':
							$comd[] = "tb1.is_best='1'";
							break;
						case 'is_promote':
							$comd[] = "tb1.is_promote='1'";
							break;
						case 'is_alone_sale':
							$comd[] = "tb1.is_alone_sale='0'";
							break;
						case 'is_qianggou':
							$comd[] = "tb1.is_qianggou='1'";
							break;
					}
				}
				$comd[] = "tb1.is_jifen='0' AND tb1.is_virtual='0'";
				//已审核
				if(isset($_GET['sale'])&&$_GET['sale']=='yes'){
					$comd[] = "tb1.is_check='1'";
				}
				//待审核
				if(isset($_GET['sale'])&&$_GET['sale']=='no'){
					$comd[] = "tb1.is_check='0'";
				}
				if(isset($_GET['keyword'])&&$_GET['keyword']){
                    $comd[] = "(tb1.goods_name LIKE '%".trim($_GET['keyword'])."%' OR tb1.goods_sn LIKE '%".trim($_GET['keyword'])."%')";
				}
                if(!empty($comd)){
                    $w = ' WHERE '.implode(' AND ',$comd);
                    $ws = str_replace('tb1.','',$w);
                }
            }
            $list = 30;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` $ws";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
            $sql = "SELECT tb1.goods_id,tb1.sort_order,tb1.cat_id,tb1.goods_thumb, tb1.goods_sn, tb1.goods_name, tb1.is_on_sale,tb1.is_check, tb1.is_promote,tb1.is_qianggou,tb1.market_price, tb1.shop_price,tb1.pifa_price,tb1.promote_price,tb1.qianggou_price,tb1.qianggou_start_date,tb1.qianggou_end_date,tb1.promote_start_date,tb1.promote_end_date,tb1.add_time, tb1.is_shipping,tb1.is_best,tb1.is_new,tb1.is_hot,tb1.is_alone_sale,tb2.cat_name,tb3.user_name,tb3.nickname FROM `{$this->App->prefix()}goods` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid=tb3.user_id";
            $sql .=" $w $orderby LIMIT $start,$list";// echo $sql;
            $rt = $this->App->find($sql);
       
		   //供应商列表
			$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id DESC";
			$this->set('uidlist',$this->App->find($sql));
		
			$this->set('rt',$rt);
			//分类列表
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//品牌列表
		   // $sql = "SELECT brand_name,brand_id FROM `{$this->App->prefix()}brand` ORDER BY sort_order ASC, brand_id DESC";
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
			$this->template($showtpl);
	}
	
	//商品列表页面
	function goods_list_all($dd=array()){
            //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY tb1.`sort_order` ASC,tb1.`goods_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
            //查询条件
            $w="";
            $ws="";
            if(isset($_GET)&&!empty($_GET)){
                //$art = array('cat_id','brand_id','is_on_sale');
                $comd = array();
				$comd[] = "tb1.is_delete='1'";
                if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0){
                    $cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
					$comd[] = 'tb1.cat_id IN ('.implode(",",$cids).')';
				}
		
				if(isset($_GET['uid'])&&intval($_GET['uid'])>0)
                    $comd[] = 'tb1.uid='.intval($_GET['uid']);
			/*
				if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0)
					 $comd[] = 'tb1.cat_id ='.intval($_GET['cat_id']);
			*/
                if(isset($_GET['brand_id'])&&intval($_GET['brand_id'])>0)
                    $comd[] = 'tb1.brand_id='.intval($_GET['brand_id']);
				/*
                if(isset($_GET['is_on_sale'])&&($_GET['is_on_sale']=='0'||$_GET['is_on_sale']=='1'))
                    $comd[] = 'tb1.is_on_sale='.$_GET['is_on_sale'];*/
				if(isset($_GET['is_goods_attr'])&&!empty($_GET['is_goods_attr'])){
					switch(trim($_GET['is_goods_attr'])){
						case 'is_on_sale1':
							$comd[] = "tb1.is_on_sale='1'";
							break;
						case 'is_on_sale0':
							$comd[] = "tb1.is_on_sale='0'";
							break;
						case 'is_hot':
							$comd[] = "tb1.is_hot='1'";
							break;
						case 'is_new':
							$comd[] = "tb1.is_new='1'";
							break;
						case 'is_best':
							$comd[] = "tb1.is_best='1'";
							break;
						case 'is_promote':
							$comd[] = "tb1.is_promote='1'";
							break;
						case 'is_alone_sale':
							$comd[] = "tb1.is_alone_sale='0'";
							break;
						case 'is_qianggou':
							$comd[] = "tb1.is_qianggou='1'";
							break;
						case 'is_jifen':
							$comd[] = "tb1.is_jifen='1'";
							break;
					}
				}
				
                if(isset($_GET['keyword'])&&$_GET['keyword'])
                    $comd[] = "(tb1.goods_name LIKE '%".trim($_GET['keyword'])."%' OR tb1.goods_sn LIKE '%".trim($_GET['keyword'])."%')";
				//已审核
				if(isset($_GET['sale'])&&$_GET['sale']=='yes'){
					$comd[] = "tb1.is_check='1'";
				}
				//待审核
				if(isset($_GET['sale'])&&$_GET['sale']=='no'){
					$comd[] = "tb1.is_check='0'";
				}
				
                if(!empty($comd)){
                    $w = ' WHERE '.implode(' AND ',$comd);
                    $ws = str_replace('tb1.','',$w);
                }
            }
            $list = 30;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` $ws";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
            $sql = "SELECT tb1.goods_id,tb1.sort_order,tb1.cat_id,tb1.goods_thumb, tb1.goods_sn, tb1.goods_name, tb1.is_on_sale,tb1.is_check, tb1.is_promote,tb1.is_qianggou,tb1.market_price, tb1.shop_price,tb1.pifa_price,tb1.promote_price,tb1.qianggou_price,tb1.qianggou_start_date,tb1.qianggou_end_date,tb1.promote_start_date,tb1.promote_end_date,tb1.add_time, tb1.is_shipping,tb1.is_best,tb1.is_new,tb1.is_hot,tb1.is_alone_sale,tb2.cat_name,tb3.user_name,tb3.nickname FROM `{$this->App->prefix()}goods` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid=tb3.user_id";
            $sql .=" $w $orderby LIMIT $start,$list";// echo $sql;
            $rt = $this->App->find($sql);
       
		   //供应商列表
			$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id DESC";
			//$this->set('uidlist',$this->App->find($sql));
		
			$this->set('rt',$rt);
			//分类列表
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//品牌列表
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
			$this->template('goods_list_delete');
	}
	
	function goods_list_check(){
		  //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY sg.`sgid` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
			
            //查询条件
            $w="";
            $ws="";
            if(isset($_GET)&&!empty($_GET)){
                $comd = array();
			
                if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0){
                    $cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
					$comd[] = 'g.cat_id IN ('.implode(",",$cids).')';
				}
		
				if(isset($_GET['uid'])&&intval($_GET['uid'])>0)
                    $comd[] = 'sg.suppliers_id='.intval($_GET['uid']);

                if(isset($_GET['brand_id'])&&intval($_GET['brand_id'])>0)
                    $comd[] = 'b.brand_id='.intval($_GET['brand_id']);

				if(isset($_GET['is_goods_attr'])&&!empty($_GET['is_goods_attr'])){
					switch(trim($_GET['is_goods_attr'])){
						case 'is_on_sale1':
							$comd[] = "sg.is_on_sale='1'";
							break;
						case 'is_on_sale0':
							$comd[] = "sg.is_on_sale='0'";
							break;
						case 'is_hot':
							$comd[] = "g.is_hot='1'";
							break;
						case 'is_new':
							$comd[] = "g.is_new='1'";
							break;
						case 'is_best':
							$comd[] = "g.is_best='1'";
							break;
						case 'is_promote':
							$comd[] = "g.is_promote='1'";
							break;
						case 'is_alone_sale':
							$comd[] = "g.is_alone_sale='0'";
							break;
						case 'is_qianggou':
							$comd[] = "g.is_qianggou='1'";
							break;
						case 'is_jifen':
							$comd[] = "g.is_jifen='1'";
							break;
					}
				}

                if(isset($_GET['keyword'])&&$_GET['keyword'])
                    $comd[] = "(g.goods_name LIKE '%".trim($_GET['keyword'])."%' OR g.goods_sn LIKE '%".trim($_GET['keyword'])."%')";
				//已审核
				if(isset($_GET['sale'])&&$_GET['sale']=='yes'){
					$comd[] = "sg.is_check='1'";
				}
				//待审核
				if(isset($_GET['sale'])&&$_GET['sale']=='no'){
					$comd[] = "sg.is_check='0'";
				}
				
				$comd[] = "g.is_delete='0'";
				$comd[] = "sg.is_delete='0'";
				
                if(!empty($comd)){
                    $w = ' WHERE '.implode(' AND ',$comd);
                    $ws = str_replace('tb1.','',$w);
                }
            }
            $list = 30;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(sg.goods_id) FROM `{$this->App->prefix()}suppliers_goods` AS sg LEFT JOIN `{$this->App->prefix()}goods` AS g ON sg.goods_id=g.goods_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}brand` AS b ON b.brand_id = g.brand_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = sg.suppliers_id $w";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
            /*$sql = "SELECT tb1.goods_id,tb1.sort_order,tb1.cat_id,tb1.goods_thumb, tb1.goods_sn, tb1.goods_name, tb1.is_on_sale,tb1.is_check, tb1.is_promote,tb1.is_qianggou,tb1.market_price, tb1.shop_price,tb1.pifa_price,tb1.promote_price,tb1.qianggou_price,tb1.qianggou_start_date,tb1.qianggou_end_date,tb1.promote_start_date,tb1.promote_end_date,tb1.add_time, tb1.is_shipping,tb1.is_best,tb1.is_new,tb1.is_hot,tb1.is_alone_sale,tb2.cat_name,tb3.user_name,tb3.nickname FROM `{$this->App->prefix()}goods` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid=tb3.user_id";
            $sql .=" $w $orderby LIMIT $start,$list"; */
           
       		$sql = "SELECT sg.*,g.goods_name,g.goods_unit,g.goods_thumb,g.cat_id,gc.cat_name,b.brand_name,u.user_name FROM `{$this->App->prefix()}suppliers_goods` AS sg LEFT JOIN `{$this->App->prefix()}goods` AS g ON sg.goods_id=g.goods_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS gc ON gc.cat_id = g.cat_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}brand` AS b ON b.brand_id = g.brand_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = sg.suppliers_id";
			$sql .=" $w $orderby LIMIT $start,$list";
			$rt = $this->App->find($sql);
			
		   //供应商列表
			$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id DESC";
			$this->set('uidlist',$this->App->find($sql));
		
			$this->set('rt',$rt);
			//分类列表
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//品牌列表
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			
			$this->template('goods_list_check');
	}
	
	//商品详情页面
	function goods_info($gid=0){
            $this->js(array("kindeditor/kindeditor.js","kindeditor/lang/zh_CN.js",'time/WdatePicker.js'));
			$this->css('default.css');
		
			//公共部分
			if(isset($_POST)&&!empty($_POST)){
				$_POST['is_best'] = isset($_POST['is_best'])&&intval($_POST['is_best'])>0 ? intval($_POST['is_best']) : '0';
				$_POST['is_new'] = isset($_POST['is_new'])&&intval($_POST['is_new'])>0 ? intval($_POST['is_new']) : '0';
				$_POST['is_hot'] = isset($_POST['is_hot'])&&intval($_POST['is_hot'])>0 ? intval($_POST['is_hot']) : '0';
				$_POST['is_on_sale'] = isset($_POST['is_on_sale'])&&intval($_POST['is_on_sale'])>0 ? intval($_POST['is_on_sale']) : '1';
				$_POST['is_shipping'] = isset($_POST['is_shipping'])&&intval($_POST['is_shipping'])>0 ? intval($_POST['is_shipping']) : '0';
				$_POST['is_alone_sale'] = isset($_POST['is_alone_sale'])&&intval($_POST['is_alone_sale'])>0 ? '0' : '1';
				$_POST['is_promote'] = isset($_POST['is_promote'])&&intval($_POST['is_promote'])>0 ? intval($_POST['is_promote']) : '0';
				if($_POST['is_promote']=='1'){
				
					if(isset($_POST['promote_start_date'])&&!empty($_POST['promote_start_date'])){
				 		$_POST['promote_start_date'] =  strtotime($_POST['promote_start_date'].' '.$_POST['xiaoshi_start'].':'.$_POST['fen_start'].':'.$_POST['miao_start']);
					}
					if(isset($_POST['promote_end_date'])&&!empty($_POST['promote_end_date'])){
						 $_POST['promote_end_date'] =  strtotime($_POST['promote_end_date'].' '.$_POST['xiaoshi_end'].':'.$_POST['fen_end'].':'.$_POST['miao_end']);
					}
				}
				unset($_POST['xiaoshi_start'],$_POST['fen_start'],$_POST['miao_start']);
				unset($_POST['xiaoshi_end'],$_POST['fen_end'],$_POST['miao_end']);
				
				
				$_POST['is_qianggou'] = isset($_POST['is_qianggou'])&&intval($_POST['is_qianggou'])>0 ? intval($_POST['is_qianggou']) : '0';
				$_POST['qianggou_start_date'] = isset($_POST['qianggou_start_date'])&&!empty($_POST['qianggou_start_date']) ? strtotime($_POST['qianggou_start_date']) : '0';
				$_POST['qianggou_end_date'] = isset($_POST['qianggou_end_date'])&&!empty($_POST['qianggou_end_date']) ? strtotime($_POST['qianggou_end_date']) : '0';
				$_POST['is_jifen'] = isset($_POST['is_jifen'])&&intval($_POST['is_jifen'])>0 ? intval($_POST['is_jifen']) : '0';
				$_POST['is_check'] = '1';
			   ######################
				//添加商品属性||过滤商品属性字段，以更好插入到商品表
				$atid = array('attr_id_list'=>'0'); //属性id，在gz_attribute表中
				$atvalue = array('attr_value_list'=>'0'); //用户添加的值
				$ataddi = array('attr_addi_list'=>'0'); //附加的东西，例如可以是价格图片等其他东西
				$gadesc = array('photo_gallery_desc'=>'0'); //商品相册描述
				$gaurl = array('photo_gallery_url'=>'0'); //商品相册图片
				$goods_gift = array('gift_type'=>'0'); 
				$nprice = array('numberprice'=>'0');
				$nrank = array('numberrank'=>'0');
				
				$attr_id_list = array();
				$attr_value_list = array();
				$attr_addi_list = array();
				$photo_gallery_desc = array();
				$photo_gallery_url = array();
				$goods_gift_arr = array();
				$numberprice =array(); //会员价格与等级是一一对应的
				$numberrank =array();
				
				
				if(isset($_POST['gift_type'])){
					$goods_gift_arr = $_POST['gift_type'];
					$_POST = array_diff_key($_POST,$goods_gift);
				}
				
				if(isset($_POST['attr_id_list'])){
					$attr_id_list = $_POST['attr_id_list']; //属性id，在gz_attribute表中
					$_POST = array_diff_key($_POST,$atid);
				}
				if(isset($_POST['attr_value_list'])){
					$attr_value_list = $_POST['attr_value_list']; //用户添加的值
					$_POST = array_diff_key($_POST,$atvalue);
				}
				if(isset($_POST['attr_addi_list'])){
					$attr_addi_list = $_POST['attr_addi_list']; //附加的东西，例如可以使图片等其他东西
					$_POST = array_diff_key($_POST,$ataddi);
				}
                //商品相册描述
                if(isset($_POST['photo_gallery_desc'])){
					$photo_gallery_desc = $_POST['photo_gallery_desc'];
					$_POST = array_diff_key($_POST,$gadesc);
				}
                //商品相册图片
                if(isset($_POST['photo_gallery_url'])){
					$photo_gallery_url = $_POST['photo_gallery_url'];
					$_POST = array_diff_key($_POST,$gaurl);
				}
				//商品的额外分类处理
				$sd = array('sub_cat_id'=>'0');
				$subcateid = array();
				if(isset($_POST['sub_cat_id'])){
						$subcateid = $_POST['sub_cat_id'];
						$_POST = array_diff_key($_POST,$sd);
				}
								
				//会员等级价格
                if(isset($_POST['numberprice'])){
					$numberprice = $_POST['numberprice'];
					$_POST = array_diff_key($_POST,$nprice);
				}
				//会员等级
                if(isset($_POST['numberrank'])){
					$numberrank = $_POST['numberrank'];
					$_POST = array_diff_key($_POST,$nrank);
				}
				####################
			}
			
			
            if($gid>0){ //编辑页面
			//当前商品基本信息
			$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE goods_id='{$gid}' LIMIT 1";
            $rt = $this->App->findrow($sql);
			if(empty($rt)){ $this->jump('goods.php?type?goods_list'); exit;}
			//当前商品的相册
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id='$gid'";
			$this->set('gallerylist',$this->App->find($sql));
			//当前商品属性的属性
			$sql = "SELECT tb1.*,tb2.attr_name,tb2.attr_is_select FROM `{$this->App->prefix()}goods_attr` AS tb1 LEFT JOIN `{$this->App->prefix()}attribute` AS tb2 ON tb1.attr_id=tb2.attr_id WHERE tb1.goods_id='$gid'";
			$goods_attr = $this->App->find($sql);
			$rt['goods_attr'] = array();
			if(!empty($goods_attr)){
				foreach($goods_attr as $row){
					$rt['goods_attr'][$row['attr_id']][] = $row;
				}
				unset($row,$goods_attr);
			}
			
			//商品的赠品类型
			$sql = "SELECT  type  FROM `{$this->App->prefix()}goods_gift` WHERE goods_id='$gid'";
			$rt['gift_type_id'] = $this->App->findcol($sql);
						
                        if(isset($_POST)&&!empty($_POST)){
								if(empty($_POST['goods_name'])){
                                    echo'<script>alert("商品名称不能为空！");</script>';
                                }else{
                                    /*if(empty($_POST['original_img'])){
                                            $this->jump('goods.php?type=goods_info&id='.$gid,0,'请你先上传图片'); exit;
                                    }*/

                                    //货号
                                    if(empty($_POST['goods_sn'])){
                                         $_POST['goods_sn'] = 'GZFH' . str_repeat('0', 6 - strlen($gid)) . $gid;
                                    }
                                    //检查当前的货号是否存在
                                    $checkvar = $this->App->findvar("SELECT goods_sn FROM `{$this->App->prefix()}goods` WHERE goods_sn=$_POST[goods_sn] LIMIT 1");
                                    if(!empty($checkvar)){
                                         $_POST['goods_sn'] = $_POST['goods_sn'].'-1'; //重新定义一个
                                    }

                                     if($rt['original_img']!=$_POST['original_img']){
                                            //修改了上传文件 那么重新上传
                                            $source_path = SYS_PATH.DS.str_replace('/',DS,$_POST['original_img']);
                                            $pa = dirname($_POST['original_img']);
                                            $thumb = basename($_POST['original_img']);
											
											$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
											$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
											$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
											$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
											if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
											   //留空
											    if(!file_exists(SYS_PATH.$_POST['goods_thumb'])){
											    	Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
                                            		$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
												}
											}else{
                                            	Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
                                            	$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
											}
											 
                                            Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_b'.DS.$thumb,$tw_b,$th_b); //大缩略图
                                            $_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
                                     }
                                     $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
									 
									 $_POST['last_update'] = mktime(); //更新时间
                                     $this->App->update('goods',$_POST,'goods_id',$gid);
								
                                     //更新商品属性[从新添加]
                                     if(!empty($attr_id_list)&&!empty($gid)){
                                            foreach($attr_id_list as $kk=>$id){
                                                    if(empty($attr_value_list[$kk])) continue;
                                                    $rtdata = array();
                                                    $rtdata['attr_id'] = $id;
                                                    $rtdata['attr_value'] = isset($attr_value_list[$kk]) ? $attr_value_list[$kk] : "NULL";
                                                    $rtdata['goods_id'] = $gid;
                                                    $rtdata['attr_addi'] = isset($attr_addi_list[$kk]) ? $attr_addi_list[$kk] : "";
                                                    $this->App->insert('goods_attr',$rtdata);
                                            }
                                            unset($rtdata);
                                     }
                                     ###########更新商品相册##########
                                     if(!empty($photo_gallery_url)&&!empty($gid)){
                                          foreach($photo_gallery_url as $kk=>$url){
                                               if(empty($url)) continue;
                                                $rtdata['img_desc'] = isset($photo_gallery_desc[$kk]) ? $photo_gallery_desc[$kk] : "";
                                                $rtdata['goods_id'] = $gid;
                                                $rtdata['img_url'] = $url;
                                                $this->App->insert('goods_gallery',$rtdata);
                                          }
                                          unset($rtdata);
                                     }
                                     //商品的子分类
                                     if(!empty($subcateid)){
                                           foreach($subcateid as $ids){
                                               $dd = array();
                                               $dd['goods_id'] = $gid;
                                               $dd['cat_id'] = $ids;
                                               $this->App->insert('category_sub_goods',$dd);
                                           }
                                     }
									 //将关键字添加到goods_keyword表
									 if(!empty($_POST['meta_keys'])){
									 	$keys = explode(',',$_POST['meta_keys']);
										foreach($keys as $key){
											if(empty($key)) continue;
											$key = trim($key);
											$sql = "SELECT kid FROM `{$this->App->prefix()}goods_keyword` WHERE goods_id='$gid' AND keyword='$key'";
											$kid = $this->App->findvar($sql);
											$ds = array();
											if(empty($kid)){
												$ds['goods_id'] = $gid;
												$ds['keyword'] = $key;
												$n = Import::basic()->Pinyin($key);
												$ds['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
												$this->App->insert('goods_keyword',$ds);
											}
										}
										unset($keys);
									 }
									 
									 //赠品
									 if(!empty($goods_gift_arr)){
									 	foreach($goods_gift_arr as $tt){
											if(empty($tt)) continue;
											$dd['goods_id'] = $gid;
											$dd['type'] = $tt;
											$sql = "SELECT gifid FROM `{$this->App->prefix()}goods_gift` WHERE goods_id='$gid' AND type='$tt'";
											$a = $this->App->findvar($sql);
											if(empty($a)){
												$this->App->insert('goods_gift',$dd);
											}
										}
									 }
									 
									 //会员等级价格添加
									 if(!empty($numberprice)){
										 foreach($numberprice as $ks=>$price){
										 	//检查是否已经存在
											$rankid= $numberrank[$ks];
											$sql = "SELECT price_id FROM `{$this->App->prefix()}goods_user_price` WHERE goods_id='$gid' AND user_rank='$rankid'";
											$price_id = $this->App->findvar($sql);
											if($price_id>0){ //存在
												if($price > 0){ //更改
													$sql = "UPDATE `{$this->App->prefix()}goods_user_price` SET user_price='$price' WHERE goods_id='$gid' AND user_rank='$rankid'";
													$this->App->query($sql);
												}else{ //删除
													$sql = "DELETE FROM `{$this->App->prefix()}goods_user_price` WHERE goods_id='$gid' AND user_rank='$rankid'";
													$this->App->query($sql);
												}
											}else{ //添加
													$dt = array();
													$dt['goods_id'] = $gid;
													$dt['user_rank'] = $rankid;
													$dt['user_price'] = $price;
													$this->App->insert('goods_user_price',$dt);
											}
										 }
									 }
									 
									 //添加分类与品牌与商品关联
									 /*if($_POST['cat_id'] > 0 && $_POST['brand_id'] > 0){
									 	$cid = intval($_POST['cat_id']);
										$bid = intval($_POST['brand_id']);
										$gid = $gid;
										$ggid = $this->App->findvar("SELECT cbgid FROM `{$this->App->prefix()}cate_brand_goods` WHERE gid='$gid' LIMIT 1");
										if($ggid > 0){//更新 
											$this->App->update('cate_brand_goods',array('cid'=>$bid,'bid'=>$bid),'gid',$ggid);
										}else{ //添加
											$this->App->insert('cate_brand_goods',array('cid'=>$cid,'bid'=>$bid,'gid'=>$gid));
										}
									 }*/
									 
                                     $this->action('system','add_admin_log','修改商品:'.$_POST['goods_name'].'-goods_id:'.$gid);
                                     $this->action('common','showdiv',$this->getthisurl());
                                }
			}
                        //该商品的其他子分类
                        $sql = " SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}category_sub_goods` AS tb1";
                        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
                        $sql .=" WHERE tb1.goods_id='$gid'";
                        $this->set('subcatelist',$this->App->find($sql));
                        $this->set('type','edit');
		}else{
                        //添加
                        if(isset($_POST)&&!empty($_POST)){
                             if(empty($_POST['goods_name'])){
                                 echo'<script>alert("商品名称不能为空！");</script>';
                             }else{ 
                                 $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
                                 $_POST['add_time'] = mktime();
                                 if(!isset($_POST['goods_sn']) || empty ($_POST['goods_sn'])){
                                     $gid = $this->App->findvar("SELECT MAX(goods_id) + 1 FROM `{$this->App->prefix()}goods`");
                                     $gid = empty($gid) ? 1 : $gid;
                                     $goods_sn = 'GZFH' . str_repeat('0', 6 - strlen($gid)) . $gid;
                                     $_POST['goods_sn'] = $goods_sn;
                                 }
                                 //检查当前的货号是否存在
                                 $checkvar = $this->App->findvar("SELECT goods_sn FROM `{$this->App->prefix()}goods` WHERE goods_sn=$_POST[goods_sn] LIMIT 1");
                                 if(!empty($checkvar)){
                                     $_POST['goods_sn'] = $_POST['goods_sn'].'-1'; //重新定义一个
                                 }
                                 //商品图片
                                 if(!empty($_POST['original_img'])){
                                    $pa = dirname($_POST['original_img']);
                                    $thumb = basename($_POST['original_img']);
                                    //商品小图
									if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
										//留空即可
									}else{
										$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
									}
									//商品中图
                                    $_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
                                 }
									
                                 if($this->App->insert('goods',$_POST)){
                                         ##########商品属性添加###########
                                         $isertid = $this->App->iid();
                                         if(!empty($attr_id_list)&&!empty($isertid)){
                                                foreach($attr_id_list as $kk=>$id){
                                                        if(empty($attr_value_list[$kk])) continue;
                                                        $rtdata = array();
                                                        $rtdata['attr_id'] = $id;
                                                        $rtdata['attr_value'] = isset($attr_value_list[$kk]) ? $attr_value_list[$kk] : "NULL";
                                                        $rtdata['goods_id'] = $isertid;
                                                        $rtdata['attr_addi'] = isset($attr_addi_list[$kk]) ? $attr_addi_list[$kk] : "";
                                                        $this->App->insert('goods_attr',$rtdata);
                                                }
                                                unset($rtdata);
                                         }
                                         ###########添加商品相册##########
                                         if(!empty($photo_gallery_url)&&!empty($isertid)){
                                              foreach($photo_gallery_url as $kk=>$url){
                                                   if(empty($url)) continue;
                                                    $rtdata['img_desc'] = isset($photo_gallery_desc[$kk]) ? $photo_gallery_desc[$kk] : "";
                                                    $rtdata['goods_id'] = $isertid;
                                                    $rtdata['img_url'] = $url;
                                                    $this->App->insert('goods_gallery',$rtdata);
                                              }
                                              unset($rtdata);
                                         }
                                         //商品的子分类
                                         if(!empty($subcateid)){
                                               foreach($subcateid as $ids){
                                                   $dd = array();
                                                   $dd['goods_id'] = $isertid;
                                                   $dd['cat_id'] = $ids;
                                                   $this->App->insert('category_sub_goods',$dd);
                                               }
                                         }
										 
										 //将关键字添加到goods_keyword表
										 if(!empty($_POST['meta_keys'])){
											$keys = explode(',',$_POST['meta_keys']);
											foreach($keys as $key){
												if(empty($key)) continue;
												$key = trim($key);
												$ds = array();
												$ds['goods_id'] = $isertid;
												$ds['keyword'] = $key;
												$n = Import::basic()->Pinyin($key);
												$ds['p_fix'] = !empty($n) ? ucwords(substr($n,0,1)) : "NAL";
												$this->App->insert('goods_keyword',$ds);
											}
											unset($keys);
										 }
									 
									 	 //赠品
										 if(!empty($goods_gift_arr)){
											foreach($goods_gift_arr as $tt){
												if(empty($tt)) continue;
												$dd['goods_id'] = $isertid;
												$dd['type'] = $tt;
												$sql = "SELECT gifid FROM `{$this->App->prefix()}goods_gift` WHERE goods_id='$gid' AND type='$tt'";
												$a = $this->App->findvar($sql);
												if(empty($a)){
													$this->App->insert('goods_gift',$dd);
												}
											}
										 }
										 
										 //会员等级价格添加
										 if(!empty($numberprice)){
											 foreach($numberprice as $ks=>$price){
												if($price > 0){
													$dt = array();
													$dt['goods_id'] = $isertid;
													$dt['user_rank'] = $numberrank[$ks];
													$dt['user_price'] = $price;
													$this->App->insert('goods_user_price',$dt);
												}
											 }
										 }
										 
										 //添加分类与品牌与商品关联
										 /*if($_POST['cat_id'] > 0 && $_POST['brand_id'] > 0){
											$cid = intval($_POST['cat_id']);
											$bid = intval($_POST['brand_id']);
											//添加
											$this->App->insert('cate_brand_goods',array('cid'=>$cid,'bid'=>$bid,'gid'=>$isertid));
										 }*/
									 
                                         $this->action('system','add_admin_log','添加商品:'.$_POST['goods_name'].'-goods_id:'.$gid);
                                         $this->action('common','showdiv',$this->getthisurl());
                                }else{
                                        echo '<script> alert("添加失败，添加过程发生意外错误！"); </script>';
                                }
                             }
                            $rt = $_POST;
                        }
                 $this->set('type','add');
		}
		//商品的属性列表
		$sql = "SELECT * FROM `{$this->App->prefix()}attribute` ORDER BY sort_order,attr_id DESC";
		$this->set('attr_list',$this->App->find($sql)); 
		
		$fn = SYS_PATH.'data/goods_spend_gift.php';
		$spendgift = array();
		if(file_exists($fn) && is_file($fn)){
				include_once($fn);
		}
		$rt['gift_typesd'] = $spendgift;
		unset($spendgift);
		
		$rt['province'] = $this->action('user','get_regions',1);  //获取省列表
		
		$this->set('rt',$rt);
		//分类列表
		$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		//品牌列表
		//$sql = "SELECT brand_name,brand_id FROM `{$this->App->prefix()}brand` ORDER BY sort_order ASC, brand_id DESC";
		$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
		//会员等级 
		$this->set('userrank',$this->App->find("SELECT * FROM `{$this->App->prefix()}user_level` WHERE lid!='10'"));
		$userprice = array();
		if($gid > 0){
		$userprice_ =$this->App->find("SELECT user_price,user_rank FROM `{$this->App->prefix()}goods_user_price` WHERE goods_id='$gid'");
		if(!empty($userprice_)){
			foreach($userprice_ as $row){
				$userprice[$row['user_rank']] = $row['user_price'];
			}
			unset($userprice_);
		}
		}
		$this->set('userprice',$userprice);
		//批发商
		$sql = "SELECT distinct tb1.user_name,tb1.user_id,tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.user_id=tb2.uid WHERE tb1.user_rank='10' ORDER BY tb1.user_id DESC";
		$this->set('uidlist',$this->App->find($sql));
		
		
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$this->set('rd',$this->App->findrow($sql));
			
		$this->template('goods_info');
	}

	//消费额度赠品管理
	function goods_spend_gift(){
			$fn = SYS_PATH.'data/goods_spend_gift.php';
			
			
			$spendgift = array();
			$rts = array();
			if(file_exists($fn) && is_file($fn)){
				include_once($fn);
			}
			$cache = Import::ajincache();

			//添加
			if(isset($_POST)&&!empty($_POST)){
				if(!($_POST['minspend']>0)){
					$this->jump('',0,'消费额度为数字！'); exit;
				}
				
				$rts = $_POST;
				if(!empty($spendgift)){
					foreach($spendgift as $row){
						$mm[] = $row;
					}
					unset($spendgift);
				}
				if(isset($_GET['id'])&&$_GET['id']>0){
					$mm[$_GET['id']-1] = $rts;
				}else{
					$mm[] = $rts;
				}
				$spendgift = $mm;
				unset($mm);
				
				$cache->write($fn, $spendgift,'spendgift');
				unset($mm);
			}
			
			if(isset($_GET['tt'])&&!empty($_GET['tt'])){
				if($_GET['tt']=='del'){
					$id=$_GET['id'];
					unset($spendgift[$id-1]);
					$cache->write($fn, $spendgift,'spendgift');
					$this->jump('goods.php?type=spend_gift');exit;
				}elseif($_GET['tt']=='update'){
					$id=$_GET['id'];
					$this->jump('goods.php?type=spend_gift&id='.$id);exit;
				}
			}
			if(isset($_GET['id'])&&$_GET['id']>0){
				$rts = $spendgift[$_GET['id']-1];
			}
			
			$this->set('rt',$spendgift);
			$this->set('rts',$rts);
			$this->template('goods_spend_gift');
	}
	
	//批量上传商品
	/*function goods_batch_add($type=""){
            $this->set('catelist',$this->action('common','get_goods_cate_tree'));
            $sql = "SELECT brand_name,brand_id FROM `{$this->App->prefix()}brand` ORDER BY sort_order ASC, brand_id DESC";
            $this->set('brandlist',$this->App->find($sql));
					
            if($type=='cachelist'){
                    $dir = SYS_PATH_PHOTOS.'temp';
                    $rt = Import::fileop()->list_files($dir);
                    $photolist = array();
                    if(!empty($rt)){
                        foreach($rt as  $k=>$var){
							if(empty($var)) continue;
							if(!(preg_match('/^.*$/u', $var) > 0)){
								$var = Import::gz_iconv()->ec_iconv('GB2312', 'UTF8', $var);
							}
                            $file = explode('.',ltrim(strrchr($var,'/'),'/'));
                            $filetype = "";
                            if(!empty($file)){
                                $filetype = strtolower($file[1]);
                                if(!in_array($filetype,array('jpg','png','gif'))) continue;
                                //$filename = Import::gz_iconv()->ec_iconv('GB2312', 'UTF8', $file[0]);
								$filename = $file[0];
                            }else{
				continue;
                            }
                            $photolist[$k] = array('url'=>SYS_PHOTOS_URL.'temp/'.$filename.'.'.$filetype,'pathname'=>SYS_PATH_PHOTOS.'temp'.DS.$filename.'.'.$filetype,'uploadname'=>SYS_PATH_PHOTOS.'goods'.DS.date('Ym',mktime()).DS.($this->upload_random_name()).'.'.$filetype,'filename'=>$filename);
							//Import::img()->thumb($rt[$k]['pathname'],$rt[$k]['uploadname'],150,150);
                        }
                    }
                    unset($rt);
                    //商品的属性列表
                    $sql = "SELECT * FROM `{$this->App->prefix()}attribute` ORDER BY sort_order,attr_id DESC";
                    $this->set('attrlist',$this->App->find($sql));
                    $this->set('photolist',$photolist);
                    $this->template('goods_batch_add_cachelist');
            }else{
                    $this->template('goods_batch_add');
            }
			
	}*/
	
	function ajax_delgoodsgift($data=array()){
		$goods_id = $data['goods_id'];
		$id = $data['giftid'];
		$sql = "DELETE FROM `{$this->App->prefix()}goods_gift` WHERE type='$id' AND goods_id='$goods_id'";
		$this->App->query($sql);
		echo ""; exit;
	}
	
	
	//显示批量上传页面
	function goods_batch_add($type=""){
			$this->js('jquery.json-1.3.js');
            $this->set('catelist',$this->action('common','get_goods_cate_tree'));
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			$adname = $this->Session->read('adminname');  //管理员的名称		
            if($type=='cachelist'){
                    $dir = SYS_PATH_PHOTOS.'temp'.DS.(empty($adname) ? 'admin' : $adname);
                    $rt = Import::fileop()->list_files($dir);
                    $photolist = array();
                    if(!empty($rt)){
						$iconv = Import::gz_iconv();
                        foreach($rt as  $k=>$var){
							if(empty($var)) continue;
							if(!(preg_match('/^.*$/u', $var) > 0)){
								$var = $iconv->ec_iconv('GB2312', 'UTF8', $var);
							}
                            $file = explode('.',ltrim(strrchr($var,'/'),'/'));
                            $filetype = "";
                            if(!empty($file)&&count($file)==2){
                                //$filetype = strtolower($file[1]);
								$filetype = $file[1];
                                if(!in_array($filetype,array('jpg','png','gif','JPG','PNG','GIF'))) continue;
                                //$filename = $iconv->ec_iconv('GB2312', 'UTF8', $file[0]);
								$filename = $file[0];
								$xname = $this->upload_random_name(); //新文件名
								$rts[$xname] = $filename;
								$fn1 = $dir.DS.($iconv->ec_iconv('UTF8', 'GB2312', $filename)).'.'.$filetype; //旧路径
								$fn2 = $dir.DS.$xname.'.'.$filetype; //新路径
								@chmod($fn1,0755);
								@rename($fn1,$fn2);
                            }else{
				continue;
                            }
                     $photolist[$k] = array('url'=>SYS_PHOTOS_URL.'temp/'.(empty($adname) ? 'admin' : $adname).'/'.$xname.'.'.$filetype,'pathname'=>$fn2,'uploadname'=>SYS_PATH_PHOTOS.'goods'.DS.date('Ym',mktime()).DS.($this->upload_random_name()).'.'.$filetype,'filename'=>$filename);
							//Import::img()->thumb($rt[$k]['pathname'],$rt[$k]['uploadname'],150,150);
                        }
                    }
                    unset($rt);
                    //商品的属性列表
                    $sql = "SELECT * FROM `{$this->App->prefix()}attribute` ORDER BY sort_order,attr_id DESC";
                    $this->set('attrlist',$this->App->find($sql));
                    $this->set('photolist',$photolist);
                    $this->template('goods_batch_add_cachelist');
            }else{
                    $this->template('goods_batch_add');
            }
			
	}
	
	//商品分类列表页面
	function cate_list(){
            $this->set('catelist',$this->action('common','get_goods_cate_tree'));
            $this->template('goods_cate_list');
	}
	
	//ajax获取商品HTML
	function ajax_get_group_goods($data = array()){
		$cid = $data['cat_id'];
		$bid = $data['brand_id'];
		$key = $data['keyword'];
		$cx = $data['cx'];
		
		$comd = array();
		$w = "";
		if($cid>0){
			$cids = $this->action('common','get_goods_sub_cat_ids',$_GET['cat_id']);
			$comd[] = 'tb1.cat_id IN('.implode(",",$cids).') OR tb3.cat_id = '.intval($cid);
		}
		if($cx=='1'){
			$comd[] = "tb1.is_promote='1'";
		}elseif($cx=='2'){
			$comd[] = "tb1.is_qianggou='1'";
		}
		
		if($bid>0)  $comd[] = 'tb1.brand_id='.intval($bid);
		
		if(!empty($key))    $comd[] = "(tb1.goods_name LIKE '%".trim($key)."%')";

		if(!empty($comd))   $w = ' WHERE '.implode(' AND ',$comd);

		
		$orderby = ' ORDER BY tb1.`goods_id` DESC';
		$sql = "SELECT tb1.goods_id, tb1.goods_name, tb1.goods_thumb FROM  `{$this->App->prefix()}goods` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}category_sub_goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .=" $w $orderby LIMIT 10";
		$rt = $this->App->find($sql);
		if(!empty($rt)){
			$str = "";	
			foreach($rt as $row){
				$str .='<li><a href="javascript:;" onclick="setgoods(\''.$row['goods_name'].'\',\''.$row['goods_id'].'\')"><img src="'.SITE_URL.$row['goods_thumb'].'" style="max-width:90%;" /></a></li>'."\n";		
			}
			echo $str;
		}else{
			echo '无找到可匹配的商品结果';
		}
		unset($comd,$rt);
		exit;
	}
	
	function selectgoods(){
		 //分类列表
		$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		 //品牌列表
		$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
		
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT goods_id,goods_name,goods_thumb FROM `{$this->App->prefix()}goods` ORDER BY goods_id DESC LIMIT $start,$list";
		$lists = $this->App->find($sql);
		$this->set("lists",$lists);
			
		$this->template('selectgoods');
	}
	
	function selecturl(){
			$page= isset($_GET['page']) ? $_GET['page'] : '';
			if(empty($page)){
				  $page = 1;
			}
			$list = 10;
			$start = ($page-1)*$list;
			
			$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods`";
			$tt = $this->App->findvar($sql);
			$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			$this->set("pagelink",$pagelink);
			
			$sql = "SELECT goods_name,goods_thumb,goods_id FROM `{$this->App->prefix()}goods` ORDER BY goods_id DESC LIMIT $start,$list";
			$lists = $this->App->find($sql);
			$this->set("lists",$lists);
			
			$sql = "SELECT cat_name,cat_id FROM `{$this->App->prefix()}goods_cate`";
            $catelist = $this->App->find($sql);
			$this->set("catelist",$catelist);
			
			//商品tag
			$sql = "SELECT tcid,cat_name FROM `{$this->App->prefix()}top_cate` WHERE parent_id='0'";
			$this->set("taggoods",$this->App->find($sql));
			
			$sql = "SELECT tb1.*,tb2.goods_name FROM `{$this->App->prefix()}goods_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE tb2.is_on_sale = '1' ORDER BY id DESC LIMIT 100";
			$tjgoods = $this->App->find($sql);
			$this->set("tjgoods",$tjgoods);
			
			//文章类
			$sql = "SELECT keyword,article_title,article_id,art_url,type,addtime FROM `{$this->App->prefix()}wx_article` WHERE cat_id='1' AND type='img' ORDER BY article_id DESC LIMIT 20";
            $artlist = $this->App->find($sql);
			$this->set("artlist",$artlist);
			
			$sql = "SELECT keyword,article_title,article_id,art_url,type,addtime FROM `{$this->App->prefix()}wx_article` WHERE cat_id!='1' AND type='img' ORDER BY article_id DESC";
            $artlist = $this->App->find($sql);
			$this->set("artlist2",$artlist);
			
			$sql = "SELECT cat_name,cat_id FROM `{$this->App->prefix()}wx_cate`";
            $catelist = $this->App->find($sql);
			$this->set("catelist2",$catelist);
			
			$this->template('selecturl');
	}
	
	//商品分类详情信息页面
	function cate_info($cid=0){
			$this->css('jquery_dialog.css');
			$this->js('jquery_dialog.js');
            $rt = array();
            if($cid > 0){ //编辑页面
                 if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['cat_name'])){
                         echo'<script>alert("分类名称不能为空！");</script>';
                     }else{
                        /*$sql = "SELECT cat_id FROM `{$this->App->prefix()}goods_cate` WHERE cat_name='$_POST[cat_name]' LIMIT 1";
                        $rs = $this->App->findvar($sql);
                        if(!empty($rs)&&$rs!=$cid){
                              echo'<script> alert("该分类名称已经存在了！"); </script>';
                        }else{*/
							if($_POST['parent_id']!=0){
								$parent = $this->action('common','get_goods_parent_cats',$_POST['parent_id']);
								if(!empty($parent)){
									$arr = Import::basic()->array_sort($parent,'asc');
									$_POST['ctype'] = isset($arr[0]['ctype']) ? $arr[0]['ctype'] : "";
									unset($parent,$arr);
								}
							}
                            $_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
                            $this->App->update('goods_cate',$_POST,'cat_id',$cid);
                            $this->action('system','add_admin_log','修改商品分类:'.$_POST['cat_name']);
                            $this->action('common','showdiv',$this->getthisurl());
                       // }
                     }
                     $rt = $_POST;
                  }
                $sql = "SELECT * FROM `{$this->App->prefix()}goods_cate` WHERE cat_id='{$cid}' LIMIT 1";
                $rt = $this->App->findrow($sql);
                $this->set('type','edit');

            }else{ //添加页面
                 if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['cat_name'])){
                         echo'<script>alert("分类名称不能为空！");</script>';
                     }else{
                         /*$sql = "SELECT cat_id FROM `{$this->App->prefix()}goods_cate` WHERE cat_name='$_POST[cat_name]' LIMIT 1";
                         $rs = $this->App->findvar($sql);
                         if(!empty($rs)){
                              echo'<script> alert("该分类名称已经存在了！"); </script>';
                         }else{*/
						 	if($_POST['parent_id']!=0){
								$parent = $this->action('common','get_goods_parent_cats',$_POST['parent_id']);
								if(!empty($parent)){
									$arr = Import::basic()->array_sort($parent,'asc');
									$_POST['ctype'] = isset($arr[0]['ctype']) ? $arr[0]['ctype'] : "";
									unset($parent,$arr);
								}
							}
                             $_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
                             $this->App->insert('goods_cate',$_POST);
                             $this->action('system','add_admin_log','添加商品分类:'.$_POST['cat_name']);
                             $this->action('common','showdiv',$this->getthisurl());
                        // }
                     }
                     $rt = $_POST;
                 }
                 $this->set('type','add');
            }

            $this->set('rt',$rt);
            $this->set('catelist',$this->action('common','get_goods_cate_tree'));
            $this->template('goods_cate_info');
	}

        //商品评论列表
        /*
         * $uid: 用户id
         * $oid: 订单id
         */
        function goods_comment_list($uid=0,$oid=0){
            if($uid>0){
                $u=" AND c.user_id='$uid'";
            }else{
		$u = "";
            }
			
            if($oid>0){
                $o="";
            }else{
		$o ="";
            }

			 //排序
            $orderby = "";
            if(isset($_GET['desc'])){
                      $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
            }else if(isset($_GET['asc'])){
                      $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
            }else {
                      $orderby = ' ORDER BY c.`comment_id` DESC';
            }
            //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
            $list = 12;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(comment_id) FROM `{$this->App->prefix()}comment` WHERE parent_id='0'";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
            $sql = "SELECT c.content, c.comment_id, c.comment_rank, c.add_time,c.status, c.id_value AS goods_id, g.goods_name, c.ip_address AS ip, c.ip_form, c.user_name FROM `{$this->App->prefix()}comment` AS c";
           // $sql .= " LEFT JOIN `{$this->App->prefix()}comment` AS r  ON r.parent_id = c.comment_id AND r.parent_id > 0";
           // $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS u ON c.user_id = u.user_id AND c.user_id>0";
            $sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = c.id_value";
            $sql .= " WHERE c.parent_id ='0' $u $orderby LIMIT $start,$list";
            $rt = $this->App->find($sql);
           // print_r($rt);
            $this->set('commentlist',$rt);
            $this->template('goods_comment_list');
        }

        //商品评论详情信息
        function goods_comment_info($id=0){
            if(empty($id)){ $this->jump('goods.php?type=comment_list'); exit;}

            $manager_mes = $this->action('manager','getuserinfo');
            $rts['email'] = isset($manager_mes['email']) ? $manager_mes['email'] : "";
            $rts['adminname'] = isset($manager_mes['adminname']) ? $manager_mes['adminname'] : "";
            $rts['adminid'] = isset($manager_mes['adminid']) ? $manager_mes['adminid'] : "";

            //管理员回复
            if(!empty($_POST)){
                if(isset($_POST['comment_id'])&&!empty($_POST['comment_id'])){ //修改回复
					 $_POST['email'] = !empty($_POST['email']) ? $_POST['email'] : $rts['email'];
                     if($_POST['comment_id']>0){
					 	$_POST['up_time'] = mktime();
                        $this->App->update('comment',$_POST,'comment_id',$_POST['comment_id']); //更新状态
                     }
                     $this->action('system','add_admin_log','修改商品评论回复-商品ID:'.$_POST['id_value']);
                     $this->action('common','showdiv',$this->getthisurl());
                }else{ //添加回复
                     $_POST['add_time'] = mktime();
                     $ip = Import::basic()->getip();
                     $_POST['ip_address'] = $ip ? $ip : '0.0.0.0';
                     $_POST['ip_form'] = Import::ip()->ipCity($ip);
                     $_POST['parent_id'] = isset($_GET['id']) ? $_GET['id'] : 0;
                     $_POST['user_id'] = $rts['adminid'] ? $rts['adminid'] : 0;
					 $_POST['email'] = !empty($_POST['email']) ? $_POST['email'] : $rts['email'];
                     $_POST['user_name'] = $rts['adminname'] ? $rts['adminname'] : "";
                     $this->App->insert('comment',$_POST);
                     if($_POST['parent_id']>0){
                        $this->App->update('comment',array('status'=>1),'comment_id',$_POST['parent_id']); //更新状态
                     }
                     $this->action('system','add_admin_log','添加商品评论回复-商品ID:'.$_POST['id_value']);
                     $this->action('common','showdiv',$this->getthisurl());
                }

            }

            $sql = "SELECT c.content,c.comment_id, c.comment_rank,c.email,c.goods_rand,c.shopping_rand,c.saleafter_rand, c.add_time,c.status, c.id_value AS goods_id, g.goods_name, c.ip_address AS ip, c.ip_form, c.user_name,rc.add_time AS rp_addtime, rc.content AS rp_conent,rc.ip_address AS rp_ip,rc.up_time, ad.adminname AS adname,ad.adminid, rc.comment_id AS rp_com_id  FROM  `{$this->App->prefix()}comment` AS c";
            $sql .=" LEFT JOIN `{$this->App->prefix()}comment` AS rc ON rc.parent_id=c.comment_id AND rc.parent_id>0";
           // $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS u ON c.user_id = u.user_id AND c.user_id>0";
            $sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = c.id_value";
            $sql .=" LEFT JOIN `{$this->App->prefix()}admin` AS ad ON ad.adminid=rc.user_id AND rc.user_id>0";
            $sql .=" WHERE c.comment_id='$id'"; 
			
            $this->set('rt',$this->App->findrow($sql));
            $this->set('rp_mes',$rts);
            unset($rts,$manager_mes);
            $this->template('goods_comment_info');
        }

		//商品属性列表
		function goods_attr_list(){
			 $sql = "SELECT * FROM `{$this->App->prefix()}attribute` ORDER BY sort_order,attr_id DESC";
			 $this->set('rt',$this->App->find($sql));
			 $this->template('goods_attr_list');
		}
		
		//商品属性管理
		function goods_attr_info($id=0){
			$rt = array();
			if(isset($_POST)&&!empty($_POST)){
				$_POST['is_show_addi'] = isset($_POST['is_show_addi'])&&intval($_POST['is_show_addi'])>0 ? intval($_POST['is_show_addi']) : '0';
                                $_POST['is_show_cart'] = isset($_POST['is_show_cart'])&&intval($_POST['is_show_cart'])>0 ? intval($_POST['is_show_cart']) : '0';
			}
				
			if($id>0){ //修改操作
				if(isset($_POST)&&!empty($_POST)){
					$attr_id = $this->App->findvar("SELECT attr_id FROM `{$this->App->prefix()}attribute` WHERE attr_name='$_POST[attr_name]' LIMIT 1");
					if(empty($attr_id)||$attr_id==$id){
						$_POST['attr_keys'] = Import::basic()->Pinyin(trim($_POST['attr_name']));
						if($this->App->update('attribute',$_POST,'attr_id',$id)){
							$this->action('system','add_admin_log','更新商品属性：'.trim($_POST['attr_name']));
							$this->action('common','showdiv',$this->getthisurl());exit;
						}else{
							echo "<script> alert('更新失败！'); </script>";
						}
					}else{
						echo "<script> alert('该属性名称已经存在了！'); </script>";
					}
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}attribute` WHERE attr_id='{$id}' LIMIT 1";
				$rt = $this->App->findrow($sql);
				
			}else{ //添加操作
				if(isset($_POST)&&!empty($_POST)){
					$attr_id = $this->App->findvar("SELECT attr_id FROM `{$this->App->prefix()}attribute` WHERE attr_name='$_POST[attr_name]' LIMIT 1");
					if(empty($attr_id)){
						$_POST['attr_keys'] = Import::basic()->Pinyin(trim($_POST['attr_name']));
						if($this->App->insert('attribute',$_POST)){
							$this->action('system','add_admin_log','添加商品属性：'.trim($_POST['attr_name']));
							$this->action('common','showdiv',$this->getthisurl());exit;
						}else{
							echo "<script> alert('添加失败！'); </script>";
						}
					}else{
						echo "<script> alert('该属性名称已经存在了！'); </script>";
					}
					$rt = $_POST;
				}
			}
			$this->set('rt',$rt);
			$this->template('goods_attr');
		}
		
		//免费提取目录
		function freecatalog($id=0){
			//if
			$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}freecatalog` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
			$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
			$rt = $this->App->find($sql);
			$this->set('rt',$rt);
			$this->template('freecatalog');
		}
		
		function freecataloginfo($id=0){
			$fn = SYS_PATH.'data/freecatalogdata.php';
			$fns = SYS_PATH.'data/freecatalogdata_photo.php';
			if(!empty($_POST['keys'])){
				$freecatalog = explode(',',str_replace(array('.','。','，'),',',trim($_POST['keys'])));
				$cache = Import::ajincache();
				if(!empty($_POST['photo_url'])){
					foreach($_POST['photo_url'] as $k=>$var){
						if(file_exists(SYS_PATH.$var) && is_file(SYS_PATH.$var)){
							$freecatalog_ptoto[] = array('photoname'=>$_POST['photo_desc'][$k],'photourl'=>$var);
						}
					}
					$cache->write($fns, $freecatalog_ptoto,'freecatalog_ptoto');
				}
				$cache->write($fn, $freecatalog,'freecatalog');
				$this->jump('',0,'保存成功！'); exit;
			}
			
			$freecatalog = array();
			file_exists($fn) ? require_once($fn) : "";
			file_exists($fns) ? require_once($fns) : "";
			$this->set('freecatalog',$freecatalog);
			$this->set('freecatalog_ptoto',$freecatalog_ptoto);
		
			$this->template('freecataloginfo');
		}

    //ajax商品分类删除
	function ajax_cate_dels($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		@set_time_limit(300); //最大运行时间
		foreach($id_arr as $id){
			$getid = $this->action('common','get_goods_sub_cat_ids',$id); //子分类
			if(!empty($getid)){
				foreach($getid as $id){
				   //删除数据库信息
                                    //非法ID不允许删除
                                    if(Import::basic()->int_preg($id)){
                                            //删除分类下的商品
                                            //$this->App->delete('goods','cat_id',$id);
                                            //删除指定分类
                                            $this->App->delete('goods_cate','cat_id',$id);
                                            //删除商品评论
                                            $cids = $this->action('common','get_goods_sub_cat_ids',$id);
                                            $gids = $this->App->findcol("SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE cat_id IN(".implode(',',$cids).")");
                                            if(!empty($gids)){
                                                //$sql = "DELETE FROM `{$this->App->prefix()}comment` WHERE id_value IN(".implode(',',$gids).")";
                                                //$this->App->query($sql);
												$this->ajax_delgoods($gids);
                                            }

                                    }
				}
			}
		}//end foreach
				
		$this->action('system','add_admin_log','删除商品分类:ID为=>'.@implode(',',$id_arr));
                unset($id_arr);
	}

    //ajax激活操作
    function ajax_brand_active($data=array()){
		if(empty($data['bid'])) die("非法操作，ID为空！");
		if($data['type']=='is_show'){
			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改商品品牌显示状态:ID为=>'.$data['cid']);
		}
		$this->App->update('brand',$sdata,'brand_id',$data['bid']);
		unset($data);
	}

      //ajax分类激活与是否显示在导航栏
     function ajax_cate_active($data=array()){
              if(empty($data['cid'])) die("非法操作，ID为空！");
				if($data['type']=='show_in_nav'){
			//添加到导航栏表
			$sql = "SELECT cid FROM `{$this->App->prefix()}nav` WHERE cid ='{$data[cid]}' AND ctype ='gc' LIMIT 1";
			$checkvar = $this->App->findvar($sql);
			$sdata['show_in_nav']= $data['active'];

			if(empty($checkvar)){
				$cdata['ctype'] = 'gc';
				$cdata['cid'] = $data['cid'];
				$cdata['name'] = $this->App->findvar("SELECT cat_name FROM `{$this->App->prefix()}goods_cate` WHERE cat_id ='{$data[cid]}' LIMIT 1");
				$cdata['is_show'] = 1;
				$cdata['is_opennew'] = 0;
				$cdata['url'] = 'catalog.php?cid='.$data['cid'];
				$this->App->insert('nav',$cdata);
				unset($cdata);
			 }else{
			 	//$this->App->delete('nav','cid',$checkvar);
				$sql = "DELETE FROM `{$this->App->prefix()}nav` WHERE cid='$checkvar' AND ctype='gc'";
			 	$this->App->query($sql);
			 }

			$this->action('system','add_admin_log','修改商品分类是否显示在导航栏:ID为=>'.$data['cid']);
		}else if($data['type']=='is_show'){

			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改商品分类状态:ID为=>'.$data['cid']);
		}else if($data['type']=='is_index'){

			$sdata['is_index']= $data['active'];
			$this->action('system','add_admin_log','修改商品分类状态:ID为=>'.$data['cid']);
		}else{
			die('没有指派类型！');
		}
		$this->App->update('goods_cate',$sdata,'cat_id',$data['cid']);
		unset($data,$sdata);
        }

   //ajax保存搜索的关键字
	function set_search_keyword($val=""){
		$fn = SYS_PATH.'data/search_keyword.php';
		if(!empty($val)){
			$val = str_replace(array('.','。','，'),',',$val);
			$search_keys = explode(',',$val);
			$cache = Import::ajincache();
			$cache->write($fn, $search_keys,'search_keys');
			die("保存成功！");
		}
		$search_keys = array();
		file_exists($fn) ? require_once($fn) : "";
		$this->set('search_keys',$search_keys);
		$this->template('set_search_keyword');
	}
	
	function ajax_brand_order($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['sort_order'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('brand',$sdata,'brand_id',$data['id']);
	}
	
	
	/***********  look 添加 开始   *************************************************/
        //ajax产品批量操作（'审核产品'修改商品价格）
	function ajax_goods_order_market($data=array()){
		if(empty($data['id'])) return "1";
		$sdata['market_price'] = empty($data['val']) ? 1 : $data['val'];
		$this->App->update('goods',$sdata,'goods_id',$data['id']);
	}
	
	function ajax_goods_order_shop($data=array()){
		if(empty($data['id'])) return "1";
		$sdata['shop_price'] = empty($data['val']) ? 1 : $data['val'];
	
		$this->App->update('goods',$sdata,'goods_id',$data['id']);
	}
	
	function ajax_goods_order_pifa($data=array()){
		if(empty($data['id'])) return "1";
		$sdata['pifa_price'] = empty($data['val']) ? 1 : $data['val'];
	
		$this->App->update('goods',$sdata,'goods_id',$data['id']);
	}
	
	/***********  look 添加 结束   *************************************************/	


	//ajax上传缓存中的图片
	function ajax_upload_cache_photo($data=array()){
		if(empty($data['filename'])){
			die("非法！");
		}
		@set_time_limit(600); //最大运行时间
		$cid = empty($data['cid'])? 0 : $data['cid'];
		$bid = empty($data['bid'])? 0 : $data['bid'];
		$pathname = explode('++',$data['pathname']);
		$filename = explode('++',$data['filename']);
		$uploadname = explode('++',$data['uploadname']);
		$items = explode('++',$data['items']);
		$price = explode('++',$data['price']);  //初步供应价
		$vipprice = explode('++',$data['vipprice']);  //初步出售价
		$offprice = explode('++',$data['offprice']);  //折扣价
		##########商品的属性#########
		$str_spec = explode('++',$data['str_spec']);
		##########商品相册###########
		if(isset($data['mygallery'])){
			$json = Import::json();
			$gallery = $json->decode($data['mygallery']); //反json ,返回值为对象
			$photo_gallery_desc = $gallery->photo_gallery_desc;
			$photo_gallery_item_id = $gallery->photo_gallery_item_id;
			$photo_gallery_url = $gallery->photo_gallery_url;
			
			$photo_gallery_desc_ar = explode('++',$photo_gallery_desc);
			$photo_gallery_item_id_ar = explode('++',$photo_gallery_item_id);
			$photo_gallery_url_ar = explode('++',$photo_gallery_url);
			$rtdata = array();
			if(!empty($photo_gallery_url_ar)){
				foreach($photo_gallery_url_ar as $kk=>$url){
					if(empty($url)) continue;
					$rtdata[$photo_gallery_item_id_ar[$kk]][] = array('img_desc'=>$photo_gallery_desc_ar[$kk],'img_url'=>$url);
				}
			}
		} // end if
			
		$ds = array();
		if(!empty($str_spec)){
			$item_ar = array();
			foreach($str_spec as $item){
				$ar = @explode('---',$item);
				if(count($ar)==2){
					$key = $ar[0];
					$val = $ar[1];
					$item_ar[trim($key)][] = trim($val);
				}else{
					continue;
				}
				unset($item,$ar);
			}
			$attr_id = $item_ar['attr_id'];
			$attr_value = $item_ar['attr_value'];
			$attr_addi = $item_ar['attr_addi'];
			if(!empty($attr_value)){
				foreach($attr_value as $k=>$vv){
					if(empty($vv)) continue; 
					$ds[] = array('attr_value'=>$vv,'attr_addi'=>$attr_addi[$k],'attr_id'=>$attr_id[$k]);
				}
			}
		}
		$datas = array();
		$imgobj = Import::img();
		foreach($filename as $k=>$name){
			$gid = $this->App->findvar("SELECT MAX(goods_id) + 1 FROM `{$this->App->prefix()}goods`");
			$gid = empty($gid) ? 1 : $gid;
			$goods_sn = 'GZFH' . str_repeat('0', 6 - strlen($gid)) . $gid;
			$datas['goods_sn'] = $goods_sn;
			$datas['cat_id'] = $cid;
			$datas['brand_id'] = $bid;
			$datas['market_price'] = empty($price[$k])? '0.00' : $price[$k];  //供应价
			$datas['shop_price'] = empty($vipprice[$k])? '0.00' : $vipprice[$k]; //出售价
			$datas['pifa_price'] = empty($offprice[$k])? '0.00' : $offprice[$k]; //折扣价
			$datas['goods_name'] = $filename[$k];
			$datas['is_on_sale'] = 1; //默认不上架
			if(!empty($pathname[$k])){
				$imgobj->filecopy($pathname[$k],$uploadname[$k]);
			}else{
				continue;
			}
			if(!file_exists($uploadname[$k])){
				continue;
			}
			$thumb = basename($uploadname[$k]);
			$pa = 'photos/goods/'.date('Ym',mktime()).'/';
			$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
			$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
			$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
			$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
			$datas['original_img'] = $pa.$thumb;	//原始图
			Import::img()->thumb($uploadname[$k],dirname($uploadname[$k]).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
			$datas['goods_thumb'] = $pa.'thumb_s/'.$thumb;
			Import::img()->thumb($uploadname[$k],dirname($uploadname[$k]).DS.'thumb_b'.DS.$thumb,$tw_b,$th_b); //大缩略图
			$datas['goods_img'] = $pa.'thumb_b/'.$thumb;
											
			$datas['add_time'] = mktime();
			$this->App->insert('goods',$datas);
			$lastid = $this->App->iid();

			##########商品属性###########
			if(!empty($ds)){
				foreach($ds as $irow){
					 $irow['goods_id'] = $lastid;
					 $this->App->insert('goods_attr',$irow);
				}
			}
			##########商品相册图片添加########
			if(isset($rtdata[$items[$k]]) && !empty($rtdata[$items[$k]])){
				foreach($rtdata[$items[$k]] as $rows){
					$rows['goods_id'] = $lastid;
                    $this->App->insert('goods_gallery',$rows);
				}
			}
		}
		//print_r($dd);
		unset($pathname,$filename,$uploadname,$items,$price,$vipprice,$photo_gallery_desc_ar,$photo_gallery_item_id_ar,$photo_gallery_url_ar,$str_spec,$data);
	}
	
	//提取目录模块删除
	function ajax_catalog_dels($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;
			
		$this->App->delete('freecatalog','mes_id',$id_arr);
		echo "";
	}
	
	//ajax还原商品
	function ajax_redugoods($data=array()){
		$ids = $data['ids'];
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;
			
		foreach($id_arr as $gid){
			$this->App->update('goods',array('is_delete'=>'0'),'goods_id',$gid);
		}
		exit;	
	}
	
	//ajax删除商品
	function ajax_delgoods($ids=0,$tt=""){
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;
		
		//加入回收站
		if($tt=='1'){ 
			$sql = "UPDATE `{$this->App->prefix()}goods` SET is_delete = '1' WHERE goods_id IN(".@implode(',',$id_arr).")";
			$this->App->query($sql);
			exit;
		}
		
		$sql = "SELECT goods_thumb, goods_img, original_img FROM `{$this->App->prefix()}goods` WHERE goods_id IN(".@implode(',',$id_arr).")";
		$imgs = $this->App->find($sql);
		if(!empty($imgs)){
			foreach($imgs as $row){
				if(!empty($row['goods_thumb']))
					Import::fileop()->delete_file(SYS_PATH.$row['goods_thumb']); //
				if(!empty($row['goods_img']))
					Import::fileop()->delete_file(SYS_PATH.$row['goods_img']); //
				if(!empty($row['original_img']))
					Import::fileop()->delete_file(SYS_PATH.$row['original_img']); //
			}
			unset($imgs);
		}
		
		//商品相册
		$sql = "SELECT img_url FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id IN(".@implode(',',$id_arr).")";
		$gallery_img = $this->App->findcol($sql);
		if(!empty($gallery_img)){
			foreach($gallery_img as $img){
				$q = dirname($img);
				$h = basename($img);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$img); //
			}
			unset($gallery_img);
		}
		
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id)){
			  if($this->App->delete('goods','goods_id',$id)){ //删除商品
					  $this->App->delete('comment','id_value',$id); //删除商品评论
					  $this->App->delete('goods_attr','goods_id',$id); //删除该属性的商品属性
					  $this->App->delete('goods_gallery','goods_id',$id); //删除商品相册
					  $this->App->delete('goods_collect','goods_id',$id); //删除商品收藏
					  $this->App->delete('goods_gift','goods_id',$id); //删除商品礼包
					  $this->App->delete('goods_user_price','goods_id',$id); //删除会员价格
					  $this->App->delete('category_sub_goods','goods_id',$id); //删除子分类商品
					   $this->App->delete('suppliers_goods','goods_id',$id); //删除供应商商品
			   }
		  }
		}
		$this->action('system','add_admin_log','删除商品：'.@implode(',',$id_arr));
		return true;
	}
	
	//ajax删除供应商商品
	function ajax_delgoods_suppliers($data=array()){
		$ids = isset($data['ids']) ? $data['ids'] : "";
		$tt = isset($data['reduction']) ? $data['reduction'] : "1";
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arrs = @explode('+',$ids);
			if(!empty($id_arrs))foreach($id_arrs as $id){
				$ar = @explode('-',$id);
				$id_arr[] = $ar[0];
				$sid[] = $ar[1];
			}
		else{
			if(!empty($ids))foreach($ids as $id){
				$ar = @explode('-',$id);
				$id_arr[] = $ar[0];
				$sid[] = $ar[1];
			}
		}
		if(isset($data['type']) && $data['type']=='check'){
			$sql = "UPDATE `{$this->App->prefix()}suppliers_goods` SET is_check = '1' WHERE goods_id IN(".@implode(',',$id_arr).") AND suppliers_id IN(".@implode(',',$sid).")";
			$this->App->query($sql);
			$this->action('system','add_admin_log','将供应商商品批量审核：'.@implode(',',$id_arr));
			exit;
		}else{
			//加入回收站
			if($tt=='1'){ 
				$sql = "UPDATE `{$this->App->prefix()}suppliers_goods` SET is_delete = '1' WHERE goods_id IN(".@implode(',',$id_arr).") AND suppliers_id IN(".@implode(',',$sid).")";
				$this->App->query($sql);
				
			}
			$this->action('system','add_admin_log','将供应商商品加入回收站：'.@implode(',',$id_arr));
			exit;
		}
		return true;
	}
	
	//删除商品评论
      function ajax_del_comment($ids=0){
            if(empty($ids)) die("非法删除，删除ID为空！");
            $id_arr = @explode('+',$ids);

            foreach($id_arr as $id){
		  	if(Import::basic()->int_preg($id))
		  		$this->App->delete('comment','comment_id',$id);
            }
			$this->action('system','add_admin_log','删除商品评论：'.$ids);
        }
	
	//删除商品属性
	function ajax_attribute_del($id=0){
		if(empty($id)||!($id>0)) die("非法删除，删除ID非法！");
		if($this->App->delete('attribute','attr_id',$id)){
			$sql = "SELECT attr_addi FROM `{$this->App->prefix()}goods_attr` WHERE attr_id='$id'";
			$attrs = $this->App->findrow($sql);
			if(!empty($attrs)){
				foreach($attrs as $val){
					if(empty($val)) continue;
					if(is_file(SYS_PATH.$val)){
						Import::fileop()->delete_file(SYS_PATH.$val); //
					}
				}
				unset($attrs);
			}
			$this->App->delete('goods_attr','attr_id',$id); //删除该属性的商品属性
			$this->action('system','add_admin_log','删除商品属性'.$id);
		}
	}

        //删除商品的子分类
        function ajax_del_subcate($data=array()){
            if(empty($data['cid'])|| empty($data['gid'])) die("传送的ID为空！");
			$cid = $data['cid'];
			$gid = $data['gid'];
			$sql = "DELETE FROM `{$this->App->prefix()}category_sub_goods` WHERE cat_id='$cid' AND goods_id='$gid'";
			if($this->App->query($sql)){
				echo "";
			}else{
				die("删除中发生意外错误！");
			}
        }

	//删除商品属性下的商品
	function ajax_goods_attr_del($id=0){
		if(empty($id)||!($id>0)) die("非法删除，删除ID非法！");
		$sql = "SELECT attr_addi FROM `{$this->App->prefix()}goods_attr` WHERE goods_attr_id='$id'";
		$attr_addi = $this->App->findvar($sql);
		if(!empty($attr_addi)){
			if(is_file(SYS_PATH.$attr_addi)){
				Import::fileop()->delete_file(SYS_PATH.$attr_addi); //
			}
			unset($attr_addi);
		}
		$this->App->delete('goods_attr','goods_attr_id',$id); //删除该属性的商品属性
		$this->action('system','add_admin_log','删除商品属性下的属性'.$id);
	}
	
	//删除商品相册图片
	function ajax_delgallery_photo($id=0){
		if(empty($id)||!($id>0)) die("非法删除，删除ID非法！");
		$sql = "SELECT img_url FROM `{$this->App->prefix()}goods_gallery` WHERE img_id='$id'";
		$img_url = $this->App->findvar($sql);
		if(!empty($img_url)){
				$q = dirname($img_url);
				$h = basename($img_url);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$img_url); //
				unset($img_url);
		}
		if($this->App->delete('goods_gallery','img_id',$id)){
			$this->action('system','add_admin_log','删除商品相册'.$id);
		}
		return;
	}
	
	//商品上下架
	function ajax_activeop($data=array()){
		if(empty($data['gid'])) die("非法操作，ID为空！");
		$type = $data['type']; 
		switch($type){
			case 'is_on_sale':
				$sdata['is_on_sale']= $data['active'];
				$this->action('system','add_admin_log','修改上架状态:ID为=>'.$data['gid']);
				break;
			case 'is_check':
				$sdata['is_check']= $data['active'];
				$this->action('system','add_admin_log','修改商品审核状态:ID为=>'.$data['gid']);
				break;
			case 'is_hot':
				$sdata['is_hot']= $data['active'];
				$this->action('system','add_admin_log','修改商品热销状态:ID为=>'.$data['gid']);
				break;
			case 'is_new':
				$sdata['is_new']= $data['active'];
				$this->action('system','add_admin_log','修改商品新品状态:ID为=>'.$data['gid']);
				break;
			case 'is_best':
				$sdata['is_best']= $data['active'];
				$this->action('system','add_admin_log','修改商品精品状态:ID为=>'.$data['gid']);
				break;
			case 'is_promote':
				//是否是第一次设置
				$sql = "SELECT promote_start_date,promote_end_date,promote_price,pifa_price FROM `{$this->App->prefix()}goods` WHERE goods_id='$data[gid]'";
				$rl = $this->App->findrow($sql);
				if($rl['promote_start_date']=='0' || $rl['promote_end_date']=='0'){
					$sdata['promote_price'] = $rl['pifa_price'];
					$sdata['promote_end_date'] = mktime()+7*24*3600;
					$sdata['promote_start_date'] = mktime();
				}
				$sdata['is_promote'] = $data['active'];
				$this->action('system','add_admin_log','修改商品促销状态:ID为=>'.$data['gid']);
				break;
			case 'is_qianggou':
				//是否是第一次设置
				$sql = "SELECT qianggou_start_date,qianggou_end_date FROM `{$this->App->prefix()}goods` WHERE goods_id='$data[gid]'";
				$rl = $this->App->findrow($sql);
				if($rl['qianggou_start_date']=='0' || $rl['qianggou_end_date']=='0'){
					$sdata['qianggou_start_date'] = mktime();
					$sdata['qianggou_end_date'] = mktime()+7*24*3600;
				}
				$sdata['is_qianggou']= $data['active'];
				$this->action('system','add_admin_log','修改商品抢购状态:ID为=>'.$data['gid']);
				break;
			case 'is_alone_sale':
				$sdata['is_alone_sale']= $data['active'];
				$this->action('system','add_admin_log','修改商品礼品状态:ID为=>'.$data['gid']);
				break;
		}
		
		$this->App->update('goods',$sdata,'goods_id',$data['gid']);
		unset($data,$sdata);
	}
	
	//供应商商品上下架
	function ajax_activeop_suppliers($data=array()){
		if(empty($data['gid'])) die("非法操作，ID为空！");
		$type = $data['type']; 
		$ar = @explode('-',$data['gid']);
		$ggid = isset($ar[0]) ? $ar[0] : 0;
		$sid = isset($ar[1]) ? $ar[1] : 0;
		switch($type){
			case 'is_on_sale':
				$sdata['is_on_sale']= $data['active'];
				$this->action('system','add_admin_log','修改供应商品上架状态:ID为=>'.$data['gid']);
				break;
			case 'is_check':
				$sdata['is_check']= $data['active'];
				$this->action('system','add_admin_log','修改供应商品审核状态:ID为=>'.$data['gid']);
				break;
			
		}
		
		$this->App->update('suppliers_goods',$sdata,array("goods_id='$ggid'","suppliers_id='$sid'"));
		unset($data,$sdata);
	}
	
	//商品评论状态激活
	function ajax_active_comment($data=array()){ 
			if(empty($data['cid'])) die("非法操作，ID为空！");
			$sdata['status']= $data['active'];
			$this->action('system','add_admin_log','修改商品评论审核状态:ID为=>'.$data['cid']);
			$this->App->update('comment',$sdata,'comment_id',$data['cid']);
			unset($data,$sdata);
	}
		
	//商品分类排序
	function ajax_catesort($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['sort_order'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('goods_cate',$sdata,'cat_id',$data['id']);
		unset($data,$sdata);
	}
	
	//商品属性排序
	function ajax_attrsort($data=array()){
		if(empty($data['id'])) return "1";
		$sdata['sort_order'] = empty($data['val']) ? 1 : $data['val'];
		$this->App->update('attribute',$sdata,'attr_id',$data['id']);
		unset($data,$sdata);
	}
	/**
	 *  返回一个随机的名字
	 *
	 * @access  public
	 * @param
	 *
	 * @return      string      $str    随机名称
	 */
	
	function upload_random_name()
	{
		$str = date('Ymd');

		for ($i = 0; $i < 6; $i++)
		{
			$str .= chr(mt_rand(97, 122));
		}
		$str .= mktime();
		return $str;
	}
	
	function ajax_cate_name($rt=array()){
		$name = $rt['searchval'];
		if(empty($name)) die('');
		$sql = "SELECT cat_id FROM `{$this->App->prefix()}goods_cate` WHERE cat_name LIKE '%$name%' LIMIT 1";
		$vv = $this->App->findvar($sql);
		if(empty($vv)) die("");
		$catelist = $this->action('common','get_goods_cate_tree');
		$str = '';
		foreach($catelist as $row){
			$str .= '<option value="'.$row[id].'"'.($row[id]==$vv ? ' selected="selected"':'').'>'.$row[name].'</option>';
			if(!empty($row['cat_id'])) foreach($row['cat_id'] as $rows){
				$str .= '<option value="'.$rows[id].'"'.($rows[id]==$vv ? ' selected="selected"':'').'>&nbsp;&nbsp;'.$rows[name].'</option>';
				if(!empty($rows['cat_id'])) foreach($rows['cat_id'] as $rowss){
					$str .= '<option value="'.$rowss[id].'"'.($rowss[id]==$vv ? ' selected="selected"':'').'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rowss[name].'</option>';
					if(!empty($rowss['cat_id'])) foreach($rowss['cat_id'] as $rowsss){
						$str .= '<option value="'.$rowsss[id].'"'.($rowsss[id]==$vv ? ' selected="selected"':'').'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rowsss[name].'</option>';
					}
				}
			}
		}
		echo $str;exit;
	}
	
	//ajax更改商品信息
	function ajax_update_goods_info($data=array()){
		$gid = $data['gid'];
		$val = $data['val'];
		$type = $data['type'];
		if(!($gid>0) || empty($val)) exit;
		$dd = array();
		switch($type){
			case 'goods_biaohao':
				$dd['goods_biaohao'] = $val;
				break;
			case 'goods_name':
				$dd['goods_name'] = $val;
				break;
			case 'sort_order':
				$dd['sort_order'] = $val;
				break;
		}
		if(!empty($dd)){
			$this->App->update('goods',$dd,'goods_id',$gid);
		}
		exit;
	}
	
	//ajax更改商品信息
	function ajax_update_goods_info_suppliers($data=array()){
		$gid = $data['gid'];
		$ar = @explode('-',$gid);
		$ggid = $ar[0];
		$sid = $ar[1];
		$val = $data['val'];
		$type = $data['type'];
		if(!($gid>0) || empty($val)) exit;
		$dd = array();
		switch($type){
			case 'market_price':
				$dd['market_price'] = $val;
				break;
			case 'shop_price':
				$dd['shop_price'] = $val;
				break;
			case 'pifa_price':
				$dd['pifa_price'] = $val;
				break;
			case 'goods_number':
				$dd['goods_number'] = $val;
				break;
			case 'warn_number':
				$dd['warn_number'] = $val;
				break;
		}
		if(!empty($dd)){
			$this->App->update('suppliers_goods',$dd,array("goods_id='$ggid'","suppliers_id='$sid'"));
		}
		exit;
	}
	
	function zhuanyi_goods(){
		if(isset($_GET['kk']) && isset($_GET['maxpage'])){
			 $imgobj = Import::img();
			 
			 $kk = $_GET['kk'];
			 
			 $list = 20;
			 if($kk==0){
				$tt = $this->App->findvar("SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods`");
				$maxpage = ceil($tt/$list);
			 }else{
				$maxpage = $_GET['maxpage'];
			 }
			
			 $start = $kk*$list;
			 
			 $sql = "SELECT g.*,u.user_name FROM `{$this->App->prefix()}goods` AS g LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = g.uid LIMIT $start,$list";
			 $rt = $this->App->find($sql);
			 $str = "";
			 if(!empty($rt))foreach($rt as $row){
			 	
				//检查是否已经存在该记录
				if($row['uid']>0){
					$sgid = $this->App->findvar("SELECT sgid FROM `{$this->App->prefix()}suppliers_goods` WHERE suppliers_id='$row[uid]' AND goods_id='$row[goods_id]'");
					if(empty($sgid)){
						$this->App->insert('suppliers_goods',array('suppliers_id'=>$row['uid'],'goods_id'=>$row['goods_id'],'market_price'=>$row['market_price'],'shop_price'=>$row['shop_price'],'pifa_price'=>$row['pifa_price'],'is_on_sale'=>$row['is_on_sale'],'is_delete'=>$row['is_delete'],'is_check'=>$row['is_check'],'addtime'=>mktime()));
						$str .='转移=>供应商['.$row['user_name'].'] goods_id['.$row['goods_id'].'] 供应价['.$row['market_price'].'] 零售价['.$row['shop_price'].'] 批发价['.$row['pifa_price'].']'."<br/>";
					}else{
						//$this->App->update('suppliers_goods',array('suppliers_id'=>$row['uid'],'goods_id'=>$row['goods_id'],'market_price'=>$row['market_price'],'shop_price'=>$row['shop_price'],'pifa_price'=>$row['pifa_price'],'is_on_sale'=>$row['is_on_sale'],'is_delete'=>$row['is_delete'],'addtime'=>mktime()),array("goods_id='$row[goods_id]'","suppliers_id='$row[uid]'"));
						$str .="该商品已经存在=goods_id:".$row['goods_id']."，正在更新！<br/>";
					}
				}else{
					$str .="没有指定供应商！<br/>";
				}
			 }
			 
			 $kk = $kk+1;
		 
			 $str .= "<font color=red>==============Load.....page(".$kk.")================</font><br />";
	
			 if($kk>$maxpage){
				$kk="";
			 }
			 sleep(2);
			 $rts = array('kk' => $kk,'url'=>$str,'maxpage'=>$maxpage);
			 die(Import::json()->encode($rts));
		} 
		$this->template('zhuanyi_goods');
	}
}
?>