<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {
    //登陆主页
    public function index(){
        $this->display();
    }
    //登陆验证
    public function login(){
        $member = M('admin');
        $username =I('username');
        $password =I('password','','md5');
        $code = I('verify','','strtolower');
        //验证验证码是否正确
        //if(!($this->check_verify($code))){
        //    $this->error('验证码错误');
        //}
        //验证账号密码是否正确
        $user = $member->where(array('username'=>$username,'password'=>$password))->find();

        if(!$user) {
            $this->error('账号或密码错误 :(') ;
        }
        //验证账户是否被禁用
        //if($user['status'] == 0){
        //    $this->error('账号被禁用，请联系超级管理员 :(') ;
        //}
		/*
        if($user['type'] == 1){
            $this->error('您没权限登陆后台 :(') ;
        }*/
        //验证是否为管理员
        //更新登陆信息
        $data =array(
            'id' => $user['id'],
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(),
        );
        
        //如果数据更新成功  跳转到后台主页
        if($member->save($data)){
            session('admin_id',$user['id']);
            session('admin_name',$user['username']);
			
			
			if($user['username'] == C('ADMIN_AUTH_KEY')||$user['username'] == C('ADMIN_AUTH_KEY_TECH')){
				session(C('ADMIN_AUTH_KEY'),$user['username']);
			}else{
				\Org\Util\Rbac::saveAccessList($user['user_id']);

			}
            $this->success("登陆成功",U('Index/index'));
        }
		else
			$this->error("登陆失败00");
        //定向之后台主页
        

    }
    //验证码
    public function verify(){
        $Verify = new \Think\Verify();
        $Verify->codeSet = '0123456789';
        $Verify->fontSize = 13;
        $Verify->length = 4;
        $Verify->entry();
    }
    protected function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    public function logout(){
        session('admin_id',null);
        session('admin_name',null);
        session(C('ADMIN_AUTH_KEY'),null);
        redirect(U('Login/index'));
    }
}