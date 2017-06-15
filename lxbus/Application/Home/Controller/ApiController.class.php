<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller {
	//初始化
	public function _initialize(){
		
	}
	//验证登陆
	public function login(){
		$result=array();
		$param=getParamFromjson();
		if($param !=null){
			$username=$param['username'];
			$password=MD5($param['password']);
			
			$user=M('user')->where("(user_name='$username' or phone='$username') and password='$password'")->find();
			//dump($user);die;
			if($user){

				$result['status']['status_code']=0;
				$result['status']['status_reason']='';
				$result['result']['u_id']=$user['user_id'];
				$result['result']['user_name']=$user['user_name'];
				$result['result']['user_phone']=$user['phone'];
				$result['result']['access_token']=$user['access_token'];

				
				//进行登录记录
				$data=array(
				'user_id' => $user['user_id'],
				'last_login_time' => time(),
				'last_login_ip' => get_client_ip()
				);
				M('user')->save($data);
			}
			else{
				$result['status']['status_code']=-2;
				$result['status']['status_reason']='帐号或密码不正确';
			}
		}
		else{
			$result['status']['status_code']=-1;
			$result['status']['status_reason']='param error';
		}
		echo json_encode($result);
	}
	
	//消费报账页面
	function main(){
		//返回今日消费额，报账积分，报账券
		$result=array();
		$param=getParamFromjson();
		if($param !=null){
			$where['user_id']=$param['user_id'];
			$where['access_token']=$param['token'];
			$where['is_active']=1;
			$user=M('user')->where($where)->find();
			//dump($user);die;
			if($user){
				$result['status']['status_code']=0;
				$result['status']['status_reason']='';
				

				//领先巴士的最新版本号
				$result['result']['newVerCode']=1;
				//领先巴士的最新版本号
				$result['result']['newVerName']="1.0";
				
				//最新路线的站点定位
				$rs=M("stop")->where("is_active=1")->select();
				$result['result']['stops']=$rs;			
			}
			else{
				$result['status']['status_code']=-2;
				$result['status']['status_reason']='用户不存在';
			}
		}
		else{
			$result['status']['status_code']=-1;
			$result['status']['status_reason']='param error';
		}
		//dump($result);die;
		echo json_encode($result);
	}
	
	//我的
	function mylxbus(){
		//返回用户真名，用户头像
		$result=array();
		$param=getParamFromjson();
		if($param !=null){
			$where['user_id']=$param['user_id'];
			$where['access_token']=$param['token'];
			$where['is_active']=1;
			$user=M('user')->where($where)->find();
			//dump($user);die;
			if($user){
				$result['status']['status_code']=0;
				$result['status']['status_reason']='';
				
				
				//用户真名
				$real_name=$user[realname];
				if($real_name==''){
						$real_name='--';
					}
				$result['result']['real_name']=$real_name;
				
				//用户头像
				$avatar=getAvatar($param['user_id'],1);
				if($avatar==1){
					$avatar=C('siteurl')."Uploads/default.png";
				}
				else
					$avatar=C('siteurl').$avatar;
				$result['result']['avatar']=$avatar;
				//$result['result']['cp_id']=$user[company_id];
				
				//$msgnum=M("message")->where("user_id=$user[user_id] and is_read=0")->count();
				$result['result']['msgnum']=2;//未读消息数
				
				//上传的图片
				$picnum=M("driver_pic")->where("user_id=$param[user_id]")->count();
				$result['result']['picnum']=$picnum;
				
				//播放次数
				$adsnum=M("ads_record")->where("user_id=$param[user_id]")->count();
				$result['result']['adsnum']=$adsnum;
			}
			else{
				$result['status']['status_code']=-2;
				$result['status']['status_reason']='用户不存在';
			}
		}
		else{
			$result['status']['status_code']=-1;
			$result['status']['status_reason']='param error';
		}
		echo json_encode($result);
	}
	
	//设置中心
	function setting(){
		//返回用户真名，用户头像,昵称，手机，邮箱
		$result=array();
		$param=getParamFromjson();
		if($param !=null){
			$where['user_id']=$param['user_id'];
			$where['access_token']=$param['token'];
			$where['is_active']=1;
			$user=M('user')->where($where)->find();
			//dump($user);die;
			if($user){
				$result['status']['status_code']=0;
				$result['status']['status_reason']='';
				
				
				//用户真名
				$real_name=$user[realname];
				if($real_name==''){
					$real_name='--';
				}
				$result['result']['real_name']=$real_name;
				
				//用户QQ
				$qq=$user[qq];
				if($qq==''){
					$qq='--';
				}
				$result['result']['qq']=$qq;
				
				//用户头像
				$avatar=getAvatar($param['user_id'],1);
				if($avatar==1){
					$avatar=C('siteurl')."Uploads/default.png";
				}
				else
					$avatar=C('siteurl').$avatar;
				$result['result']['avatar']=$avatar;
				
				//用户昵称
				$nick_name=$user[nickname];
				if($nick_name==''){
					$nick_name='--';
				}
				$result['result']['nick_name']=$nick_name;
				
				//邮箱
				$email=$user[email];
				if($email==''){
					$email='--';
				}
				$result['result']['email']=$email;
				

			}
			else{
				$result['status']['status_code']=-2;
				$result['status']['status_reason']='用户不存在';
			}
		}
		else{
			$result['status']['status_code']=-1;
			$result['status']['status_reason']='param error';
		}
		echo json_encode($result);
	}
	
	//上传头像
	function upavatar(){
		$user_id=$_GET[user_id];
		$token=$_GET[token];
		$where['user_id']=$user_id;
		$where['access_token']=$token;
		$where['is_active']=1;
		$user=M('user')->where($where)->count();
		
		if($user>0){
			if (isset($_FILES['myFile'])) {
				//默认上传是200*200
				move_uploaded_file($_FILES['myFile']['tmp_name'], "./Uploads/avatar/" . $_FILES['myFile']['name']);
				//进行裁剪
				$photo="Uploads/avatar/" . $_FILES['myFile']['name'];
				$image = new \Think\Image(); 
				$image->open('./'.$photo);
				// 生成一个固定大小为162*162的缩略图并保存为thumb.jpg
				$image->thumb(162, 162,\Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/avatar/'.$user_id."-1.png");
				echo '1';
			}
			else{
				echo "2";
			}
		}
	}
	
}

?>