<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
    <title><?php echo ($title); ?></title>
    <meta name="description" content="<?php echo ($title); ?>">
    <meta name="Keywords" content="<?php echo ($title); ?>">
	<script src="/lxbus/Application/Home/View//Public//plugins/jQuery/jQuery-2.2.0.min.js"></script>
	<script src="/lxbus/Application/Home/View//Public//js/jquery.js"></script>

	<script>
	window.onload = function(){
			var input = document.getElementById("file_input");
			var result,div;
	 
			if(typeof FileReader==='undefined'){
				result.innerHTML = "抱歉，你的浏览器不支持 FileReader";
				input.setAttribute('disabled','disabled');
			}else{
				input.addEventListener('change',readFile,false);
			}　　　　　
			//handler
			function readFile(){
				
				//提示正在上传
				$('#uploding').show();
				
				for(var i=0;i<this.files.length;i++){
					if (!input['value'].match(/.jpg|.gif|.png|.bmp/i)){　　//判断上传文件格式
						return alert("上传的图片格式不正确，请重新选择");　　　　　　　　　　
						}
					var reader = new FileReader();
					reader.readAsDataURL(this.files[i]);
					reader.onload = function(e){
						// ajax 上传图片
						var get_url = "<?php echo U('saveuppic');?>";
						var user_id=$("#user_id").val();
						var token=$("#token").val();
						
						//var longitude=window.bzbweb.getlongitude();
						//var longitude=window.bzbweb.getlatitude();
						var longitude='';
						var latitude='';
						//alert(e.target.result);
						$.post(get_url, { img: e.target.result,user_id:user_id,token:token,longitude:longitude,latitude:latitude},function(ret){	
						   if(ret.img!=''){
								$('#uploding').hide();
							    //alert(ret.msg);
								result = '<div id="result"><img src="'+ret.img+'" alt=""/></div>';
								div = document.createElement('div');
								div.innerHTML = result;
								document.getElementById('picbody').appendChild(div);  　　//插入dom树 
							}else if(ret.msg!=""){
								$('#uploding').hide();
								alert(ret.msg);
							}else{
								$('#uploding').hide();
								alert('图片上传失败！');
							}
						},'json');
			
						  
					}
					 
					　　　　　　　　 
				}   
				
				
			}
		}
	</script>
	<style>
	.container img {max-width:95%;}
	p{margin:0px;}
	.file{width:10%;left:30%; margin-top:16px;border:0px; height:10px; position:absolute;font-size:14px;}
	.filebtn{width:55%;margin:auto auto;margin-top:12px;display:block; position:relative; color:#f16265;font-family:"microsoft yahei"; background:rgb(255,210,0); font-size:1.06em;text-align:center; cursor:pointer; border:0px; border-radius:5px;padding:8px 0;}
	.del-btn{
	float:right;margin-top:-20px;margin-right:5px; height:16px;border:1px solid #ccc;background:#fff;border-radius:8px;padding:5px 8px;font-size:14px;color:#cc0000;z-index:888;
		}
	.uploding{position:absolute;top:40%;width:50%;left:30%;text-align:center;padding:30px 0; color:#fff;background:#333;border:1px solid #ccc;opacity:0.8;border-radius:15px;}
	</style>
</head>
<body>
<div class="container">
<?php if($is_ok == 1): ?><form>
    <p><input class="file" type="file" id="file_input" multiple/>
	<label class="filebtn" for="file_input" title="JPG,GIF,PNG"><img width="25px" src="/lxbus/Application/Home/View//Public//images/camera.png"/>&nbsp;&nbsp;请选择图片</label>
	</p>
	<span style="float:right;margin-top:-30px;margin-right:5px;" onclick="window.location.reload();">刷新</span>
	<div id="picbody" style="margin-top:15px;">
	</div>
	<caption>
		<input type="date"  name="keyword1" id="keyword1" value="<?php echo ($keyword1); ?>"  />
		到
		<input type="date" name="keyword1" id="keyword2" value="<?php echo ($keyword2); ?>"  />
		<input type="button" name="serach" id="search" value="搜索"><br/>

	</caption>
	<?php if(is_array($userlist)): $i = 0; $__LIST__ = $userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><span style="width:98%;float:left;text-align:center;margin:15px 0;border:1px solid #ccc;padding:2px 1%;">
			<span style="width:100%;float:left;z-index:666;"><img src="<?php echo C('site_base_url'); echo ($v["pic_url"]); ?>"></span>
			<span style="width:100%;float:left;z-index:666;"><?php echo (date("Y-m-d H:i:s",$v["create_time"])); ?></span>
			<span class="del-btn" data-id="<?php echo ($v["id"]); ?>" data-pic="<?php echo ($v["pic_url"]); ?>">删除</span>
		</span><?php endforeach; endif; else: echo "" ;endif; ?>
	<tr>
		<td colspan="15" class="mypage"><?php echo ($page); ?></td>
	</tr>

	<input type="hidden" id="user_id" name="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo ($token); ?>">
</form>
<div id="uploding" class="uploding" style="display:none;">上传中...</div>
</div>
<?php else: ?>
	<div class="mlist">
		<p>非法操作</p>
	</div><?php endif; ?>
<script>
$(document).ready(function(e) {
	$('.del-btn').click(function(){
	//alert("aaa");
	var id=$(this).attr('data-id');
	var pic=$(this).attr('data-pic');
	var user_id=$('#user_id').val();
	var token=$('#token').val();
	//alert(id);
	var url = "<?php echo U('delpic');?>?id="+id+"&user_id="+user_id+"&token="+token+"&pic="+pic;
	var r=confirm('你确定要删除这个图片吗？');
	if (r==true){
		$.get(url,function(data,status){
			console.log(data);
			if(data>=0){
				//$('#myModal').modal('hide');
				
				alert("删除成功");
				window.location.reload();
			}
			
			else{
				alert("删除失败");
			}
		});
	}
	
	});
});
//搜索
$('#search').click(function(){

	var user_id=$('#user_id').val();
	var token=$('#token').val();

	var keyword1=$('#keyword1').val();
//		var keyword=$('#keyword').val();
	var keyword2=$('#keyword2').val();
	var url = "<?php echo U('myuppic');?>?keyword1="+keyword1+"&keyword2="+keyword2+"&user_id="+user_id+"&token="+token;
	window.location.href=url;
});
</script>

</body>
</html>