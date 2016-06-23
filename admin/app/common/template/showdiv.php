<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.black_overlay{
      		/*display:none;*/
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color:#ededed;
            z-index:1001;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=80);
}
.white_content {
			/*display:none;*/
            position: absolute;
            top: 25%;
            left: 20%;
            width: 500px;
            height: 171px;
            background-color: white;
            z-index:1002;
            overflow: auto;
			
}
-->
</style>
<script type="text/javascript" language="javascript">
<!--
function colsediv(){
document.getElementById('light').style.display='none';
document.getElementById('fade').style.display='none';
<?php if(isset($thisurl)&&!empty($thisurl)){ ?>
window.location.href = "<?php echo $thisurl;?>";
<?php } ?>
return false;
}
-->
</script>
<div id="light" class="white_content" style="background:url(<?php echo $this->img('mes.jpg');?>) no-repeat center center;">
 <div style=" position:absolute; bottom:8px; right:6px;"><img src="<?php echo $this->img('error_icon.png');?>" onclick = "colsediv()" style="cursor:pointer"/></div>
 </div>
<div id="fade" class="black_overlay"></div>
