<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize(){
		/*$Menu=M('menu');
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
		$this->mainmenu=$mainmenu;*/
        $sid = session('admin_id');
		
        //判断用户是否登陆
        if(!isset($sid)) {
            redirect(U('Login/index'));
        }
		//dump($_SESSION);
		//判断管理用户权限的
		$access = \Org\Util\Rbac::AccessDecision();
        if(!$access){
            $this->error('你没有权限');
        }

    }

}