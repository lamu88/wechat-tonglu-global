<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/9/css.css?v=2" media="all" />
<style type="text/css">
body{ background:#fff;}
.applytkth{ padding:10px;}
.applytkth p{ line-height:30px; line-height:30px; cursor:pointer}
.applytkth p label{ cursor:pointer}

.pw{
border: 1px solid #ddd;
border-radius: 5px;
padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
</style>
<?php $this->element('9/top',array('lang'=>$lang)); ?>
<div id="main" style="min-height:300px">
<div class="applytkth">
  <form id="form1" name="form1" method="post" action="">
  <div style="font-size:14px; border-bottom:1px solid #ededed; line-height:30px; height:30px; font-weight:bold">选择退款申请原因</div>
  <p>
    <label>
    <input type="radio" name="orderdesc" value="宝贝拍错了，重新下单" />宝贝拍错了，重新下单
    </label>
  </p>
  <p>
    <label>
    <input type="radio" name="orderdesc" value="购买重复了" />购买重复了
    </label>
  </p>
  <p>
    <label>
    <input type="radio" name="orderdesc" value="商品质量问题" />商品质量问题
    </label>
  </p>
  <p>
    <label>
    <input type="radio" name="orderdesc" value="库存无货" />库存无货
    </label>
  </p>
  <p>
    <label>
    <input type="radio" name="orderdesc" value="其他原因" />其他原因
    </label>
  </p>
  
  <label>
  <textarea name="ordertxt" class="pw" style="width:100%; height:60px"  placeholder="备注：退货物流单号等其他描述" ></textarea>
  </label>
  <div style="text-align:center; padding:10px;">
  <label>
  <input onclick="return confirm('确定申请吗')" type="submit" name="Submit" value="提交申请" style="width:50%; background:#32a000;border-radius:7px; height:32px; line-height:32px; cursor:pointer; color:#FFF; font-size:16px;" />
  </label>
  </div>
  </form>
  </div>
</div>
<?php $this->element('9/footer',array('lang'=>$lang)); ?>