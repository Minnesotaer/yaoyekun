<?php
namespace Admin\Controller;
use Think\Controller;
class FengongshiController extends BaseController {

	//分公司列表
	public function fgslist(){
		$keyword=$_GET[keyword];
		$scms_type=$_GET[cms_type];
		if($keyword!=''){
			$where="fgsname like '%$keyword%'";
		}
		else
		{
			$where="1=1";		
		}
		
		$data_num=M('fengongshi')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('fengongshi')->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->where($where)->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/fgslist","分公司列表",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->fgs_data=$data;
		$this->display();
	}
	
	//编辑分公司
	public function update(){
		$fgs=M('fengongshi')->find(I('fgs_id'));

		$province=M("province")->select();
		$this->province=$province;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/update","编辑分公司",$data['fgs_id']);
		
		$this->fgs=$fgs;
		$this->display();
	}
	//执行编辑公司
	public function edit(){
		$uname=$_GET[username];
		$id=$_GET[id];
		$unum=M("fengongshi")->where("username='$uname' and id!=$id")->count();

		if($unum>0){
			echo -2;exit;
		}
		$phone=$_GET[phone];
		$unum2=M("fengongshi")->where("phone='$phone' and id!=$id")->count();
		if($unum2>0){
			echo -3;exit;
		}
		
		if($_GET['password']==''){
			$_GET['password']=$_GET['oldpassword'];
		}
		else{
			$_GET['password']=md5($_GET['password']);
		}
		$_GET['ok_time']=strtotime($_GET[ok_time]);
		if(M('fengongshi')->save($_GET))
			echo 0;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/edit","执行编辑公司",0);
	}
	//添加公公司
	public function add(){
		
		$province=M("province")->select();
		$this->province=$province;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/add","添加公公司",0);
		
		$this->category=$cats;
		$this->display();
	}
	//执行添加分公司
	public function save(){
		$uname=$_POST[username];
		$unum=M("fengongshi")->where("username='$uname'")->count();

		if($unum>0){
			echo -2;exit;
		}
		$phone=$_POST[phone];
		$unum2=M("fengongshi")->where("phone='$phone'")->count();
		if($unum2>0){
			echo -3;exit;
		}
		$_POST['password']=md5($_POST['password']);
		$_POST['create_time']=time();
		$_POST['ok_time']=strtotime($_POST[ok_time]);
		if(M('fengongshi')->add($_POST))
			echo 0;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/save","执行添加分公司",0);
	}
	
	//禁用用户
	public function stopfgs(){
		$id=$_GET[fgs_id];
		if(M('fengongshi')->where("id=$id")->save(array('is_active'=>0)))
			echo 0;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/stopfgs","禁用用户",$data['fgs_id']);
	}
	//启用用户
	public function startfgs(){
		$id=$_GET[fgs_id];
		if(M('fengongshi')->where("id=$id")->save(array('is_active'=>1)))
			echo 0;
		else
			echo -1;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Fengongshi/startfgs","启用用户",$data['fgs_id']);
	}
	
	//分公司登陆验证
    public function fgslogin(){
        $member = M('fengongshi');
        $id =I('fgs_id');
        $password =I('password');
        
        //验证账号密码是否正确
        $user = $member->where(array('id'=>$id,'password'=>$password))->find();

        if(!$user) {
            echo -1;//密码错误
			exit;
        }
 
        //验证是否为管理员
        //更新登陆信息
        $data =array(
            'id' => $user['id'],
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(),
        );
        
        //如果数据更新成功  跳转到后台主页
        if($member->save($data)){
            session('fgs_id',$user['id']);
            session('fgs_name',$user['fgsname']);
            session('province_id',$user['province_id']);
            session('city_id',$user['city_id']);
            session('area_id',$user['area_id']);
            session('type',$user['type']);
			
            echo 0;exit;
        }
		else{
			echo -2;
		}        

    }

}