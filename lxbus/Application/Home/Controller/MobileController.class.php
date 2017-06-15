<?php
namespace Home\Controller;
use Think\Controller;

Class MobileController extends Controller{
	//初始化，一些公用的操作
	public function _initialize(){
		
	}
	//首页广告
	function home(){
		
		
		$this->display();
	}
	//提交定位
	function savestop(){
		
		$stops=M("stop")->where("is_active=1")->select();
		$this->stops=$stops;
		$this->title="提交站点定位";
		$this->display();
	}
	//提交定位保存
	function dosavestop(){
		$stopid=$_GET[stop];
		$data[longitude]=$_GET[longitude];
		$data[latitude]=$_GET[latitude];
		$data[create_time]=time();
		$rs=M("stop")->where("id=$stopid")->save($data);
		if($rs){
			echo 0;
		}
		else{
			echo -1;
		}
		
	}
	
	//首页广告
	function reg(){
		
		$this->title="用户注册";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	//找回密码
	function getpass(){
		
		$this->title="找回密码";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//上传图片
	function uppic(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			$is_ok=1;
		}
		else{
			$is_ok=0;
		}
		$this->user_id=$user_id;
		$this->token=$token;
		$this->is_ok=$is_ok;
		$this->title="上传图片";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	//上传图片
	function myuppic(){

		$keyword1=$_GET[keyword1];
		$keyword2=$_GET[keyword2];

		$time1=strtotime($keyword1);
		$time2=strtotime($keyword2);
//echo $keyword1;
		if($keyword1!=''&& $keyword2!=''){
			$where="(create_time)>=$time1 and (create_time)<=$time2";
		}else{
			$where = "1=1";

		}


		$usernum=M('driver_pic')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('driver_pic')->field('id,user_id,pic_url,create_time,create_ip')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		//dump($views_data['userlist']);die;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
//		$this->keyword=$keyword;
		$this->keyword1=$keyword1;
		$this->keyword2=$keyword2;
		$this->userlist=$userlist;

		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			$mypics=M("driver_pic")->where("user_id=$user_id")->select();
			$this->img=$mypics;
			$is_ok=1;
		}
		else{
			$is_ok=0;
		}
		$this->user_id=$user_id;
		$this->token=$token;
		$this->is_ok=$is_ok;
		$this->title="我上传的图片";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//进行图片保存
	function saveuppic(){
		$user_id=$_POST[user_id];
		$token=$_POST[token];
		$longitude=$_POST[longitude];
		$latitude=$_POST[latitude];

		$img = $_POST['img'];
		
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
		//判断是否合法
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			$picname=time().$ext;
			// 生成的文件名
			$photo = "Uploads/buspics/".$picname;
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
			$shop[user_id]=$user[user_id];
			$shop[create_ip]=get_client_ip();
			$shop[longitude]=$longitude;
			$shop[latitude]=$latitude;
			$shop[create_time]=time();
			
			$rs=M("driver_pic")->add($shop);
			// 返回
			header('content-type:application/json;charset=utf-8');
			$ret = array('img'=>$backpic,'msg'=>'图片上传成功');
			echo json_encode($ret);	
			exit;	

		}
		else{
			echo "非法操作";
		}

	}
	
	//删除图片
	function delpic(){
		$id=$_GET[id];
		$pic=$_GET[pic];
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		//判断是否合法
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			//进行删除
			$rs=M("driver_pic")->where("id=$id")->delete();
			unlink("./".$pic);
		}else{
			echo "非法操作";
		}
	}
	
	//我的路线
	function myline(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			//已经绑定线路id
			if($user[line_id]>0){
				$line=M("line")->where("id=$user[line_id]")->find();
				$stops=M("stop")->join("lxbus_line2stop on lxbus_line2stop.stop_id=lxbus.stop.id")->where("lxbus_line2stop.line_id=$user[line_id]")->select();
				
				$this->okline=1;
				$this->line=$line;
				$this->stops=$stops;
			}else{
				$this->okline=0;
			}
			$is_ok=1;
			//所有的路线
			$lines=M("line")->where("is_active=1")->select();
			//dump($lines);
			$this->lines=$lines;
		}else{
			$is_ok=0;
		}
		
		$this->is_ok=$is_ok;
		$this->title="我的路线";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//排行榜
	function paihang(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			$is_ok=1;
		}
		else{
			$is_ok=0;
		}
		$this->is_ok=$is_ok;
		$this->title="排行榜";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//客服中心
	function kefu(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$user=M("user")->where("user_id=$user_id and access_token='$token'")->find();
		if($user){
			$is_ok=1;
		}
		else{
			$is_ok=0;
		}
		$this->is_ok=$is_ok;
		$this->title="客服中心";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//消息中心
	function message(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$uswhere[user_id]=$user_id;
		$uswhere[access_token]=$token;
		$us=M('user')->where($uswhere)->find();
		$is_ok=0;
		if($us){
			$is_ok=1;
			//$this->user=$us;
		}
		//消息列表
		if($is_ok==1){
			
			$allmsgnum=M("message")->where("user_id=$user_id")->count();//总数
			if($allmsgnum>0){
				$allmsglist=M("message")->where("user_id=$user_id")->limit("10")->order("create_time desc")->select();//列表	
				$this->allmsglist=$allmsglist;
			}
			
			//已读
			$yesmsgnum=M("message")->where("user_id=$user_id and is_read=1")->count();//总数
			if($yesmsgnum>0){
				$yesmsglist=M("message")->where("user_id=$user_id and is_read=1")->limit("10")->order("create_time desc")->select();//列表	
				$this->yesmsglist=$yesmsglist;
			}
			
			//未读
			$nomsgnum=M("message")->where("user_id=$user_id and is_read=0")->count();//总数
			if($nomsgnum>0){
				$nomsglist=M("message")->where("user_id=$user_id and is_read=0")->limit("10")->order("create_time desc")->select();//列表	
				$this->nomsglist=$nomsglist;
			}
			
			
			
			$this->allmsgnum=$allmsgnum;//消费者推荐数
			$this->yesmsgnum=$yesmsgnum;
			$this->nomsgnum=$nomsgnum;
			
		}
		
		$this->is_ok=$is_ok;
		$this->user_id=$user_id;
		$this->token=$token;
				
		$this->title="消息中心";
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display();
	}
	
	//查看消息
	public function msgview(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$id=$_GET[id];
		$uswhere[user_id]=$user_id;
		$uswhere[access_token]=$token;
		$us=M('user')->where($uswhere)->find();
		$is_ok=0;
		if($us){
			$is_ok=1;
		}
		
		$msg=M("message")->where("user_id=$user_id and id=$id")->find();
		if($msg[is_read]==0){
			$data=array('is_read'=>1,'read_time'=>time());
			$rs=M("message")->where("id=$id")->save($data);//标志为已读
		}
		$this->msg=$msg;
		$this->is_ok=$is_ok;
		$this->title="消息详情";//网站title
		$this->description="领先巴士，相信声音传播的力量";//网如何description
		$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
		$this->display("msgview");
	}
	
	//app相关菜单
	//菜单链接，对应8
	function menu(){
		$domenu=$_GET[domenu];
		$doname="";
		
		$user_id=$_GET[user_id];
		$token=$_GET[token];

		
		switch ($domenu)
		{
			case "lxbusmiss":
				/*
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				//帮助中心内容
				$count=M("yk_cms")->where("cms_type=11 and is_trash=0")->count();
		
				// 查询满足要求的总记录数
				$Page       = new \Think\Page($count,10);
				// 实例化分页类 传入总记录数和每页显示的记录数(10)
				$show       = $Page->show();
				// 分页显示输出
				// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
				$artlist2=M("yk_cms")->where("cms_type=11 and is_trash=0")->limit($Page->firstRow.','.$Page->listRows)->order('cms_publish_date desc')->select();//帮助中心的列表
				
				// 赋值数据集
				$this->assign('page',$show);// 赋值分页输出
				

				$this->artlist2=$artlist2;
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="领先小秘书";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_lxbusmiss");
				*/
				$doname="领先小秘书";
				$this->title=$doname;
				$this->doname=$doname;
				$this->display();
				break;  
			  
			case "wsq":
			  $doname="微社区";
			  $this->title=$doname;
			  $this->doname=$doname;
			  $this->display();
			break;
			  
			case "news":
				header("Location:http://m.huxiu.com");exit;
				/*
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				//帮助中心内容
				$count=M("yk_cms")->where("cms_type=13 and is_trash=0")->count();
		
				// 查询满足要求的总记录数
				$Page = new \Think\Page($count,10);
				// 实例化分页类 传入总记录数和每页显示的记录数(10)
				$show = $Page->show();
				// 分页显示输出
				// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
				$artlist2=M("yk_cms")->where("cms_type=13 and is_trash=0")->limit($Page->firstRow.','.$Page->listRows)->order('cms_publish_date desc')->select();//帮助中心的列表
				
				// 赋值数据集
				$this->assign('page',$show);// 赋值分页输出
				

				$this->artlist2=$artlist2;
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="市场资讯";//网站title
				$this->description="报账宝,消费报账";//网如何description
				$this->keyword="报账宝,消费报账";//网站关键字
				$this->display("menu_news");
				*/
			break;
			
			case "bzbgn":
				//$article=M("yk_cms")->where("cms_id=22")->find();
				$this->article="功能介绍内容";
				
				$this->title="功能介绍";
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display(menu_lxbusfeedback);
			break;
			
			
			
			case "bzbfeedback":
				//$article=M("yk_cms")->where("cms_id=24")->find();
				$this->article="帮助与反馈内容";
				
				$this->title="帮助与反馈";
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display(menu_lxbusfeedback);
			break;
			
			case "bzbcontect":
				//$article=M("yk_cms")->where("cms_id=25")->find();
				$this->article="举报与投诉内容";
				
				$this->title="举报与投诉";
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display(menu_lxbuscontect);
			break;
			
 
			case "msg":
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				$is_ok=0;
				if($us){
					$is_ok=1;
					//$this->user=$us;
				}
				//消息列表
				if($is_ok==1){
					
					$allmsgnum=M("message")->where("user_id=$user_id")->count();//总数
					if($allmsgnum>0){
						$allmsglist=M("message")->where("user_id=$user_id")->limit("10")->order("create_time desc")->select();//列表	
						$this->allmsglist=$allmsglist;
					}
					
					//已读
					$yesmsgnum=M("message")->where("user_id=$user_id and is_read=1")->count();//总数
					if($yesmsgnum>0){
						$yesmsglist=M("message")->where("user_id=$user_id and is_read=1")->limit("10")->order("create_time desc")->select();//列表	
						$this->yesmsglist=$yesmsglist;
					}
					
					//未读
					$nomsgnum=M("message")->where("user_id=$user_id and is_read=0")->count();//总数
					if($nomsgnum>0){
						$nomsglist=M("message")->where("user_id=$user_id and is_read=0")->limit("10")->order("create_time desc")->select();//列表	
						$this->nomsglist=$nomsglist;
					}
					
					
					
					$this->allmsgnum=$allmsgnum;//消费者推荐数
					$this->yesmsgnum=$yesmsgnum;
					$this->nomsgnum=$nomsgnum;
					
				}
				
				$this->is_ok=$is_ok;
				$this->user_id=$user_id;
				$this->token=$token;
				
				$this->title="消息中心";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_msg");
			break;
			
			//关于报账宝
			case "aboutlxbus":
				//$article=M("yk_cms")->where("cms_id=38")->find();
				$this->article="关于领先巴士的内容";
				$this->title="关于领先巴士";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				
				$this->display("menu_aboutlxbus");
			break;
			  
						  
			case "username":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="用户名";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_username");
			break;
			  
			case "realname":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="用户真名";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_realname");
			break;
			  
			case "nickname":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="用户昵称";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_nickname");
			break;
			case "qq":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="用户QQ";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_qq");
			break;
			  
			case "phone":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="用户手机";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_phone");
			break;
			  
			case "email":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
					$this->user=$us;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="电子邮箱";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_email");
			break;
			
						
			case "pwd":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="登录密码";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_pwd");
			break;
			
			case "pwd2":
				$user_id=$_GET[user_id];
				$token=$_GET[token];
				$uswhere[user_id]=$user_id;
				$uswhere[access_token]=$token;
				$us=M('user')->where($uswhere)->find();
				
				$is_ok=0;
				if($us){
					$is_ok=1;
				}
				
				$this->user_id=$user_id;
				$this->token=$token;
				$this->is_ok=$is_ok;
				$this->title="提现密码";//网站title
				$this->description="领先巴士，相信声音传播的力量";//网如何description
				$this->keyword="领先巴士，相信声音传播的力量";//网站关键字
				$this->display("menu_pwd2");
			break;
			
			
			
			default:
				$doname="欢迎使用领先巴士";
				$this->title=$doname;
				$this->doname=$doname;
				$this->display();
			break;
		}
	}
}

?>