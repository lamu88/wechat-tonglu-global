<style type="text/css">
.cLineB {
overflow: hidden;
padding: 8px 0;
border-bottom: 1px solid #EEEEEE;
}
.contentbox .cLineB h4 {
font-size: 16px; padding:5px; margin:0px;
}
.contentbox .cLineB button a{ color:#fff} 
.btnGreen {
border: 1px solid #FFFFFF;
box-shadow: 0 1px 1px #0A8DE4;
-moz-box-shadow: 0 1px 1px #0A8DE4;
-webkit-box-shadow: 0 1px 1px #0A8DE4;
padding: 5px 20px;
cursor: pointer;
display: inline-block;
text-align: center;
vertical-align: bottom;
overflow: visible;
border-radius: 3px;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
background-color: #5ba607;
background-image: linear-gradient(bottom, #107BAD 3%, #18C2D1 97%, #18C2D1 100%);
background-image: -moz-linear-gradient(bottom, #107BAD 3%, #0A8DE40 97%, #18C2D1 100%);
background-image: -webkit-linear-gradient(bottom, #107BAD 3%,#0A8DE4 97%, #18C2D1 100%);
color: #fff;
font-size: 14px;
line-height: 1.5;
}
.right {
float: right; margin-right:5px;
}
.ftip {
background: #fefbe4 url(<?php echo $this->img('lightbulb.gif');?>) no-repeat 10px 16px;
border: 1px solid #F3ECB9;
padding: 6px 20px 6px 36px;
height: 36px;
font-size: 14px;
margin: 10px;
color: #993300;
line-height: 36px;
}
TABLE.ListProduct {
BORDER-TOP: #d3d3d3 1px solid;
MARGIN-TOP: 5px;
WIDTH: 100%;
MARGIN-BOTTOM: 5px;
_border-collapse: collapse;
}
TABLE.ListProduct THEAD TH {
BORDER-BOTTOM: #d3d3d3 1px solid;
PADDING-BOTTOM: 5px;
BACKGROUND-COLOR: #f1f1f1;
PADDING-LEFT: 5px;
PADDING-RIGHT: 5px;
COLOR: #333;
FONT-SIZE: 14px;
BORDER-TOP: #e3e3e3 1px solid;
FONT-WEIGHT: normal;
BORDER-RIGHT: #ddd 1px solid;
PADDING-TOP: 5px;
color: #000000;
font-weight: bold;
}
TABLE.ListProduct TBODY TR:nth-child(2n+1) {
background-color: #FCFCFC;
}
TABLE.ListProduct TBODY TD {
BORDER-BOTTOM: #eee 1px solid;
PADDING-BOTTOM: 10px;
PADDING-LEFT: 5px;
PADDING-RIGHT: 5px;
BORDER-RIGHT: #eee 1px solid;
PADDING-TOP: 10px;
font-size: 12px;
_empty-cells: show;
word-break: break-all;
}
TABLE.ListProduct TBODY TD a{ color:#fff}
#cdul {
float: left;
color: red;
}
TABLE.ListProduct TBODY TR:hover {
background-color: #F1FCEA;
}
.board {
background: url(<?php echo $this->img('bg_repno.gif');?>) no-repeat scroll 0 0 transparent;
padding-left:55px;
}
</style>
<div class="contentbox">
  <div class="cLineB">
  <h4><span class="">注意：1级菜单最多只能开启3个，2级子菜单最多开启5个!</span></h4>
  <a style="float:right; margin-right:5px; color:#FFF" class="btnGreen " href="weixin.php?type=diymenuinfo" title="添加主菜单">添加菜单</a>
 </div>
 <div class="ftip" style="margin:10px auto">通过认证的订阅号或者服务号才能使用自定义菜单。</div>
 
 <div class="msgWrap form">
    <form enctype="multipart/form-data" action="" method="post"><input type="hidden" value="" name="anchor">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListProduct"> 
	<thead>
	<tr>
		<th style=" width:60px;">显示顺序</th>
		<th style=" width:220px;">主菜单名称</th>
		<th style=" width:170px;">关联关键词</th>
		<th>外链URL</th>
		<th class="norightborder" style=" width:160px;">操作</th>
	</tr>
	</thead>
	<tbody>
	<?php if(!empty($rt))foreach($rt as $row){ ?>
		<tr class="hover">
			<td class="td25">
				<span><?php echo $row['sort'];?></span>
			</td>
			<td>
			  <div>
				<span><?php echo $row['title'];?></span>
			  </div>
			</td>
			<td><span><?php echo $row['keyword'];?></span></td>
			<td><span><?php echo empty($row['url']) ? "无链接":$row['url'];?></span></td>
			<td>
				<a class="ajax btnGreen  cboxElement" href="<?php echo ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$row['id'];?>" title="修改主菜单">修改</a>
				<a class=" btnGreen " href="<?php echo ADMIN_URL.'weixin.php?type=diymenu&id='.$row['id'];?>" onclick="return confirm('确定删除吗')">删除</a>
			</td>				
		  </tr>
		     <?php if(!empty($row['cat_id']))foreach($row['cat_id'] as $rows){ ?>
				<tr class="hover">
					<td class="td25">
						<span><?php echo $rows['sort'];?></span>
					</td>
					<td>
					  <div class="board">
						<span><?php echo $rows['title'];?></span>
					  </div>
					</td>
					<td><span><?php echo $rows['keyword'];?></span></td>
					<td><span><?php echo empty($rows['url']) ? "无链接":$rows['url'];?></span></td>
					<td>
						<a class="ajax btnGreen  cboxElement" href="<?php echo ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$rows['id'];?>" title="修改主菜单">修改</a>
						<a class=" btnGreen " href="<?php echo ADMIN_URL.'weixin.php?type=diymenu&id='.$rows['id'];?>" onclick="return confirm('确定删除吗')">删除</a>
					</td>				
				  </tr>
			<?php } ?>
		  	
	<?php } ?>	  
		  <tr class="hover">
			<td class="td25" colspan="5">
			<a class="btnGreen " onclick="return drop_confirm();" title="">生成自定义菜单</a>
			<span style="float:left;" id="cdul">
			<style>
				#cdul{
					float:left;
					color:red;
				}
			</style>
			注：<br>
			(使用前提是已经拥有了自定义菜单的用户才能够使用，)<br>
			第一步:添加菜单，<br>
			第二步:点击生成!<br>
			注意：1级菜单最多只能开启3个，2级子菜单最多开启5个<br>
			官方说明：修改后，需要重新关注，或者最迟隔天才会看到修改后的效果！<br>
			</span>
			</td>				
		  </tr>
				  
	</tbody>
	</table>
	<input type="hidden" name="__hash__" value="d996b49c161bce77136580567dc404ea_44c284577b5bb2203694e2c8ab06da33"></form>
		   <p>
	
		   </p>
		   <div class="clear"></div>
		  </div>
	  
</div>

<script type="text/javascript">
function drop_confirm(){
	if(confirm('自定义菜单最多勾选3个，每个菜单的子菜单最多5个，请确认!')){
		createwindow();
		$.post('<?php  echo ADMIN_URL.'weixin.php'; ?>',{action:'ajax_diyclass_send'},function(data){
				removewindow();
				alert(data);
		});
	}
	return false;
}
</script>