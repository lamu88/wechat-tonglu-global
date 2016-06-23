<?php
 /*
 * 这是一个广告处理类
 */
class AdsController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/ 
		$this->css('content.css');
	}
	
	//广告列表页面
	function adslist(){
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
		}else {
		  	  $orderby = ' ORDER BY tb1.uid ASC,tb1.vieworder,tb1.`pid` DESC';
		}
		
		//分页
	    $page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 8;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(pid) FROM `{$this->App->prefix()}ad_content`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
	   $sql ="SELECT tb1.*,tb2.ad_name AS ad_tag ,tb3.nickname FROM `{$this->App->prefix()}ad_content` AS tb1";
	   $sql .=" LEFT JOIN `{$this->App->prefix()}ad_position` AS tb2 ON tb1.tid = tb2.tid";
	   $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid = tb3.user_id";
	   $sql .=" {$orderby} LIMIT $start,$list"; 
	   $this->set('adslist',$this->App->find($sql));
	   $this->template('adslist');
	}
	
	//排序
	function ajax_ads_vieworder($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['vieworder'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('ad_content',$sdata,'pid',$data['id']);
	}
	
	//广告标签列表
	function adstaglist(){
	    $sql = "SELECT tb1.*,COUNT(tb2.tid) AS tids FROM `{$this->App->prefix()}ad_position` AS tb1";
		$sql .= "  LEFT JOIN `{$this->App->prefix()}ad_content` AS tb2";
		$sql .= " ON tb1.tid = tb2.tid";
		$sql .=" GROUP BY tb1.`tid`";
		
		$this->set('adstaglist',$this->App->find($sql));
		$this->template('adstaglist');
	}
	//广告标签详情信息
	function adstag_info($type='adstag_add',$id=0){
	    $rt = array();
		if($type=='adstag_edit'){
		    if(empty($id)){
			  Import::basic()->redirect('ads.php?type=adstaglist'); exit;
			}else{
			  $sql = "SELECT * FROM `{$this->App->prefix()}ad_position` WHERE tid='{$id}' LIMIT 1";
			  $rt = $this->App->findrow($sql);
			}
		}
		$this->set('rt',$rt);
		$this->set('type',$type);
		$this->template('adstag_info');
	}
	//广告详情信息
	function ads_info($type='ads_add',$id=0){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		$rt = array();
		$rt['type'] = "";
		if($type=='ads_edit'){
		    if(empty($id)){
			  Import::basic()->redirect('ads.php?type=adslist'); exit;
			}else{
			  $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE pid='{$id}' LIMIT 1";
			  $rt = $this->App->findrow($sql);
			}
		}
	    $sql = "SELECT ad_name,tid FROM `{$this->App->prefix()}ad_position` WHERE is_show='1'";
		$rts = $this->App->find($sql);
		if($rt['type']=='gc'){
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
		}else{
			$this->set('catelist',$this->action('common','get_cate_tree'));
		}
		$this->set('rts',$rts);
		$this->set('rt',$rt);
		$this->set('type',$type);
		$this->template('ads_info');
	}
	
	#######################
	function ajax_adtags_info($data = array(),$id=0){
		if(!empty($id)){
			$data['uptime'] = time();
		}else{
			$data['addtime'] = time();
		}
		$ad_name = $data['ad_name'];
		$sql = "SELECT ad_name FROM `{$this->App->prefix()}ad_position` WHERE ad_name = '$ad_name' LIMIT 1";
		$a_name = $this->App->findvar($sql);
		if(empty($a_name) || $a_name==$ad_name){
			if(empty($id)){
			  if($this->App->insert('ad_position',$data)){
			  	$this->action('system','add_admin_log','添加广告标签：'.$ad_name);
			  }
			  else{
			    echo "无法添加广告标签，意外错误！";
			  }
			}else{
			  if($this->App->update('ad_position',$data,'tid',$id)){
			  	$this->action('system','add_admin_log','修改广告标签：'.(empty($ad_name) ? "激活状态" : $ad_name));
			  }else{
			    echo "数据未变动，无需更新！";
			  }
			}
		}else{
			echo "广告标签名称已经存在，无法操作！";	 exit;
		}
		exit;
	}
	
	function ajax_deladstag($ids){
		if(empty($ids)) echo "删除ID为空！";
		$arr = explode('+',$ids);
		foreach($arr as $id){
		  $sql ="SELECT ad_img FROM `{$this->App->prefix()}ad_content` WHERE tid='$id'";
		  $imgs = $this->App->findcol($sql);
		  if(!empty($imgs)){
		  	foreach($imgs as $vv){
				if(empty($vv)) continue;
				Import::fileop()->delete_file(SYS_PATH.$vv); //删除文件
					$q = dirname($vv);
					$h = basename($vv);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
		  }
		  $this->App->delete('ad_content','tid',$id);
		  $this->App->delete('ad_position','tid',$id); //删除记录
		}
		$this->action('system','add_admin_log','删除广告标签：ID为'.implode(',',$arr));
	}
	
	function ajax_addads_info($data = array(),$id=0){
		if(!empty($data['tid'])){ //生成指定宽度高度的图片
			$tid = $data['tid'];
			$sql = "SELECT ad_width,ad_height FROM `{$this->App->prefix()}ad_position` WHERE tid='$tid'";
			$rr = $this->App->findrow($sql);
			$width = $rr['ad_width'];
			$height = $rr['ad_height'];
			if(!empty($width) && !empty($height) && !empty($data['ad_img'])){
				if(!empty($id)){
					$sql = "SELECT ad_img FROM `{$this->App->prefix()}ad_content` WHERE pid='{$id}'";
					$var = $this->App->findvar($sql);
					if($data['ad_img']!=$var){
						Import::fileop()->delete_file(SYS_PATH.$var); //删除文件
						$q = dirname($var);
						$h = basename($var);
						Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
						Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
						if(file_exists(SYS_PATH.$data['ad_img'])){
							Import::img()->thumb(SYS_PATH.$data['ad_img'],SYS_PATH.$data['ad_img'],$width,$height);
						}
					}
				}
			}
		}
		
		if(!empty($id)){
			$data['uptime'] = time();
		}else{
			$data['addtime'] = time();
		} 
		$ad_name = $data['ad_name'];
		if(empty($id)){
		  if($this->App->insert('ad_content',$data)){
		  	@file_get_contents(SITE_URL.'data/flashdata/dynfocus/loadjs.php'); //将数据写入文件
			$this->action('system','add_admin_log','添加广告：'.$ad_name);
		  }
		  else{
			echo "无法添加广告，意外错误！";
		  }
		}else{
		  if($this->App->update('ad_content',$data,'pid',$id)){
		  	@file_get_contents(SITE_URL.'data/flashdata/dynfocus/loadjs.php'); //将数据写入文件
			$this->action('system','add_admin_log','修改广告：'.(empty($ad_name) ? "激活状态" : $ad_name));
		  }else{
			echo "数据未变动，无需更新！";
		  }
		}
		exit;
	}
	
	function ajax_delads($ids){
		if(empty($ids)) echo "删除ID为空！";
		$arr = explode('+',$ids);
		
		$sql = "SELECT ad_img FROM `{$this->App->prefix()}ad_content` WHERE pid IN(".@implode(',',$arr).")";
		$imgs = $this->App->findcol($sql);
		if(!empty($imgs)){
		  	foreach($imgs as $vv){
				if(empty($vv)) continue;
				Import::fileop()->delete_file(SYS_PATH.$vv); //删除文件
					$q = dirname($vv);
					$h = basename($vv);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
		}
		
		foreach($arr as $id){
		  $this->App->delete('ad_content','pid',$id);
		}
		$this->action('system','add_admin_log','删除广告：ID为'.implode(',',$arr));
	}
	
	//根据类型获取商品分类的菜单还是文章分类的菜单
	function ajax_getcateoption($type='ac'){
		if($type=='ac'){
			$this->set('catelist',$this->action('common','get_cate_tree')); //文章分类
		}elseif($type=='gc'){
			$this->set('catelist',$this->action('common','get_goods_cate_tree')); //商品分类
		}
		$this->set('cat_id','0');
		$con = $this->fetch('ajax_cate_option',true);
		die($con);
	}
}
?>