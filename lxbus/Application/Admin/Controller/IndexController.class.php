<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
		//dump($_SESSION);

		$this->display();
    }
	//修改密码
	public function editad(){
		$id=$_SESSION['admin_id'];
		$rs=M('admin')->where("id=$id")->find();
		if(!$rs){
			$this->error("管理用户不存在");
		}

		$this->display();
	}

	//执行修改后台用户
	public function doeditad(){
		$data['id']=$_SESSION['admin_id'];
		$password=I('password');
		$repassword=I('repassword');
		if($password!=''&&$repassword!=''&&$password!=$repassword){
			echo -1;exit;
		}
		if($password!=''&&$repassword!=''){
			$data['password']=md5($password);
			$rs=M('admin')->save($data);
		}
		if($rs)
			echo 0;
		else
			echo -2;
	}
	/*public function test(){
		$Menu=M('menu');
		$mainmenu=$Menu->where('menu_parent_id=0')->field('menu_id,menu_name,menu_icon')->select();
		//echo $Menu->getLastSql();
		$childmenu=M('menu')->where('menu_parent_id>0')->field('menu_name,menu_icon,menu_url,menu_parent_id')->select();
		foreach($mainmenu as $v=>$value){
		$child=array();
		foreach($childmenu as $v2=>$value2){
		if($value['menu_id']==$value2['menu_parent_id']){
			array_push($child,$value2);
		}
		}
		$mainmenu[$v]['child']=$child;
		}
		$this->mainmenu=$mainmenu;

		$views_data['bzbcount']=getBZBcount();
		$views_data['cscount']=getCScount();
		$views_data['cpcount']=getCPcount();
		$views_data['mncount']=getMNcount();
		$views_data['moneycount']=getMoneycount();

		$this->views_data=$views_data;
		$this->display();
		//dump($mainmenu);
	}*/
}