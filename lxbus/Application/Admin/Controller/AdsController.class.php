<?php
namespace Admin\Controller;
use Think\Controller;
class AdsController extends BaseController {

	//首页合作商城
	public function homehzsclist(){
		$provinceid=$_GET[provinceid]?$_GET[provinceid]:450000;
		$citys=M("city")->where("father=$provinceid")->select();
		
		$province=M("province")->select();
		
		$this->citys=$citys;
		$this->province=$province;
		$this->display();
	}
	public function homehzsc(){
		$cityid=$_GET[cityid];
		$ads1=M("ads")->where("`dec`='G' and id=17")->find();
		$ads2=M("ads")->where("`dec`='G' and id=18")->find();
		
		$this->ads1=$ads1;
		$this->ads2=$ads2;
		$this->display();
	}
	//保存广告
	public function dohomehzsc(){
		$id=$_POST[id];
		$is_active=$_POST[isactive];
		$asdlink=$_POST[adslink];
		
		$ads[id]=$id;
		$ads[is_active]=$is_active;
		$ads[site_url]=$asdlink;
		$ads[create_time]=time();
		$rs=M('ads')->save($ads);
		if($rs){
			echo 0;
		}
		else{
			echo -1;
		}
	}
	
	//口碑保存广告
	public function dohomehzsc2(){
		$id=$_POST[id];
		$is_active=$_POST[isactive];
		$asdlink=$_POST[adslink];
		
		$ads[id]=$id;
		$ads[is_active]=$is_active;
		$ads[site_url]=$asdlink;
		$ads[create_time]=time();
		$rs=M('kb_ads')->save($ads);
		if($rs){
			echo 0;
		}
		else{
			echo -1;
		}
	}
	
	//首页中部八格
	public function homemid(){
		$ads1=M("ads")->where("`dec`='A' and id=1")->find();
		$ads2=M("ads")->where("`dec`='B' and id=3")->find();
		$ads3=M("ads")->where("`dec`='B' and id=4")->find();
		$ads4=M("ads")->where("`dec`='B' and id=5")->find();
		$ads5=M("ads")->where("`dec`='B' and id=6")->find();
		$ads6=M("ads")->where("`dec`='C' and id=7")->find();
		$ads7=M("ads")->where("`dec`='C' and id=8")->find();
		$ads8=M("ads")->where("`dec`='C' and id=9")->find();
		
		$this->ads1=$ads1;
		$this->ads2=$ads2;
		$this->ads3=$ads3;
		$this->ads4=$ads4;
		$this->ads5=$ads5;
		$this->ads6=$ads6;
		$this->ads7=$ads7;
		$this->ads8=$ads8;
		$this->display();
	}
	
	//首页推荐商品
	public function hometj(){
		$ads1=M("ads")->where("`dec`='E' and id=2")->find();
		$ads2=M("ads")->where("`dec`='D' and id=10")->find();
		$ads3=M("ads")->where("`dec`='D' and id=11")->find();
		$ads4=M("ads")->where("`dec`='D' and id=12")->find();
		$ads5=M("ads")->where("`dec`='D' and id=13")->find();
		$ads6=M("ads")->where("`dec`='D' and id=14")->find();
		$ads7=M("ads")->where("`dec`='D' and id=15")->find();
		$ads8=M("ads")->where("`dec`='D' and id=16")->find();
		
		$this->ads1=$ads1;
		$this->ads2=$ads2;
		$this->ads3=$ads3;
		$this->ads4=$ads4;
		$this->ads5=$ads5;
		$this->ads6=$ads6;
		$this->ads7=$ads7;
		$this->ads8=$ads8;
		$this->display();
	}
	
	//口碑滚动
	public function kbslider(){
		
		$ads1=M("kb_ads")->where("`dec`='L' and id=22")->find();
		$ads2=M("kb_ads")->where("`dec`='L' and id=23")->find();
		$ads3=M("kb_ads")->where("`dec`='L' and id=24")->find();
		$ads4=M("kb_ads")->where("`dec`='L' and id=25")->find();
		
		$this->ads1=$ads1;
		$this->ads2=$ads2;
		$this->ads3=$ads3;
		$this->ads4=$ads4;
		$this->display();
	}
	
	//口碑中部四格
	public function kbmid(){
		$ads1=M("kb_ads")->where("`dec`='a' and id=18")->find();
		$ads2=M("kb_ads")->where("`dec`='b' and id=19")->find();
		$ads3=M("kb_ads")->where("`dec`='c' and id=20")->find();
		$ads4=M("kb_ads")->where("`dec`='d' and id=21")->find();
		
		$this->ads1=$ads1;
		$this->ads2=$ads2;
		$this->ads3=$ads3;
		$this->ads4=$ads4;
		$this->display();
	}
	
	
	//进行图片保存
	function saveuppic(){
		$id=$_POST[id];
		$img = $_POST['img'];
		//dump($_POST);exit;
		
		if(empty($img))
		{
			return false;
		}
	
		// 获取图片
		list($type, $data) = explode(',', $img);

		// 判断类型
		if(strstr($type,'image/jpeg')!=='')
		{
			$ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=='')
		{
			$ext = '.gif';
		}elseif(strstr($type,'image/png')!=='')
		{
			$ext = '.png';
		}
		
		$picname=time().$ext;
		// 生成的文件名
		$photo = "Uploads/shopads/".$picname;
		$ads[pic_url] = $photo;
		$backpic=C('siteurl').$photo;
	 
		// 生成文件
		file_put_contents($photo, base64_decode($data), true);
		
		$this->deladspic($id);//删除图片
		
		$ads[id] = $id;
		//保存到数据库
		$rs=M("ads")->save($ads);
		// 返回
		header('content-type:application/json;charset=utf-8');
		$ret = array('img'=>$backpic,'msg'=>'图片上传成功');
		echo json_encode($ret);	
		exit;			
		
	}
	
	//删除图片
	function deladspic($id){
		$pic=M('ads')->where("id=$id")->find();
		if($pic){
			//进行删除
			$mypic[pic_url]='';
			$rs=M("ads")->where("id=$id")->save($mypic);
			unlink("./".$pic[pic_url]);
		}
	}
	
	//口碑进行图片保存
	function saveuppic2(){
		$id=$_POST[id];
		$img = $_POST['img'];
		//dump($_POST);exit;
		
		if(empty($img))
		{
			return false;
		}
	
		// 获取图片
		list($type, $data) = explode(',', $img);

		// 判断类型
		if(strstr($type,'image/jpeg')!=='')
		{
			$ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=='')
		{
			$ext = '.gif';
		}elseif(strstr($type,'image/png')!=='')
		{
			$ext = '.png';
		}
		
		$picname=time().$ext;
		// 生成的文件名
		$photo = "Uploads/shopads/".$picname;
		$ads[pic_url] = $photo;
		$backpic=C('siteurl').$photo;
	 
		// 生成文件
		file_put_contents($photo, base64_decode($data), true);
		
		$this->deladspic2($id);//删除图片
		
		$ads[id] = $id;
		//保存到数据库
		$rs=M("kb_ads")->save($ads);
		// 返回
		header('content-type:application/json;charset=utf-8');
		$ret = array('img'=>$backpic,'msg'=>'图片上传成功');
		echo json_encode($ret);	
		exit;	

		
		
	}
	
	//口碑删除图片
	function deladspic2($id){
		$pic=M('kb_ads')->where("id=$id")->find();
		if($pic){
			//进行删除
			$mypic[pic_url]='';
			$rs=M("kb_ads")->where("id=$id")->save($mypic);
			unlink("./".$pic[pic_url]);
		}
	}
	
}