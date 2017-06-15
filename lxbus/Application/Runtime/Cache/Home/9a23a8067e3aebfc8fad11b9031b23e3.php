<?php if (!defined('THINK_PATH')) exit();?><html lang="zh-CN">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
    <title><?php echo ($title); ?></title>
    <link rel="shortcut icon" href="/lxbus/Application/Home/View//Public//images/bzb.ico" type="image/x-icon">
    <meta name="description" content="<?php echo ($description); ?>">
    <meta name="Keywords" content="<?php echo ($keyword); ?>">
    <link href="/lxbus/Application/Home/View//Public//css/bootstrap.css" rel="stylesheet">
	<link href="/lxbus/Application/Home/View//Public//css/site.css" rel="stylesheet">
	<link href="/lxbus/Application/Home/View//Public//css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/lxbus/Application/Home/View//Public//css/mobile.css" rel="stylesheet">
	
	<script src="/lxbus/Application/Home/View//Public//js/jquery.js"></script>
	<script src="/lxbus/Application/Home/View//Public//js/bootstrap.js"></script>
	
</head>


<body style="margin:0;padding:0;">
<div style="width:100%;">

	<div class="container regist-ct" style="background-color:#fff">
		<div style="margin-top:20px;margin-bottom:20px;text-align:center;color:#cc0000;font-size:24px;">
			  提交站点定位
		</div>

		<form class="form-horizontal" id="myform" >

		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label" style="float:left;width:28%;text-align:right;padding-right:0px;padding-top:6px;">选择站点 <span class="requery">*</span></label>
			<div class="col-sm-3" style="float:left;width:70%;">
			  <select name="stop" id="stop">
				<option value=''>选择站点</option>
				<?php if(is_array($stops)): $i = 0; $__LIST__ = $stops;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value='<?php echo ($v[id]); ?>'><?php echo ($v[stopname]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			  </select>
			</div>
	
		  </div>


		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label" style="float:left;width:28%;text-align:right;padding-right:0px;padding-top:6px;">经度 <span class="requery">*</span></label>
			<div class="col-sm-3" style="float:left;width:70%;">
			  <input type="text" class="form-control" id="longitude" name="longitude" placeholder="经度">
			</div>
			<div class="col-sm-2" style="float:right;width:70%;">
			 <input type="button" id="setlng" name="setlng" value="确定经度">
			</div>
		  </div>
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label" style="float:left;width:28%;text-align:right;padding-right:0px;padding-top:6px;">纬度 <span class="requery">*</span></label>
			<div class="col-sm-3" style="float:left;width:70%;">
			  <input type="text" class="form-control" id="latitude" name="latitude" placeholder="纬度">
			</div>
			<div class="col-sm-2" style="float:right;width:70%;">
			 <input type="button" id="setlat" name="setlat" value="确定纬度">
			</div>
		  </div>

		  <div class="form-group">
			<div class="col-sm-offset-4 col-sm-4">
			  <input type="button" id="btn-reg" class="btn btn-danger btn-block" value="提交"/>
			</div>
		  </div>
		  
		  
		</form>
		

	</div>
	
	
<div>
<script>
//获取经度
$('#setlng').click(function(){
	var lng=window.bzbweb.getlongitude();
	$('#longitude').val(lng);
});
//获取纬度
$('#setlat').click(function(){
	var lng=window.bzbweb.getlatitude();
	$('#latitude').val(lng);
});
//提交保存
$('#btn-reg').click(function(){
		//alert("aaaaaa");
		var url="<?php echo U('dosavestop');?>?"+$('form').serialize();
		$.get(url,function(data,status){
			if(data==0){
				alert("操作成功！");
				//$('#myModal').modal('hide');
				
			}
			else{
				alert("操作失败！");
			}
		});
	});

</script>
</body>
</html>