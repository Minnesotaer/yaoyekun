<?php
namespace Admin\Controller;
use Think\Controller;
class BzbController extends BaseController {
    public function index(){
        
    }
	public function bz(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%') and bzb_proof.status >=-1";
		}
		else
		{
			$where="status >=-1";		
		}
		$bznum=M('proof')->join('bzb_user on bzb_user.consumer_id=bzb_proof.consumer_id')->where($where)->count();
		$Page = new \Think\Page($bznum,300);//导入分页类
		$show  = $Page->show();
		$bzlist=M('proof')->join('bzb_user on bzb_user.consumer_id=bzb_proof.consumer_id')->field('bzb_proof.*,bzb_user.realname,bzb_user.phone')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Bzb/bz","查看报账记录",0);
		
		$this->bznum=$bznum;
		$this->keyword=$keyword;
		$this->page=$show;
		
		$this->bzlist=$bzlist;
		$this->display();
	}
	public function queue(){
		$queue_num=M('queue')->count();
		$Page = new \Think\Page($queue_num,500);//导入分页类
		$show  = $Page->show();
		$queue_data=M('queue')->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->queue=$queue_data;
		$this->display();
	}
	public function order(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%' or bzb_orders.id like '%$keyword%'";
		}
		else
		{
			$where="1=1";		
		}
		$order_num=M('orders')->join('bzb_company on bzb_orders.company_id=bzb_company.company_id')->join('bzb_user on bzb_user.consumer_id=bzb_orders.consumer_id')->where($where)->count();
		$Page = new \Think\Page($order_num,300);//导入分页类
		$show  = $Page->show();
		$order_data=M('orders')->join('bzb_company on bzb_orders.company_id=bzb_company.company_id')->join('bzb_user on bzb_user.consumer_id=bzb_orders.consumer_id')->field('bzb_orders.id,bzb_orders.order_sn,bzb_orders.money,bzb_orders.create_time,remark,realname,bzb_user.phone as phone,bzb_company.company_name,bzb_orders.is_check')->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->where($where)->select();
		//dump($order_data);die;
		foreach($order_data as $k=>$v){
			if($v[order_sn]=='')
				$order_data[$k][order_sn]=date('Ymd',$v[create_time]).$v[id];
		}
		
		
		$this->keyword=$keyword;
		$this->page=$show;
		$this->order=$order_data;
		$this->display();
	}

}