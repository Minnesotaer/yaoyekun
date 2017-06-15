<?php
namespace Admin\Controller;
use Think\Controller;
class CaiwuController extends BaseController {

	//用户银行卡
	public function userbank(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$bank_num=M('bank_message')->join('bzb_user on bzb_user.user_id=bzb_bank_message.user_id')->where($where)->count();
		$Page = new \Think\Page($bank_num,20);//导入分页类
		$show  = $Page->show();
		$bank_data=M('bank_message')->join('bzb_user on bzb_user.user_id=bzb_bank_message.user_id')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->bank=$bank_data;
		$this->display();
	}
	//消费者提现
	public function cswithdraw(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$withdraw_num=M('consumer_withdraw')->join('bzb_consumer on bzb_consumer.consumer_id=bzb_consumer_withdraw.consumer_id')->join('bzb_user on bzb_user.consumer_id=bzb_consumer_withdraw.consumer_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('consumer_withdraw')->join('bzb_consumer on bzb_consumer.consumer_id=bzb_consumer_withdraw.consumer_id')->join('bzb_user on bzb_user.consumer_id=bzb_consumer_withdraw.consumer_id')->field('bzb_user.phone as phone,bzb_consumer_withdraw.*')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	//执行消费者提现
	public function dowithdraw(){
		$w_id=I('w_id');
		$data=M('consumer_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			if(M('consumer_withdraw')->save($data))
			echo 'success';
		else
			echo '数据提交失败';
		}
		else
			echo '';
	}
	//业务经理提现
	public function mnwithdraw(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$withdraw_num=M('manager_withdraw')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_withdraw.manager_id')->join('bzb_user on bzb_user.manager_id=bzb_manager_withdraw.manager_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('manager_withdraw')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_withdraw.manager_id')->join('bzb_user on bzb_user.manager_id=bzb_manager_withdraw.manager_id')->field('bzb_user.phone as phone,bzb_manager_withdraw.*')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	//执行消费者提现
	public function domnwithdraw(){
		$w_id=I('w_id');
		$data=M('manager_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			if(M('manager_withdraw')->save($data))
				echo 'success';
			else
				echo '数据提交失败';
		}
		else
			echo '';
	}
	//商家提现
	public function cpwithdraw(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$withdraw_num=M('company_withdraw')->join('bzb_company on bzb_company.company_id=bzb_company_withdraw.company_id')->join('bzb_user on bzb_user.company_id=bzb_company_withdraw.company_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('company_withdraw')->join('bzb_company on bzb_company.company_id=bzb_company_withdraw.company_id')->join('bzb_user on bzb_user.company_id=bzb_company_withdraw.company_id')->field('bzb_user.phone as phone,bzb_company_withdraw.*,bzb_company.company_name as company_name')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	//执行商家提现
	public function docpwithdraw(){
		$w_id=I('w_id');
		$data=M('company_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			if(M('company_withdraw')->save($data))
				echo 'success';
			else
				echo '数据提交失败';
		}
		else
			echo '';
	}
	//商家充值
	public function charge(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$charge_num=M('charge')->join('bzb_company on bzb_company.company_id=bzb_charge.company_id')->join('bzb_user on bzb_user.company_id=bzb_charge.company_id')->where($where)->count();
		$Page = new \Think\Page($charge_num,20);//导入分页类
		$show  = $Page->show();
		$charge_data=M('charge')->join('bzb_company on bzb_company.company_id=bzb_charge.company_id')->join('bzb_user on bzb_user.company_id=bzb_charge.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_charge.create_time desc")->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->charge=$charge_data;
		$this->display();
	}
	//执行商家充值
	public function docharge(){
		$c_id=I('c_id');
		$data=M('charge')->find($c_id);
		//dump($data);die;
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			if(M('charge')->save($data)){
				if(cp_money_move($data['company_id'],$data['amount'],'充值',$c_id))
					echo 'success';
				else{
					$data['status']=0;
					$data['check_time']=0;
					M('charge')->save($data);
					echo 'error';
					}
			}
			else
				echo '数据提交失败';
		}
		else
			echo '-1';
	}
	//消费者财务移动
	public function cscaiwuyidong(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$cscaiwuyidong_num=M('consumer_money_record')->join('bzb_user on bzb_user.consumer_id =bzb_consumer_money_record.consumer_id')->where($where)->count();
		$Page = new \Think\Page($cscaiwuyidong_num,20);//导入分页类
		$show  = $Page->show();
		$cscaiwuyidong_data=M('consumer_money_record')->join('bzb_user on bzb_user.consumer_id =bzb_consumer_money_record.consumer_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_consumer_money_record.create_time desc")->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cscaiwuyidong=$cscaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	//商家财务移动
	public function cpcaiwuyidong(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$cpcaiwuyidong_num=M('company_money_record')->join('bzb_user on bzb_user.company_id =bzb_company_money_record.company_id')->join('bzb_company on bzb_company.company_id=bzb_company_money_record.company_id')->where($where)->count();
		$Page = new \Think\Page($cpcaiwuyidong_num,20);//导入分页类
		$show  = $Page->show();
		$cpcaiwuyidong_data=M('company_money_record')->join('bzb_user on bzb_user.company_id =bzb_company_money_record.company_id')->join('bzb_company on bzb_company.company_id=bzb_company_money_record.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_company_money_record.create_time desc")->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cpcaiwuyidong=$cpcaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	//业务经理财务移动
	public function mncaiwuyidong(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$mncaiwuyidong_num=M('manager_money_record')->join('bzb_user on bzb_user.manager_id =bzb_manager_money_record.manager_id')->where($where)->count();
		$Page = new \Think\Page($mncaiwuyidong_num,20);//导入分页类
		$show  = $Page->show();
		$mncaiwuyidong_data=M('manager_money_record')->join('bzb_user on bzb_user.manager_id =bzb_manager_money_record.manager_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_manager_money_record.create_time desc")->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->mncaiwuyidong=$mncaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//消费者余额列表
	public function csyue(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$csyue_num=M('consumer')->join('bzb_user on bzb_user.consumer_id =bzb_consumer.consumer_id')->where($where)->count();
		$Page = new \Think\Page($csyue_num,20);//导入分页类
		$show  = $Page->show();
		$csyue_data=M('consumer')->join('bzb_user on bzb_user.consumer_id =bzb_consumer.consumer_id')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->csyue=$csyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	//企业余额列表
	public function cpyue(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$cpyue_num=M('company')->join('bzb_user on bzb_user.company_id =bzb_company.company_id')->where($where)->count();
		$Page = new \Think\Page($cpyue_num,20);//导入分页类
		$show  = $Page->show();
		$cpyue_data=M('company')->join('bzb_user on bzb_user.company_id =bzb_company.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cpyue=$cpyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//业务经理余额列表
	public function mnyue(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$mnyue_num=M('manager')->join('bzb_user on bzb_user.manager_id =bzb_manager.manager_id')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$mnyue_data=M('manager')->join('bzb_user on bzb_user.manager_id =bzb_manager.manager_id')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->mnyue=$mnyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//慈善基金
	public function founder(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('founder')->where($where)->sum('money');
		$data_num=M('founder')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('founder')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//重复购券
	public function requeue(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('requeue_fee')->where($where)->sum('money');
		$data_num=M('requeue_fee')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('requeue_fee')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//平台利润
	public function profit(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('money_record')->where($where)->sum('money');
		$data_num=M('money_record')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('money_record')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}

	//营业税
	public function tax(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('tax')->where($where)->sum('money');
		$data_num=M('tax')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('tax')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//平台维护
	public function weihu(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('weihu_fee')->where($where)->sum('money');
		$data_num=M('weihu_fee')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('weihu_fee')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//分公司津贴
	public function fgs(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('fengongshi_money')->where($where)->sum('money');
		$data_num=M('fengongshi_money')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('fengongshi_money')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	//信息费
	public function sms(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($fromdate>0){
			$where="create_time>$fromdate";
		}elseif($todate>0){
			$where.=" and create_time<$todate";
		}else
		{
			$where="1=1";		
		}
		$data_money=M('sms_fee')->where($where)->sum('money');
		$data_num=M('sms_fee')->where($where)->count();
		$Page = new \Think\Page($mnyue_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('sms_fee')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
}