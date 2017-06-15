<?php
namespace Admin\Controller;
use Think\Controller;
class KoubeiController extends BaseController {
 
	//商家管理
	public function index(){

		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(line like '%$keyword%' or remark like '%$keyword%') ";
		}
		$usernum=M('line')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('line')->field('id,linename,province_id,city_id,remark,is_active,create_time')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();

		//dump($views_data['userlist']);//die;
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->userlist=$userlist;



		$this->display();
	   
	    
	
	
	}
	public function stop(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(stopname like '%$keyword%' or remark like '%$keyword%') ";
		}
		$usernum=M('stop')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('stop')->field('id,stopname,province_id,city_id,area_id,remark,create_time')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();

		//dump($views_data['userlist']);//die;
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->userlist=$userlist;



		$this->display();
	}
	
	//查看编辑店铺
    public function myshop()
	{
		
		$id=$_GET[id];
		
		//选择分类
		$cat=M("kb_cat")->where("pid=0")->select();
		$this->cat=$cat;
		
		$shop=M('kb_shop')->where("id=$id")->find();
		
		$cat=$shop[cat_id];
		$cats=explode(',',$cat);
		//dump($cats);
		$num=count($cats);
		$pcat=$cats[$num-1];
		$this->pcat=$pcat;//一级分类
		
		$childcat=M("kb_cat")->where("pid=$pcat")->select();
		//dump($childcat);
		foreach($childcat as $k=>$v){
			for($i=0;$i<$num-1;$i++){
				if($cats[$i]===$v[id]){
					$childcat[$k][ischeck]=1;
					break;
				}
				else{
					$childcat[$k][ischeck]=0;
				}
			}
		}
		//二级分类
		$this->childcat=$childcat;
		
		//省份
		$province=M("province")->select();
		$this->province=$province;
		
		//商圈
		$circle=M("kb_circle")->where("city_id=$shop[city_id]")->select();
		$this->circle=$circle;
		

		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/myshop","编辑用户",$id);
		
		$this->shop=$shop;
		
		//dump($userlist);
		
		$this->display();
	}
	
	//执行修改店铺
	public function update(){
		//echo json_encode($_POST);exit;
		//dump($_POST);exit;
		
		$shop[name]=$_POST[name];
		//查出用户的企业id
		if($_POST[companyphone]!=''){
			$com=M("user")->where("phone='$_POST[companyphone]'")->find();
			if($com){
				
				
				$shop[company_id]=$com[company_id];
				
			}
			else{
				echo -2;//商家用户不存在
				exit;
			}
			
		}
		
		$id=$_POST[id];
		$shop[province_id]=$_POST[province_id];
		$shop[city_id]=$_POST[city_id];
		$shop[area_id]=$_POST[area_id];
		$shop[address]=$_POST[address];
		
		$province=M("province")->where("provinceid='$_POST[province_id]'")->find();
		$pvname=$province[province];
		
		$city=M("city")->where("cityid='$_POST[city_id]'")->find();
		$ctname=$city[city];
		
		$area=M("area")->where("areaid='$_POST[areaid]'")->find();
		$aename=$area[area];
	  
		$shop[area_words]=$pvname.",".$ctname.",".$aename.",".$shop[address];
		
		$catid=$_POST[cat_id];
		if($catid!=''){
			$shop[cat_id]="";
			$shop[cat_words]="";
			for($i=0;$i<count($_POST[childcat]);$i++){
				$shop[cat_id].=$_POST[childcat][$i].",";
				$shop[cat_words].=getkbcatname($_POST[childcat][$i]).",";
			}
			$shop[cat_id].=$catid;
			$shop[cat_words].=getkbcatname($catid);
		}
		
		$shop[longitude]=$_POST[longitude];
		$shop[latitude]=$_POST[latitude];
		$shop[circle_id]=$_POST[circle_id];
		$shop[clicks]=$_POST[clicks];
		$shop[is_wifi]=$_POST[is_wifi];
		$shop[is_park]=$_POST[is_park];
		$shop[is_command]=$_POST[is_command];
		$shop[phone]=$_POST[phone];
		$shop[standard_pay]=$_POST[standard_pay];
		$shop[discount]=$_POST[discount];
		$shop[tags]=$_POST[tags];
		$shop[create_time]=time();
		$shop[is_check]=1;
	
		$shop[description]=$_POST[des];
		$shoprs=M('kb_shop')->where("id=$id")->save($shop);
		if($shoprs)
			echo $id;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/save","执行修改店铺",$shoprs);
	}
	
	//修改店铺第二步，上传图片
    public function myshop2()
	{
		$id=$_GET[id];
		
		//门头图片
		$img1=M("kb_shop_img")->where("img_type=1 and shop_id=$id")->select();
		
		//门头图片
		$img2=M("kb_shop_img")->where("img_type=2 and shop_id=$id")->select();
		
		//门头图片
		$img3=M("kb_shop_img")->where("img_type=3 and shop_id=$id")->select();
		
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/addshop2","修改店铺第二步",$id);

		$this->id=$id;
		$this->img1=$img1;
		$this->img2=$img2;
		$this->img3=$img3;
		$this->display();
	}
	
	//添加店铺
    public function addshop()
	{
		//选择分类
		$cat=M("kb_cat")->where("pid=0")->select();
		$this->cat=$cat;
		
	   
	    //选择商圈
		$circle=M("kb_circle")->select();
		$this->circle=$circle;
		//dump($circle);
	   
	    
	   
        //选择城市
		$province=M("province")->select();
		$this->province=$province;
		
	
		
		$longitude=$_GET[longitude]?$_GET[longitude]:116.403781;
		$latitude=$_GET[latitude]?$_GET[latitude]:39.914998;
		
		$this->longitude=$longitude;
		$this->latitude=$latitude;
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/addshop","添加店铺第一步",0);

		
		$this->display();
	}

	//执行添加店铺
	public function save(){
		//echo json_encode($_POST);exit;
		//dump($_POST);exit;
		
		$shop[name]=$_POST[name];
		//查出用户的企业id
		if($_POST[companyphone]!=''){
			$com=M("user")->where("phone='$_POST[companyphone]'")->find();
			if($com){
				
				$myshop=M("kb_shop")->where("company_id=$com[company_id]")->count();
				if($myshop>0){
					echo -3;//这个商家用户已经添加了店铺
					exit;
				}else{
					$shop[company_id]=$com[company_id];
				}
			}
			else{
				echo -2;//商家用户不存在
				exit;
			}
			
		}
		

		$shop[province_id]=$_POST[province_id];
		$shop[city_id]=$_POST[city_id];
		$shop[area_id]=$_POST[area_id];
		$shop[address]=$_POST[address];
		
		$province=M("province")->where("provinceid='$_POST[province_id]'")->find();
		$pvname=$province[province];
		
		$city=M("city")->where("cityid='$_POST[city_id]'")->find();
		$ctname=$city[city];
		
		$area=M("area")->where("areaid='$_POST[areaid]'")->find();
		$aename=$area[area];
	  
		$shop[area_words]=$pvname.",".$ctname.",".$aename.",".$shop[address];
		
		$catid=$_POST[cat_id];
		if($catid!=''){
			$shop[cat_id]="";
			$shop[cat_words]="";
			for($i=0;$i<count($_POST[childcat]);$i++){
				$shop[cat_id].=$_POST[childcat][$i].",";
				$shop[cat_words].=getkbcatname($_POST[childcat][$i]).",";
			}
			$shop[cat_id].=$catid;
			$shop[cat_words].=getkbcatname($catid);
		}
		
		$shop[longitude]=$_POST[longitude];
		$shop[latitude]=$_POST[latitude];
		$shop[circle_id]=$_POST[circle_id];
		$shop[clicks]=$_POST[clicks];
		$shop[is_wifi]=$_POST[is_wifi];
		$shop[is_park]=$_POST[is_park];
		$shop[is_command]=$_POST[is_command];
		$shop[phone]=$_POST[phone];
		$shop[standard_pay]=$_POST[standard_pay];
		$shop[discount]=$_POST[discount];
		$shop[tags]=$_POST[tags];
		$shop[create_time]=time();
		$shop[is_check]=1;
	
		$shop[description]=$_POST[des];
		$shoprs=M('kb_shop')->add($shop);
		if($shoprs)
			echo $shoprs;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/save","执行添加店铺",$shoprs);
	}
	
	//添加店铺第二步，上传图片
    public function addshop2()
	{
		$id=$_GET[id];
		
		//门头图片
		$img1=M("kb_shop_img")->where("img_type=1 and shop_id=$id")->select();
		
		//门头图片
		$img2=M("kb_shop_img")->where("img_type=2 and shop_id=$id")->select();
		
		//门头图片
		$img3=M("kb_shop_img")->where("img_type=3 and shop_id=$id")->select();
		
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/addshop2","添加店铺第二步",$id);

		$this->id=$id;
		$this->img1=$img1;
		$this->img2=$img2;
		$this->img3=$img3;
		$this->display();
	}
	
	//进行图片保存
	function saveuppic(){
		$shopid=$_POST[shopid];
		$pictype=$_POST[pictype];
		$img = $_POST['img'];
		//echo json_encode($_POST);	
		//exit;
		if($shopid>0)
			$myshop=M("kb_shop")->where("id=$shopid")->find();
		
		
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
		$photo = "Uploads/shopimg/".$picname;
		$shop[pic_url] = $photo;
		$backpic=C('siteurl').$photo;
	 
		// 生成文件
		file_put_contents($photo, base64_decode($data), true);
		
		//进行裁剪
		$image = new \Think\Image(); 
		$image->open('./'.$photo);
		$width = $image->width(); // 返回图片的宽度
		$height = $image->height();
		$width = $width/500; //取得图片的长宽比  190是要输出的图片的宽度
		$height = ceil($height/$width);
		// 生成一个固定大小为200*200的缩略图并保存为thumb.jpg
		$image->thumb(500, $height,\Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/shopimg/'.$picname);
		
		//进行保存图片
		$shop[company_id]=$myshop[company_id];
		$shop[img_type]=$pictype;
		$shop[shop_id]=$shopid;
		$shop[create_time]=time();
		
		$rs=M("kb_shop_img")->add($shop);
		// 返回
		header('content-type:application/json;charset=utf-8');
		$ret = array('img'=>$backpic,'msg'=>'图片上传成功');
		echo json_encode($ret);	
		exit;	

	
	}
	
	//删除图片
	function delpic(){
		$id=$_GET[id];
		$pic=$_GET[pic];

		
		//进行删除
		$rs=M("kb_shop_img")->where("id=$id")->delete();
		unlink("./".$pic);
		
	}
	
	//商家申请
	public function shopapply(){
		
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(name like '%$keyword%' or bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%') and (bzb_kb_shop.is_check=0 or bzb_kb_shop.is_check=2)";
		}else
		{
			$where="bzb_kb_shop.is_check=0 or bzb_kb_shop.is_check=2";		
		}
		$usernum=M('kb_shop')->join('bzb_company on bzb_kb_shop.company_id=bzb_company.company_id')->join('bzb_user on bzb_user.company_id=bzb_kb_shop.company_id')->where($where)->count();
		
		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('kb_shop')->join('bzb_company on bzb_kb_shop.company_id=bzb_company.company_id')->join('bzb_user on bzb_user.company_id=bzb_kb_shop.company_id')->where($where)->field('bzb_kb_shop.*,bzb_user.phone as myphone')->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->group("bzb_kb_shop.id")->select();
		
		
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/shopapply","商家申请列表",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->applylist=$userlist;
		//dump($applylist);
		
		$this->display();
	   
	}

	//查看店铺申请详情
    public function myshopapply()
	{
			
		$id=$_GET[id];
		
		//选择分类
		$cat=M("kb_cat")->where("pid=0")->select();
		$this->cat=$cat;
		
		$shop=M('kb_shop')->where("id=$id")->find();
		
		$cat=$shop[cat_id];
		$cats=explode(',',$cat);
		//dump($cats);
		$num=count($cats);
		$pcat=$cats[$num-1];
		$this->pcat=$pcat;//一级分类
		if($pcat>0){
			$childcat=M("kb_cat")->where("pid=$pcat")->select();
			//dump($childcat);
			if($childcat){
				foreach($childcat as $k=>$v){
					for($i=0;$i<$num-1;$i++){
						if($cats[$i]===$v[id]){
							$childcat[$k][ischeck]=1;
							break;
						}
						else{
							$childcat[$k][ischeck]=0;
						}
					}
				}
				//二级分类
				$this->childcat=$childcat;
			}
		}
		//省份
		$province=M("province")->select();
		$this->province=$province;
		
		//商圈
		$circle=M("kb_circle")->where("city_id=$shop[city_id]")->select();
		$this->circle=$circle;
		

		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/myshop","编辑用户",$id);
		
		$this->shop=$shop;
		
		//dump($userlist);
		//门头图片
		$img1=M("kb_shop_img")->where("img_type=1 and shop_id=$id")->select();
		
		//门头图片
		$img2=M("kb_shop_img")->where("img_type=2 and shop_id=$id")->select();
		
		//门头图片
		$img3=M("kb_shop_img")->where("img_type=3 and shop_id=$id")->select();
		
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/addshop2","添加店铺第二步",$id);

		$this->id=$id;
		$this->img1=$img1;
		$this->img2=$img2;
		$this->img3=$img3;
		
		$this->display();
	}
	
	//商家审核
	function checkshopapply(){
		$id=$_GET[id];
		$type=$_GET[type];
		$checkremark=$_GET[checkremark];
		
		$checkshop[is_check]=$type;
		$checkshop[check_remark]=$checkremark;
		$checkshop[check_time]=time();
		$rs=M("kb_shop")->where("id=$id")->save($checkshop);
		if($rs)
			echo 0;
		else
			echo -1;
	}
	
	//商家相册,店铺的
	public function album(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="name like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$usernum=M('kb_shop')->join('bzb_company on bzb_kb_shop.company_id=bzb_company.company_id')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$imagelist=M('kb_shop')->join('bzb_company on bzb_kb_shop.company_id=bzb_company.company_id')->where($where)->field('bzb_kb_shop.*')->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		//统计
		foreach($imagelist as $k=>$v){
			$imgnum=M("kb_shop_img")->where("shop_id=$v[id]")->count();
			$imagelist[$k][imgnum]=$imgnum;
		}
		//dump($views_data);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/album","商家相册列表",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->imagelist=$imagelist;
		
		
		
		
		$this->display();
	}
	
	//对应店铺的图片列表
	public function myalbum()
	{
		
		$id=$_GET[id];
		
		$usernum=M('kb_shop_img')->where("shop_id=$id")->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$imglist=M('kb_shop_img')->where("shop_id=$id")->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		foreach($imglist as $k=>$v){
			$imglist[$k][pic_url]=C("siteurl").$v[pic_url];
		}
		//dump($views_data['userlist']);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/myalbum","用户列表",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->imglist=$imglist;
		
		//dump($imglist);
		
		$this->display();
	}
	
	//评论管理
	public function comment(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_kb_shop.name like '%$keyword%' or bzb_user.user_name like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$usernum=M('kb_comments')->join('bzb_kb_shop on bzb_kb_comments.shop_id=bzb_kb_shop.id')->join('bzb_user on bzb_kb_comments.user_id=bzb_user.user_id')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$commentlist=M('kb_comments')->join('bzb_kb_shop on bzb_kb_comments.shop_id=bzb_kb_shop.id')->join('bzb_user on bzb_kb_comments.user_id=bzb_user.user_id')->where($where)->field('bzb_kb_comments.*')->limit($Page->firstRow,$Page->listRows)->order("bzb_kb_comments.create_time desc")->select();
		//dump($views_data);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/comment","评论列表",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cplist=$commentlist;
		
		//dump($commentlist);
		
		
		$this->display();
	}
	
	//删除评论
	function delcommemt(){
		$id=$_GET[id];
		$rs=M('kb_comments')->delete($id);
		if($rs){
			echo 0;
		}
		else{
			echo -1;
		}
	}

	
	//店铺分类
	public function shopcat(){
		$keyword=$_GET[keyword];
		$scms_type=$_GET[cms_type];
		if($keyword!=''){
			//$where="name like '%$keyword%' and pid=0";
			$where2="and name like '%$keyword%'";
		}
		else
		{
			$where="pid=0";		
		}
		$cat=M("kb_cat")->where($where)->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['id']]['catid']   = $row['id'];
			$cats[$row['id']]['catname'] = $row['name'];

			$childcat=M("kb_cat")->where("pid=$row[id] $where2")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['id']]['children'][$row2['id']]['id']   = $row2['id'];
				$cats[$row['id']]['children'][$row2['id']]['name'] = $row2['name'];
				}
			}
		}

		//dump($cats);exit;
		$this->keyword=$keyword;
		$this->cats=$cats;
		$this->display();
	}

	//添加分类
	public function addcat(){
		
		$cat=M("kb_cat")->where("pid=0")->select();
		$this->cat=$cat;
		$this->display();
	}
	
	//执行添加分类
	public function savecat(){
		$_POST[create_time]=time();
		if($_POST[pid]==0){
			$_POST[type_level]=1;
		}
		if($_POST[pid]>0){
			$_POST[type_level]=2;
		}
		if(M('kb_cat')->add($_POST))
			$this->success('添加成功',U('addcat'));
		else
			$this->error('error');
	}
	
	//修改分类
	public function updatecat(){
		$id=$_GET[ct_id];
		$shop_cat=M("kb_cat")->where("id=$id")->find();
		$cat=M("kb_cat")->where("pid=0")->select();
		//dump($cms_cat);exit;
		$this->cms_cat=$shop_cat;
		$this->cat=$cat;
		$this->display();
	}
	
	//执行修改分类
	public function doupdatecat(){
		//dump($_POST);exit;
		$_POST[create_time]=time();
		if($_POST[pid]==0){
			$_POST[type_level]=1;
		}
		if($_POST[pid]>0){
			$_POST[type_level]=2;
		}
		if(M('kb_cat')->save($_POST))
			$this->success('修改成功');
		else
			$this->error('error');
	}
	
	//删除分类
	public function delcat(){
		M('kb_cat')->where('id='.I('id'))->delete();
		echo 'success';
	}
	
	//企业优惠券
	public function coupon(){
			
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="coupon_number like '%$keyword%' or coupon_text like '%$keyword%'";
		}
		else
		{
			$where="1=1";		
		}
		$usernum=M()->query("select count(t.counts) a from (select id,count(*) counts from bzb_kb_coupon where $where group by batch) t");
		//dump($usernum);exit;

		$couponlist=M('kb_coupon')->where($where)->limit($Page->firstRow,$Page->listRows)->order("check_time desc")->group("batch")->select();
		$Page = new \Think\Page($usernum[0][a],20);//导入分页类
		$show  = $Page->show();
		//dump($views_data);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/coupon","券的管理",0);
		
		$this->usernum=$usernum[0][a];
		$this->page=$show;
		$this->keyword=$keyword;
		$this->couponlist=$couponlist;
		
		$this->display();
	}
	
	//审核订单
	public function docheckcoupon(){
		$id=$_GET[id];
		
		$data[status]=1;
		$rs=M("kb_coupon")->where("batch=$id and status=0")->save($data);
		if($rs){
			echo 0;
		}
		else{
			echo -1;
		}
	}
	
	//企业优惠券列表
	public function mycoupon(){
		
		$batchid=$_GET[id];
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(coupon_number like '%$keyword%' or coupon_text like '%$keyword%') and batch=$batchid";
		}else
		{
			$where="batch=$batchid";		
		}
		$usernum=M('kb_coupon')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$couponlist=M('kb_coupon')->where($where)->limit($Page->firstRow,$Page->listRows)->order("check_time desc")->select();
		//dump($views_data);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/mycoupon","企业券的管理",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->couponlist=$couponlist;
		
		
		
		
		$this->display();
	}
	   
	    
	//报错
	public function baocuo(){
		
		
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_kb_shop.name like '%$keyword%' ";
		}else
		{
			$where="1=1";		
		}
		$usernum=M('kb_baocuo')->join('bzb_kb_shop on bzb_kb_baocuo.shop_id=bzb_kb_shop.id')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show = $Page->show();
		$baocuolist=M('kb_baocuo')->join('bzb_kb_shop on bzb_kb_baocuo.shop_id=bzb_kb_shop.id')->where($where)->field('bzb_kb_baocuo.*')->limit($Page->firstRow,$Page->listRows)->order("bzb_kb_baocuo.create_time desc")->select();
		//dump($views_data);//die;
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Koubei/baocuo","券的管理",0);
		
		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->baocuolist=$baocuolist;
		
		//dump($baocuolist);
		
		
		$this->display();
	}
	
}
		
