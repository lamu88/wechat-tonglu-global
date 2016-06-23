<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=2" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
body{ background:#FFF !important;}
.pw,.pwt{
height:26px; line-height:26px;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}

</style>
<div id="main" style="min-height:300px">
	<div style="margin:5px">
		<h2 style="text-align:center; font-size:14px"><?php echo $rt['article_title'];?></h2>
		<p style=" text-align:center;color:#999999"><?php echo date('Y-m-d H:i:s',$rt['addtime']);?></p>
	</div>
	<div class="clear" style="border-top:1px solid #dcd9d8;"></div>
	<div style="margin:5px;">
		<div style="line-height:23px">
		<?php echo $rt['content'];?>
		</div>
	</div>
</div>
<script type="text/javascript">
function update_user_pass(){

}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>