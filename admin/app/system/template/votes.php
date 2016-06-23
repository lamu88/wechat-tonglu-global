<div class="contentbox">
	 <table cellspacing="2" cellpadding="5" width="100%">
	 	<tr>
		<th colspan="8" align="left"><span style="float:left">投票调查</span><a href="" style=" float:right;padding:0px 4px 0px 4px; color:#FF0000; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc">添加新投票</a></th>
		</tr>
     	<tr>
        	<th>展开</th><th>投票标题</th><th>多选</th><th>投票数</th><th>操作</th>
        </tr>
		<?php 
		if(!empty($rts)){
		foreach($rts as $row){
		?>
        <tr>
			<td><?php echo $row['name'];?></td>
			<td><img src="<?php echo $this->img($row['is_show']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_show']==1 ? '0' : '1';?>" class="activeop" lang="is_show" id="<?php echo $row['id'];?>"/></td>
			<td><img src="<?php echo $this->img($row['is_opennew']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_opennew']==1 ? '0' : '1';?>" class="activeop" lang="is_opennew" id="<?php echo $row['id'];?>"/></td>
  			<td><?php echo $row['type']=='top' ? '顶部' : ($row['type']=='bottom' ? '底部' : '中间');?></td>
			<td>
	<a href="systemconfig.php?type=nav_edit&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="delnav"/>
			</td>
		</tr>
		<?php 
		}  
		}  ?>
     </table>
</div>