<?php
class BannerController extends Controller{
	
	/*
	banner管理
	@$key:广告tag名称
	@$id:分类id
	@$type:产品分类(gc)还是文字分类(ac)
	@@list:查询几条广告
	*/
	function banner($key="",$list=1,$id=0,$type='gc'){
	   $t = Common::_return_px();
	   $cache = Import::ajincache();
	   $cache->SetFunction(__FUNCTION__);
	   $cache->SetMode('ads'.$t);
	   $fn = $cache->fpath(array('0'=>$key,'1'=>$list,'2'=>$id,'3'=>$type));
	   if(file_exists($fn)&&!$cache->GetClose()){
					include($fn);
	   }
	   else
	   {
				$uid = $this->Session->read('User.uid');
				//求出当前用户的推荐用户的代理信息
				$sql = "SELECT tb1.share_uid,tb2.user_rank FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.share_uid WHERE tb1.uid = '$uid' AND tb2.user_rank='10' LIMIT 1";
				$rts = $this->App->findrow($sql);
				$rank = '1';
				if(!empty($rts)){
					$rank = $rts['user_rank'];
					$pid = $rts['share_uid']; //分享的ID
				}
				if($rank=='10'){
					$uid = $pid;
				}
				if(!($uid>0)) $uid = 0;
				$s = "(case when tb1.uid='$uid' then 1 ELSE 4 END),";
				$sql = "SELECT tb1.*,tb2.ad_name FROM `{$this->App->prefix()}ad_content` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}ad_position` AS tb2 ON tb1.tid = tb2.tid";
				if($id>0 && !empty($key)){
					$sql .=" WHERE tb1.cat_id = '$id' AND tb1.is_show='1' AND tb1.type='$type' AND tb2.ad_name LIKE '%$key%' ORDER BY {$s} tb1.vieworder ASC,tb1.addtime DESC LIMIT $list";
				}elseif($id>0){
					$sql .=" WHERE tb1.cat_id = '$id' AND tb1.is_show='1' AND tb1.type='$type' ORDER BY {$s} tb1.vieworder ASC,tb1.addtime DESC LIMIT $list";
					
				}elseif(!empty($key)){
					$sql .=" WHERE tb1.is_show='1' AND tb2.ad_name LIKE '%$key%' ORDER BY {$s} tb1.vieworder ASC,tb1.addtime DESC LIMIT $list";
				}else{
					$rt = array();
				}
				if($list==1){
					$rt = $this->App->findrow($sql);
				}elseif($list>1){
					$rt = $this->App->find($sql);
				}else{
					$rt = array();
				}
				
				$cache->write($fn, $rt,'rt');
		}
		return $rt;
	}//end function
}
?>