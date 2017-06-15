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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/userbank","查看用户银行卡",0);		
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->bank=$bank_data;
		$this->display();
	}
	//消费者提现
	public function cswithdraw(){
		$keyword=$_GET[keyword];
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		if($fromdate>0){
			$where.=" and bzb_consumer_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_consumer_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_consumer_withdraw.status=$status";
		}
		$withdraw_num=M('consumer_withdraw')->join('bzb_consumer on bzb_consumer.consumer_id=bzb_consumer_withdraw.consumer_id')->join('bzb_user on bzb_user.consumer_id=bzb_consumer_withdraw.consumer_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('consumer_withdraw')->join('bzb_consumer on bzb_consumer.consumer_id=bzb_consumer_withdraw.consumer_id')->join('bzb_user on bzb_user.consumer_id=bzb_consumer_withdraw.consumer_id')->field('bzb_user.phone as phone,bzb_consumer_withdraw.*')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cswithdraw","消费者提现列表",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->status=$status;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	
	//消费者提现导出excel
	public function cswithdrawtoexcel(){
		$keyword=$_GET[keyword];
		
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		if($fromdate>0){
			$where.=" and bzb_consumer_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_consumer_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_consumer_withdraw.status=$status";
		}
		$xlsName  = "消费者提现";
		$xlsCell  = array(
		array('consumer_id','消费者编号'),
		array('phone','用户手机'),
		array('realname','用户名称'),
		array('amount','金额'),
		array('bank_name','银行'),
		array('bank_user','户主'),
		array('bank_num','卡号'),
		array('create_time','添加时间'),
		array('status','状态'),
		array('check_time','审核时间'),
		);
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cswithdrawtoexcel","消费者提现导出excel",0);
		
		$xlsData=M('consumer_withdraw')->join('bzb_consumer on bzb_consumer.consumer_id=bzb_consumer_withdraw.consumer_id')->join('bzb_user on bzb_user.consumer_id=bzb_consumer_withdraw.consumer_id')->field('bzb_user.phone as phone,bzb_consumer_withdraw.*')->where($where)->select();
		//dump($withdraw_data);
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['status']=getCheckstausdes2($v['status']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['check_time']=$v['check_time']>0?date('Y-m-d H:i',$v['check_time']):'未审核';
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
		
		
	}

	
	
	//执行消费者提现
	public function dowithdraw(){
		$w_id=I('w_id');
		$data=M('consumer_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			
			//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"Caiwu/dowithdraw","执行消费者提现",$data['w_id']);
			
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
		
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		if($fromdate>0){
			$where.=" and bzb_manager_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_manager_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_manager_withdraw.status=$status";
		}
		$withdraw_num=M('manager_withdraw')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_withdraw.manager_id')->join('bzb_user on bzb_user.manager_id=bzb_manager_withdraw.manager_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('manager_withdraw')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_withdraw.manager_id')->join('bzb_user on bzb_user.manager_id=bzb_manager_withdraw.manager_id')->field('bzb_user.phone as phone,bzb_manager_withdraw.*')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"Caiwu/mnwithdraw","业务经理提现",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->status=$status;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	
	//业务经理提现导出excel
	public function mnwithdrawtoexcel(){
		$keyword=$_GET[keyword];
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		
		if($keyword!=''){
			$where="bzb_user.realname like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		if($fromdate>0){
			$where.=" and bzb_manager_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_manager_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_manager_withdraw.status=$status";
		}
		$xlsName  = "业务经理提现";
		$xlsCell  = array(
		array('manager_id','业务经理编号'),
		array('realname','名称'),
		array('phone','用户手机'),
		array('amount','金额'),
		array('bank_name','银行'),
		array('bank_user','户主'),
		array('bank_num','卡号'),
		array('create_time','添加时间'),
		array('status','状态'),
		array('check_time','审核时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/mnwithdrawtoexcel","业务经理提现导出excel",0);
		
		$xlsData=M('manager_withdraw')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_withdraw.manager_id')->join('bzb_user on bzb_user.manager_id=bzb_manager_withdraw.manager_id')->field('bzb_user.phone as phone,bzb_manager_withdraw.*')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['status']=getCheckstausdes2($v['status']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['check_time']=$v['check_time']>0?date('Y-m-d H:i',$v['check_time']):'未审核';
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
		
		
	}
	
	//执行业务经理提现
	public function domnwithdraw(){
		$w_id=I('w_id');
		$data=M('manager_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			
			//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"Caiwu/domnwithdraw","执行业务经理提现",$data['w_id']);
			
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
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		if($fromdate>0){
			$where.=" and bzb_company_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_company_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_company_withdraw.status=$status";
		}
		$withdraw_num=M('company_withdraw')->join('bzb_company on bzb_company.company_id=bzb_company_withdraw.company_id')->join('bzb_user on bzb_user.company_id=bzb_company_withdraw.company_id')->where($where)->count();
		$Page = new \Think\Page($withdraw_num,20);//导入分页类
		$show  = $Page->show();
		$withdraw_data=M('company_withdraw')->join('bzb_company on bzb_company.company_id=bzb_company_withdraw.company_id')->join('bzb_user on bzb_user.company_id=bzb_company_withdraw.company_id')->field('bzb_user.phone as phone,bzb_company_withdraw.*,bzb_company.company_name as company_name')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
		//dump($withdraw_data);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpwithdraw","商家提现",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->status=$status;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->withdraw=$withdraw_data;
		$this->display();
	}
	
	//商家提现导出excel
	public function cpwithdrawtoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$status=$_GET[status];

		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		
		if($fromdate>0){
			$where.=" and bzb_company_withdraw.create_time>$fromdate";
		}
		if($todate>0){
			$where.=" and bzb_company_withdraw.create_time<$todate";
		}
		if($status!=''){
			$where.=" and bzb_company_withdraw.status=$status";
		}
		$xlsName  = "商家提现";
		$xlsCell  = array(
		array('company_id','商家编号'),
		array('company_name','商家名称'),
		array('phone','用户手机'),
		array('amount','金额'),
		array('bank_name','银行'),
		array('bank_user','户主'),
		array('bank_num','卡号'),
		array('create_time','添加时间'),
		array('status','状态'),
		array('check_time','审核时间'),
		);
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpwithdrawtoexcel","商家提现导出excel",0);
		
		$xlsData=M('company_withdraw')->join('bzb_company on bzb_company.company_id=bzb_company_withdraw.company_id')->join('bzb_user on bzb_user.company_id=bzb_company_withdraw.company_id')->field('bzb_user.phone as phone,bzb_company_withdraw.*,bzb_company.company_name as company_name')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['status']=getCheckstausdes2($v['status']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['check_time']=$v['check_time']>0?date('Y-m-d H:i',$v['check_time']):'未审核';
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
		
	}
	
	//执行商家提现
	public function docpwithdraw(){
		$w_id=I('w_id');
		$data=M('company_withdraw')->find($w_id);
		if($data &&$data['status']==0){
			$data['status']=1;
			$data['check_time']=time();
			
			//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"Caiwu/docpwithdraw","执行商家提现",$data['w_id']);
			
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
		$charge_data=M('charge')->join('bzb_company on bzb_company.company_id=bzb_charge.company_id')->join('bzb_user on bzb_user.company_id=bzb_charge.company_id')->field('bzb_company.company_id as company_id,bzb_company.company_name as company_name,bzb_company.phone as phone,bzb_charge.id as id,bzb_charge.amount as amount,bzb_charge.real_amount as real_amount,bzb_charge.alipay_number as alipay_number,bzb_charge.remark as remark,bzb_charge.status as status,bzb_charge.check_time as check_time,bzb_charge.create_time as create_time')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_charge.create_time desc")->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/charge","商家充值",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->charge=$charge_data;
		$this->display();
	}
	
	//商家充值导出excel
	public function chargetoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$xlsName  = "商家充值";
		$xlsCell  = array(
		array('company_id','商家编号'),
		array('company_name','商家'),
		array('phone','用户手机'),
		array('amount','金额'),
		array('real_amount','实际金额'),
		array('alipay_number','支付宝帐号'),
		array('remark','备注'),
		array('create_time','时间'),
		array('status','状态'),
		array('check_time','审核时间'),
		);
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/chargetoexcel","商家充值导出excel",0);
		
		$xlsData=M('charge')->join('bzb_company on bzb_company.company_id=bzb_charge.company_id')->join('bzb_user on bzb_user.company_id=bzb_charge.company_id')->field('bzb_company.company_id as company_id,bzb_company.company_name as company_name,bzb_company.phone as phone,bzb_charge.amount as amount,bzb_charge.real_amount as real_amount,bzb_charge.alipay_number as alipay_number,bzb_charge.remark as remark,bzb_charge.status as status,bzb_charge.check_time as check_time,bzb_charge.create_time as create_time')->where($where)->order("bzb_charge.create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['status']=getCheckstausdes2($v['status']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['check_time']=$v['check_time']>0?date('Y-m-d H:i',$v['check_time']):'未审核';
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}
	
	//执行商家充值
	public function docharge(){
		$c_id=I('c_id');
		$real_amount=I('real_amount');
		$password=I('password');
		if($password!=C('TUIJIAN_PASS')){
			echo -1;exit;//密码错误
		}
		$data=M('charge')->find($c_id);
		//dump($data);die;
		if($data &&$data['status']==0){
			if($data[amount]<$real_amount){
				echo -5;exit;//审核金额不能大于充值金额
			}
			$data['status']=1;
			$data['check_time']=time();
			if(M('charge')->save($data)){
				//进行商家加钱和生成财务异动				
				if(cp_money_move($data['company_id'],$real_amount,'商家充值',$c_id)){
					//生成操作记录
					admin_log(session('admin_id'),session('admin_name'),"Caiwu/docharge","后台审核商家充值-".$real_amount,$c_id);
					echo 0;
				}
				else{
					$data['status']=0;
					$data['check_time']=0;
					M('charge')->save($data);
					echo -2;
					}
			}
			else
				echo -3;
		}
		else
			-4;
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
		$cscaiwuyidong_data=M('consumer_money_record')->join('bzb_user on bzb_user.consumer_id =bzb_consumer_money_record.consumer_id')->field("bzb_user.realname as realname,bzb_user.phone as phone,bzb_consumer_money_record.*")->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_consumer_money_record.create_time desc")->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cscaiwuyidong","消费者财务移动",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cscaiwuyidong=$cscaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//消费者财务移动导出excel
	public function cscaiwuyidongtoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$xlsName  = "消费者财务移动";
		$xlsCell  = array(
		array('consumer_id','消费者编号'),
		array('phone','手机'),
		array('realname','姓名'),
		array('money','金额'),
		array('type','备注说明'),
		array('t_id','对应订单ID'),
		array('create_time','时间'),
		);
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cscaiwuyidongtoexcel","消费者财务移动导出excel",0);
		
		$xlsData=M('consumer_money_record')->join('bzb_user on bzb_user.consumer_id =bzb_consumer_money_record.consumer_id')->where($where)->order("bzb_consumer_money_record.create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['money']=$v['is_jia']==1?"+".$v[money]:'-'.$v[money];
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
	}
	
	//商家财务移动
	public function cpcaiwuyidong(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			//$where="bzb_user.phone like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
			$cpid="";
			$rsus=M('user')->where("phone like '%$keyword%' and company_id>0")->select();
			foreach($rsus as $k=>$v){
				$cpid.=$v[company_id].",";
			}
			$rscp=M('company')->where("company_name like '%$keyword%'")->select();
			foreach($rscp as $k=>$v){
				$cpid.=$v[company_id].",";
			}
			$cpid.=$cpid."0";
			$where="company_id in($cpid)";
			
		}else
		{
			$where="1=1";		
		}
		$cpcaiwuyidong_num=M('company_money_record')->where($where)->count();
		$Page = new \Think\Page($cpcaiwuyidong_num,20);//导入分页类
		$show  = $Page->show();
		$cpcaiwuyidong_data=M('company_money_record')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpcaiwuyidong","商家财务移动",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cpcaiwuyidong=$cpcaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//商家财务移动导出excel
	public function cpcaiwuyidongtoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			//$where="bzb_user.phone like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
			$cpid="";
			$rsus=M('user')->where("phone like '%$keyword%' and company_id>0")->select();
			foreach($rsus as $k=>$v){
				$cpid.=$v[company_id].",";
			}
			$rscp=M('company')->where("company_name like '%$keyword%'")->select();
			foreach($rscp as $k=>$v){
				$cpid.=$v[company_id].",";
			}
			$cpid.=$cpid."0";
			$where="company_id in($cpid)";
		}else
		{
			$where="1=1";		
		}
				
		$xlsName  = "商家财务移动";
		$xlsCell  = array(
		array('company_id','商家编号'),
		array('company_name','商家名称'),
		array('phone','手机'),
		array('money','金额'),
		array('type','备注说明'),
		array('t_id','对应订单ID'),
		array('create_time','时间'),
		);
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpcaiwuyidongtoexcel","商家财务移动导出excel",0);
		
		
		$xlsData=M('company_money_record')->where($where)->order("create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['phone']=getcpuserphonereal($v['company_id']);
			$xlsData[$k]['company_name']=getcpname($v['company_id']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['money']=$v['is_jia']==1?"+".$v[money]:'-'.$v[money];
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		$mncaiwuyidong_data=M('manager_money_record')->join('bzb_user on bzb_user.manager_id =bzb_manager_money_record.manager_id')->field("bzb_user.realname as realname,bzb_user.phone as phone,bzb_manager_money_record.*")->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_manager_money_record.create_time desc")->select();
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/mncaiwuyidong","业务经理财务移动",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->mncaiwuyidong=$mncaiwuyidong_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//业务经理财务移动导出excel
	public function mncaiwuyidongtoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$xlsName  = "业务经理财务移动";
		$xlsCell  = array(
		array('manager_id','业务编号'),
		array('phone','手机'),
		array('realname','姓名'),
		array('money','金额'),
		array('remark','备注说明'),
		array('order_id','对应订单ID'),
		array('create_time','时间'),
		);
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/mncaiwuyidongtoexcel","业务经理财务移动导出excel",0);
		
		$xlsData=M('manager_money_record')->join('bzb_user on bzb_user.manager_id =bzb_manager_money_record.manager_id')->field("bzb_user.realname as realname,bzb_user.phone as phone,bzb_manager_money_record.*")->where($where)->order("bzb_manager_money_record.create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['money']=$v['is_jia']==1?"+".$v[money]:'-'.$v[money];
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/csyue","消费者余额列表",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->csyue=$csyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//消费者余额列表导出excel
	public function csyuetoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/csyuetoexcel","消费者余额列表导出excel",0);
		
		$xlsName  = "消费者余额列表";
		$xlsCell  = array(
		array('consumer_id','消费者编号'),
		array('phone','手机'),
		array('realname','姓名'),
		array('money','金额'),
		);

		$xlsData=M('consumer')->join('bzb_user on bzb_user.consumer_id =bzb_consumer.consumer_id')->where($where)->select();
		exportExcel($xlsName,$xlsCell,$xlsData);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/csyuetoexcel","消费者余额列表导出excel",0);
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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpyue","企业余额列表",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cpyue=$cpyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//企业余额列表导出excel
	public function cpyuetoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%' or bzb_company.company_name like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
				
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/cpyuetoexcel","企业余额列表导出excel",0);
		
		$xlsName  = "消费者余额列表";
		$xlsCell  = array(
		array('company_id','企业编号'),
		array('phone','手机'),
		array('realname','姓名'),
		array('company_name','企业名称'),
		array('money','金额'),
		);

		$xlsData=M('company')->join('bzb_user on bzb_user.company_id =bzb_company.company_id')->where($where)->select();
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/mnyue","业务经理余额列表",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->mnyue=$mnyue_data;
		$this->display();
		//dump($cscaiwuyidong_data);
	}
	
	//业务经理余额列表导出excel
	public function mnyuetoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.phone like '%$keyword%' or bzb_user.realname like '%$keyword%'";
		}else
		{
			$where="1=1";		
		}
		$xlsName  = "业务经理余额列表";
		$xlsCell  = array(
		array('manager_id','业务经理编号'),
		array('phone','手机'),
		array('realname','姓名'),
		array('money','金额'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/mnyuetoexcel","业务经理余额列表导出excel",0);
		
		$xlsData=M('manager')->join('bzb_user on bzb_user.manager_id =bzb_manager.manager_id')->where($where)->select();
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/founder","慈善基金",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//慈善基金导出excel
	public function foundertoexcel(){
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
		$xlsName  = "慈善基金";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('founder_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/foundertoexcel","慈善基金导出excel",0);
		
		$xlsData=M('founder')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/requeue","重复购券列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//重复购券导出excel
	public function requeuetoexcel(){
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
		$xlsName  = "重复购券";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('requeue_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/requeuetoexcel","重复购券导出excel",0);
		
		$xlsData=M('requeue_fee')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/profit","平台利润列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//平台利润导出excel
	public function profittoexcel(){
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
		$xlsName  = "平台利润";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('profit_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/profittoexcel","平台利润导出excel",0);
		
		$xlsData=M('money_record')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/tax","营业税列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//营业税导出excel
	public function taxtoexcel(){
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
		$xlsName  = "营业税";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('tax_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/taxtoexcel","营业税导出excel",0);
		
		$xlsData=M('tax')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/weihu","平台维护列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//平台维护导出excel
	public function weihutoexcel(){
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
		$xlsName  = "平台维护";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('weihu_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/weihutoexcel","平台维护导出excel",0);
		
		$xlsData=M('weihu_fee')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/fgs","分公司津贴列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//分公司津贴导出excel
	public function fgsexcel(){
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
		$xlsName  = "分公司津贴";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('fengongshi_id','关联分公司'),
		array('province_id','分公司地区'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('fengongshi_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/fgsexcel","分公司津贴导出excel",0);
		
		$xlsData=M('fengongshi_money')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
			$xlsData[$k]['fengongshi_id']=getfengongshiname($v['fengongshi_id']);
			$xlsData[$k]['province_id']=getprovincename($v['province_id']).$xlsData[$k]['city_id']=getcityname($v['city_id']).$xlsData[$k]['area_id']=getareaname($v['area_id']);
				 
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

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
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/sms","信息费列表",0);
		
		$this->page=$show;
		$this->money=$data_money;
		$this->data=$data;
		$this->display();
	}
	
	//信息费导出excel
	public function smstoexcel(){
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
		$xlsName  = "信息费";
		$xlsCell  = array(
		array('id','编号'),
		array('company_id','关联企业'),
		array('order_id','关联订单号'),
		array('money','金额'),
		array('weihu_rate','百分比'),
		array('remark','备注'),
		array('create_time','生成时间'),
		);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Caiwu/smstoexcel","信息费导出excel",0);
		
		$xlsData=M('sms_fee')->where($where)->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['company_id']=getcpname($v['company_id']);
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}
	public function changetuijianmoney(){
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"User/changetuijianmoney","修改会员推荐收入",0);
		$this->display();
	}
	public function dochangetuijianmoney(){
		$tjtype=$_GET[tjtype];
		$phone=$_GET[phone];
		$orderid=$_GET[orderid];
		$password=$_GET[password];
		//dump($_GET);exit;
		//echo $tjtype;exit;
		if($password!=C('TUIJIAN_PASS')){
			echo -1;exit;//密码错误
		}
		$rs1=M('user')->where("phone='$phone'")->find();
		if(!$rs1){
			echo -2;exit;//接收的用户不存在
		}
		if(getlevelbyuid($rs1[user_id])==0&&$tjtype==2){
			echo -4;exit;//接收的用户不能推荐企业
		}
		$rs2=M('manager_money_record')->where("manager_id=300001 and type=$tjtype and order_id=$orderid and is_jia=1")->find();
		if(!$rs2){
			echo -3;exit;
		}
		
		//修改消费者推荐关系
		if($tjtype==1){
			$recid=$rs2[id];
			$mnid=$rs1[manager_id];
			$money=$rs2[money];
			$data[tjren]=$tjuid;
			$rs3=M("manager")->where("manager_id=300001")->setDec('money',$money);//300001减去钱
			$rs31=M("manager")->where("manager_id=$mnid")->setInc('money',$money);//接收会员加上钱
			
			//业务记录收为接收会员的
			$rs4=M("manager_money_record")->where("id=$recid")->save(array('manager_id'=>$mnid));
			//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"User/dochangetuijianmoney","修改推荐消费者收益:从18888888888划".$money."到".$phone,0);
		
			echo 0;
		}
		elseif($tjtype==2){
			$recid=$rs2[id];
			$mnid=$rs1[manager_id];
			$money=$rs2[money];
			$data[tjren]=$tjuid;
			$rs3=M("manager")->where("manager_id=300001")->setDec('money',$money);//300001减去钱
			$rs31=M("manager")->where("manager_id=$mnid")->setInc('money',$money);//接收会员加上钱
			
			//业务记录收为接收会员的
			$rs4=M("manager_money_record")->where("id=$recid")->save(array('manager_id'=>$mnid));
			//写入操作日志
			admin_log(session('admin_id'),session('admin_name'),"User/dochangetuijianmoney","修改推荐企业收益:从18888888888划".$money."到".$phone,0);
		
			
			echo 0;
		}
		else{
			echo -5;
		}
	}
}