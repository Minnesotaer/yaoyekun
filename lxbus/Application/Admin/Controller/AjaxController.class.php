<?php
namespace Admin\Controller;
use Think\Controller;

Class AjaxController extends Controller{
	//获取城市或区县
	function getRegion(){
		$pid=$_REQUEST["pid"];
		$type=$_REQUEST["type"];
		if($type==2){
			$list=getcities($pid);
		}
		
		if($type==3)
		{
			$list=getareas($pid);
		}
		echo json_encode($list);
	}
	
	//获取口碑二级分类
	function getchildcat(){
		$pid=$_REQUEST["pid"];
		$list=M("kb_cat")->field("id,name")->where("pid=$pid")->select();
		echo json_encode($list);
	}
	
	//获取商圈
	function getcircle(){
		$cityid=$_REQUEST["cityid"];
		$list=M("kb_circle")->field("id,name")->where("city_id=$cityid")->select();
		echo json_encode($list);
	}
}

?>