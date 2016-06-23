<?php
class CuxiaoController extends Controller{
 	function  __construct() {
		$this->css('content.css');
	}
	
	/********1元夺宝**********/
	function duobao(){
		
		$this->template('duobao');
	}
	
		
	//生成单品推广二维码
	function ajax_mark_erweima($data=array()){
		$ym = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
		
		$uid = $data['uid'];
		$gid = $data['gid'];
		
		include(SYS_PATH.'inc/phpqrcode.php');
		
		$thisurl = SITE_URL."m/product.php?id=".$gid."&tid=".$uid;
		
		$filename = SYS_PATH_PHOTOS.$ym.DS.'qgoods'.DS.$gid.DS.$uid.'.png';
		
		// 生成的文件名
		Import::fileop()->checkDir($filename);
		
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'L';
		// 点的大小：1到10
		$matrixPointSize = 6;
		QRcode::png($thisurl, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

		$img = SITE_URL.'photos/'.$ym.'/qgoods/'.$gid.'/'.$uid.'.png';
		echo '<img src="'.$img.'" style="width:150px;padding:1px;border:1px solid #ccc" alt="" />';
		
		$dd = array();
		$dd['uid'] = $uid;
		$dd['goods_id'] = $gid;
		$dd['url'] = $img;
		$dd['time'] = mktime();
		
		$sql = "SELECT id FROM `{$this->App->prefix()}cx_saogoods` WHERE uid='$uid' AND goods_id='$gid' LIMIT 1";
		$id = $this->App->findvar($sql);
		if($id > 0){
			$this->App->update('cx_saogoods',$dd,'id',$id);
		}else{
			$this->App->insert('cx_saogoods',$dd);
		}
		exit;
	}
	
	//扫码的推广产品
	function saogoods(){
		$id= isset($_GET['id']) ? $_GET['id'] : '';
		if($id > 0){
			$this->App->delete('cx_saogoods','id',$id);
			$this->jump(ADMIN_URL.'cuxiao.php?type=saogoods'); exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
			
		$list = 20;
	    $start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_saogoods`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb,tb3.nickname FROM `{$this->App->prefix()}cx_saogoods` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb1.uid ORDER BY id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set("rt",$rt);
				 
		$this->template('saogoods');
	}
	
	function saogoods_info(){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		 
		 $id = isset($_GET['id']) ? $_GET['id'] : 0;
		 if($id > 0){
		 	$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb,tb3.nickname FROM `{$this->App->prefix()}cx_saogoods` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb1.uid WHERE  id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);
		 }
		$this->template('saogoods_info');
	}
	
	/********整点抢购**********/
	function qianggou(){
		$id= isset($_GET['id']) ? $_GET['id'] : '';
		if($id > 0){
			$this->App->delete('cx_qianggou','id',$id);
			$this->jump(ADMIN_URL.'cuxiao.php?type=qianggou'); exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
			
		$list = 20;
	    $start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_qianggou`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.sale_count FROM `{$this->App->prefix()}cx_qianggou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id ORDER BY id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set("rt",$rt);
				 
		$this->template('qianggou');
	}
	
	function qg_info(){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		 
		 $id = isset($_GET['id']) ? $_GET['id'] : 0;
		 if($id > 0){
		 	 if(!empty($_POST)){
			 		if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->update('cx_qianggou',$_POST,'id',$id)){
						$this->jump('',0,'更新成功'); exit;
					}else{
						$this->jump('',0,'更新失败'); exit;
					}
			 }
		 	$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb FROM `{$this->App->prefix()}cx_qianggou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE  id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);
		 }else{
		 	if(!empty($_POST)){
					$_POST['time'] = mktime();
					if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->insert('cx_qianggou',$_POST)){
						$this->jump('',0,'添加成功'); exit;
					}else{
						$this->jump('',0,'添加失败'); exit;
					}
			 }
		 }
		 
		
		$this->template('qianggou_info');
	}
	
	/********大牌惠**********/
	function dapaihui(){
		$id= isset($_GET['id']) ? $_GET['id'] : '';
		if($id > 0){
			$this->App->delete('cx_dapaihui','id',$id);
			$this->jump(ADMIN_URL.'cuxiao.php?type=dapaihui'); exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
			
		$list = 20;
	    $start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_dapaihui`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT * FROM `{$this->App->prefix()}cx_dapaihui` ORDER BY id DESC LIMIT 1";
		$rt = $this->App->find($sql);
/*		$rt = array();
		if(!empty($rt_))foreach($rt_ as $k=>$row){
			$rt[$k] = $row;
			$gids = $row['gids'];
			$sql = "SELECT goods_name,goods_thumb,goods_id FROM `{$this->App->prefix()}goods` WHERE goods_id IN(".str_replace('-',',',$gids).")";
			$rt[$k]['ginfo'] = $this->App->find($sql);
		}
		unset($rt_);	*/
		
		$this->set("rt",$rt);
			 
		$this->template('dapaihui');
	}
	
	function dph_info(){
		 $this->js(array('time/WdatePicker.js',"edit/kindeditor.js","jquery_dialog.js"));
		 $this->css('jquery_dialog.css');

		//分类列表
		 $this->set('catelist',$this->action('common','get_goods_cate_tree'));
		 //品牌列表
		 $this->set('brandlist',$this->action('common','get_brand_cate_tree'));	
		 
		 if(!empty($_POST)){
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
		 }
		 
		 $id = isset($_GET['id']) ? $_GET['id'] : 0;
		 if($id > 0){
		 	 if(!empty($_POST)){
			 		if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
					
			 		if($this->App->update('cx_dapaihui',$_POST,'id',$id)){
						$this->jump('',0,'更新成功'); exit;
					}else{
						$this->jump('',0,'更新失败'); exit;
					}
			 }
		 	$sql = "SELECT * FROM `{$this->App->prefix()}cx_dapaihui` WHERE id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			if(!empty($rt['gids'])){
				$sql = "SELECT goods_name,goods_thumb,goods_id FROM `{$this->App->prefix()}goods` WHERE goods_id IN(".str_replace('-',',',$rt['gids']).")";
				$rt['ginfo'] = $this->App->find($sql);
			}
			
			$this->set('rt',$rt);
		 }else{
		 	if(!empty($_POST)){
					$_POST['time'] = mktime();
					if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
					$gids = explode('-',$_POST['gids']);
					$ts = array();
					foreach($gids as $gid){
						if($gid > 0){
							$ts[] = $gid;
						}
					}
					if(empty($ts)){
						$this->jump('',0,'请先选择产品！'); exit;
					}
					$_POST['gids'] = implode('-',$ts);
					
			 		if($this->App->insert('cx_dapaihui',$_POST)){
						$this->jump('',0,'添加成功'); exit;
					}else{
						$this->jump('',0,'添加失败'); exit;
					}
			 }
		 }
		 
		
		$this->template('dapaihui_info');
	}
	
	//开通分销商的产品
	function fenxiao_goods(){
		$id= isset($_GET['id']) ? $_GET['id'] : '';
		if($id > 0){
			$this->App->delete('cx_fxgoods','id',$id);
			$this->jump(ADMIN_URL.'cuxiao.php?type=fenxiao_goods'); exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
			
		$list = 20;
	    $start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_fxgoods`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb,tb2.pifa_price FROM `{$this->App->prefix()}cx_fxgoods` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id ORDER BY id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set("rt",$rt);
				 
		$this->template('fenxiao_goods');
	}
	
	function fenxiao_goods_info(){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		 
		 $id = isset($_GET['id']) ? $_GET['id'] : 0;
		 if($id > 0){
		 	 if(!empty($_POST)){
			 		if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->update('cx_fxgoods',$_POST,'id',$id)){
						$this->jump('',0,'更新成功'); exit;
					}else{
						$this->jump('',0,'更新失败'); exit;
					}
			 }
		 	$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb FROM `{$this->App->prefix()}cx_fxgoods` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE  id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);
		 }else{
		 	if(!empty($_POST)){
					$_POST['time'] = mktime();
					if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->insert('cx_fxgoods',$_POST)){
						$this->jump('',0,'添加成功'); exit;
					}else{
						$this->jump('',0,'添加失败'); exit;
					}
			 }
		 }
		 
		
		$this->template('fenxiao_goods_info');
	}
	
	/********免费试用**********/
	function freeuse(){
		$id= isset($_GET['id']) ? $_GET['id'] : '';
		if($id > 0){
			$this->App->delete('cx_freeuse','id',$id);
			$this->jump(ADMIN_URL.'cuxiao.php?type=freeuse'); exit;
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
			
		$list = 20;
	    $start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_freeuse`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.goods_name,tb2.sale_count FROM `{$this->App->prefix()}cx_freeuse` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id ORDER BY id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set("rt",$rt);
				 
		$this->template('freeuse');
	}
	
	function freeuse_info(){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		 
		 $id = isset($_GET['id']) ? $_GET['id'] : 0;
		 if($id > 0){
		 	 if(!empty($_POST)){
			 		if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->update('cx_freeuse',$_POST,'id',$id)){
						$this->jump('',0,'更新成功'); exit;
					}else{
						$this->jump('',0,'更新失败'); exit;
					}
			 }
		 	$sql = "SELECT tb1.*,tb2.goods_name,tb2.goods_thumb FROM `{$this->App->prefix()}cx_freeuse` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE  id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);
		 }else{
		 	if(!empty($_POST)){
					$_POST['time'] = mktime();
					if(empty($_POST['title'])){
					$this->jump('',0,'标题不能为空'); exit;
					}
			 		if($this->App->insert('cx_qianggou',$_POST)){
						$this->jump('',0,'添加成功'); exit;
					}else{
						$this->jump('',0,'添加失败'); exit;
					}
			 }
		 }
		 
		
		$this->template('freeuse_info');
	}
	
	//达人推荐
	function darentuijian(){
	
	}
}
?>