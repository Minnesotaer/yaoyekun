<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
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
    }
	public function test(){
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
	}
}