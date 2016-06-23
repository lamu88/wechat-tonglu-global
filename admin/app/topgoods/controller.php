<?php
class TopgoodsController extends Controller{
 	function  __construct() {
           $this->css('content.css');
	}
	
	function ajax_cate_goods(){
		$this->css('jquery_dialog.css');
		$this->js(array('jquery_dialog.js'));
		
		$cid = (isset($_GET['cid'])&&intval($_GET['cid'])>0) ? intval($_GET['cid']) : 0;
		$sql = "SELECT tb1.*,tb2.cat_name AS sname,g.goods_name,g.goods_thumb ,g.shop_price,g.pifa_price FROM `{$this->App->prefix()}top_cate_goods` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = tb1.goods_id";
		$sql .=" LEFT JOIN `{$this->App->prefix()}top_cate` AS tb2 ON tb2.tcid = tb1.tcid";
		$sql .=" WHERE tb1.tcid='$cid' OR tb2.parent_id='$cid' ORDER BY tb1.gid DESC";
		$rt = $this->App->find($sql);
		$this->set('rt',$rt);
		
		$sql = "SELECT tb1.cat_name AS subname,tb2.cat_name AS bigname FROM `{$this->App->prefix()}top_cate` AS tb1 LEFT JOIN `{$this->App->prefix()}top_cate` AS tb2 ON tb1.parent_id = tb2.tcid WHERE tb1.tcid='$cid' LIMIT 1";
		$this->set('rts',$this->App->findrow($sql));
		
		$this->template('ajax_cate_goods');
	}
	
	function ajax_del_topgoods($rt=array()){
		$gid = $rt['gid'];
		$sql = "SELECT * FROM `{$this->App->prefix()}top_cate_goods` WHERE gid='$gid'";
		$rts = $this->App->findrow($sql);
		$img = $rts['img'];
		if(!empty($img)){
			Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
			$q = dirname($img);
			$h = basename($img);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
		}
		$this->App->delete('top_cate_goods','gid',$gid);
		exit;
	}
	
	function ajax_del_topgoodscate($rt=array()){
		$tcid = $rt['tcid'];
		if(!($tcid > 0)) die("ID为空");
		
		$sql = "SELECT * FROM `{$this->App->prefix()}top_cate` WHERE tcid='$tcid'";
		$rts = $this->App->findrow($sql);
		
		$img2 = $rts['cat_img2'];
		$img = $rts['cat_img'];
		if(!empty($img2)){
			Import::fileop()->delete_file(SYS_PATH.$img2); //删除图片
			$q = dirname($img2);
			$h = basename($img2);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
		}
		if(!empty($img)){
			Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
			$q = dirname($img);
			$h = basename($img);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
			Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}top_cate_goods` WHERE tcid='$tcid'";
		$rr = $this->App->find($sql);
		if(!empty($rr))foreach($rr as $rows){
			$img = $rows['img'];
			if(!empty($img)){
				Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
				$q = dirname($img);
				$h = basename($img);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
		}
		
		$this->App->delete('top_cate_goods','tcid',$tcid);
		$this->App->delete('top_cate','tcid',$tcid);
		exit;
	}
	
	
    function clist(){
		$this->css('jquery_dialog.css');
		$this->js(array('jquery_dialog.js'));
			
		$id = (isset($_GET['id'])&&intval($_GET['id'])>0) ? intval($_GET['id']) : 0;
		if($id>0){  //删除
			$sql = "SELECT * FROM `{$this->App->prefix()}top_cate` WHERE tcid='$id'";
			$rows = $this->App->findrow($sql);
			if(empty($rows)){
				$this->jump(ADMIN_URL.'topgoods.php?type=clist'); exit;
			}
			$topic_img = SYS_PATH.$rows['topic_img'];
			$topic_flash  = SYS_PATH.$rows['topic_flash '];
			if(file_exists($topic_img)) Import::fileop()->delete_file($topic_img);
			if(file_exists($topic_flash)) Import::fileop()->delete_file($topic_flash);
			//删除数据库内容
			$this->App->delete('topic','topic_id',$id); 
			$this->jump(ADMIN_URL.'topgoods.php?type=clist');
			exit;
		}
		$this->set('catelist',$this->get_goods_cate_tree());
		
		//$this->set('rt',$rt);
	    $this->template('clist');
	}
	
	function cinfo($id=0){
		 if($id > 0){ //编辑页面
                 if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['cat_name'])){
                         echo'<script>alert("名称不能为空！");</script>';
                     }else{
                         $this->App->update('top_cate',$_POST,'tcid',$id);
                         $this->action('system','add_admin_log','修改Topgoods分类:'.$_POST['cat_name']);
                         $this->action('common','showdiv',$this->getthisurl());
                     }
                     $rt = $_POST;
                  }
                $sql = "SELECT * FROM `{$this->App->prefix()}top_cate` WHERE tcid='{$id}' LIMIT 1";
                $rt = $this->App->findrow($sql);
                $this->set('type','edit');

            }else{ //添加页面
                 if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['cat_name'])){
                         echo'<script>alert("名称不能为空！");</script>';
                     }else{
                         $this->App->insert('top_cate',$_POST);
                         $this->action('system','add_admin_log','添加Topgods分类:'.$_POST['cat_name']);
                         $this->action('common','showdiv',$this->getthisurl());
                     }
                     $rt = $_POST;
                 }
                 $this->set('type','add');
            }
		 $this->set('catelist2',$this->action('common','get_goods_cate_tree'));
		 $this->set('rt',$rt);	
		 $this->set('catelist',$this->get_goods_cate_tree());
		 $this->template('cinfo');
	}
	
	function lists(){
		$id = (isset($_GET['id'])&&intval($_GET['id'])>0) ? intval($_GET['id']) : 0;
		if($id>0){  //删除
			$sql = "SELECT * FROM `{$this->App->prefix()}top_cate_goods` WHERE gid='$id'";
			$rows = $this->App->findrow($sql);
			if(empty($rows)){
				$this->jump(ADMIN_URL.'topgoods.php?type=lists'); exit;
			}
			
			$topic_img = SYS_PATH.$rows['img'];
			if(!empty($rows['img']) && is_file($topic_img)){
				Import::fileop()->delete_file($topic_img);
				$q = dirname($rows['img']);
				$h = basename($rows['img']);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
			
			//删除数据库内容
			$this->App->delete('top_cate_goods','gid',$id); 
			$this->jump(ADMIN_URL.'topgoods.php?type=lists',0,'删除成功');
			exit;
		}
		
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(gid) FROM `{$this->App->prefix()}top_cate_goods`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);

		$sql = "SELECT tb1.*,tb2.cat_name AS sname,tb3.cat_name AS bname,g.goods_name,g.goods_thumb FROM `{$this->App->prefix()}top_cate_goods` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = tb1.goods_id";
		$sql .=" LEFT JOIN `{$this->App->prefix()}top_cate` AS tb2 ON tb2.tcid = tb1.tcid";
		$sql .=" LEFT JOIN `{$this->App->prefix()}top_cate` AS tb3 ON tb3.tcid = tb2.parent_id";
		$sql .=" ORDER BY tb2.parent_id ASC,tb1.tcid DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set('rt',$rt);
		
	    $this->template('lists');
	}
	
	
	function info(){
		$this->js(array('jquery.json-1.3.js'));
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if($id>0){
			$sql = "SELECT tb1.*,tb2.cat_name AS sname,tb3.cat_name AS bname,g.goods_name,g.goods_thumb FROM `{$this->App->prefix()}top_cate_goods` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = tb1.goods_id";
			$sql .=" LEFT JOIN `{$this->App->prefix()}top_cate` AS tb2 ON tb2.tcid = tb1.tcid";
			$sql .=" LEFT JOIN `{$this->App->prefix()}top_cate` AS tb3 ON tb3.tcid = tb2.parent_id WHERE tb1.gid='$id'";
			$rt = $this->App->findrow($sql);
			
			//删除原来图片
			$img = $rt['img'];
			if(!empty($img) && !empty($_POST['photo_img'][0]) & $img != $_POST['photo_img'][0]){
				$topic_img = SYS_PATH.$rt['img'];
				if(!empty($rt['img']) && is_file($topic_img)){
					Import::fileop()->delete_file($topic_img);
					$q = dirname($rt['img']);
					$h = basename($rt['img']);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
				}
			}
			
			if(!empty($_POST['photo_name'])){
				foreach($_POST['photo_name'] as $k=>$name){
						 $dd['tcid'] = intval($_POST['cat_id']);
						 $dd['img'] = $_POST['photo_img'][$k];
						 $dd['url'] = $_POST['photo_url'][$k];
						 if($_POST['source_select'][$k]>0) $dd['goods_id'] = $_POST['source_select'][$k];
						 $dd['maxbuy_num'] = $_POST['gid_buy_num'][$k];
						 $dd['gname'] = trim($name);
						 $this->App->update('top_cate_goods',$dd,'gid',$id);
						 
				}
				$this->action('common','showdiv',$this->getthisurl());
			}
			
			$this->set('rt',$rt);
		}else{
			if(!empty($_POST['photo_name'])){
				foreach($_POST['photo_name'] as $k=>$name){
						//if(empty($name)) continue;
						/*$sql = "SELECT gid FROM `{$this->App->prefix()}top_cate_goods` WHERE goods_id='$gid'";
						$ggid = $this->App->findvar($sql);
						if($ggid>0) continue;*/
						 $dd['tcid'] = intval($_POST['cat_id']);
						 $dd['img'] = $_POST['photo_img'][$k];
						 $dd['url'] = $_POST['photo_url'][$k];
						 if($_POST['source_select'][$k]>0) $dd['goods_id'] = $_POST['source_select'][$k];
						 $dd['maxbuy_num'] = $_POST['gid_buy_num'][$k];
						 $dd['gname'] = trim($name);
						 $this->App->insert('top_cate_goods',$dd);
						 
				}
				$this->action('common','showdiv',$this->getthisurl());
			}
		}
		
		//分类列表
		$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		//品牌列表
		$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
		
		$this->set('catelist2',$this->get_goods_cate_tree());
		
		$this->template('info');
	}
	
	//获取商品分类
	function get_goods_cate_tree($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(tcid) FROM `'.$this->App->prefix()."top_cate` WHERE parent_id = '$cid'";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT * FROM `'.$this->App->prefix()."top_cate` WHERE parent_id = '$cid' ORDER BY parent_id ASC, tcid ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['tcid']]['id']   = $row['tcid'];
			   $three_arr[$row['tcid']]['parent_id']   = $row['parent_id'];
			   $three_arr[$row['tcid']]['name'] = $row['cat_name'];
			   $three_arr[$row['tcid']]['url']   = $row['cat_url'];
			  
			   
			    if (!empty($row['tcid']))
				{
					 $three_arr[$row['tcid']]['cat_id'] = $this->get_goods_cate_tree($row['tcid']);
				}
			}
		}
		return $three_arr;
	}
	
	function ajax_searchGoods($data=""){
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();
	
		if (empty($data))
		{
			$result['error'] = 2;
			$result['message'] = '传送的数据为空！';
			die($json->encode($result));
		}
		$art = $json->decode($data); //反json
		$cat_id = $art->cat_id;
		$brand_id = $art->brand_id;
		$keyword = $art->keyword; 
		$comd = array();
		if(intval($cat_id)>0){
			$cids = $this->action('common','get_goods_sub_cat_ids',$cat_id);
            $comd[] = 'cat_id IN('.implode(",",$cids).')';
		}
		if(intval($brand_id)>0){
			$comd[] = "brand='$brand_id'";
		}
		if(!empty($keyword)){
			$comd[] = "goods_name LIKE '%$keyword%'";
		}
		if(empty($comd)){
			$result['error'] = 2;
			$result['message'] = '';
			die($json->encode($result));
		}
		
		$sql = "SELECT goods_id,goods_name FROM `{$this->App->prefix()}goods` WHERE ".implode(' AND ',$comd)." ORDER BY goods_id DESC LIMIT 20";
		$rt = $this->App->find($sql);
		
		$result['error'] = 1;
		$result['message'] = $rt;
		die($json->encode($result));
	
	}
}
?>