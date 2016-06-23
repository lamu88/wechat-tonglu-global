<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=2" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
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
.dailicenter li{ height:50px; line-height:50px; padding-top:2px; padding-bottom:2px; background:#FFFFFF; border-bottom:1px solid #dcd9d8;text-align:left;}
.dailicenter li a{ font-size:14px;padding-left:5%; display:block; background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat;border-left:3px solid #de4943}
.dailicenter li a:hover{ background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat #cfccbd}

.pages{ margin-top:20px;}
.pages a{ background:#ededed; padding:2px 4px 2px 4px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; margin-right:5px;}

</style>
<div id="main" style="min-height:300px">
	<ul class="dailicenter">
	<?php if(!empty($rt))foreach($rt as $row){?>
		<li>
			<a href="<?php echo ADMIN_URL.'new.php?id='.$row['article_id'];?>">
			<p style="height:22px; line-height:22px; padding-top:3px; overflow:hidden"><?php echo $row['article_title'];?></p>
			<p style="height:22px; line-height:22px; padding-bottom:3px; overflow:hidden; color:#CCCCCC"><?php echo date('Y-m-d',$row['addtime']);?></p>
			</a>
		</li>
	<?php }else{ ?>
	<p style="padding:50px; font-size:16px; text-align:center">暂无公告</p>
	<?php } ?>
	</ul>
	<?php if(!empty($pages)){?>
	  <div class="pages"><?php echo $pages['showmes'];?><?php echo $pages['first'].$pages['previ'].$pages['next'].$pages['Last'];?></div>
	  <?php } ?>
</div>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>