<?php
namespace Admin\Controller;
use Think\Controller;
class SysController extends BaseController {
    public function index(){
		
    }
	
	public function config(){
		$data=M('config')->select();
		//dump($data);die;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/config","业务参数设置",0);
		
		$this->webconfig=$data;
		$this->display();
	}
	
	public function saveconf(){
		if(M('config')->where('id='.I('c-id'))->setField('value',I('c-value')))
			echo 'success';
		else
			echo 'error';
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/saveconf","业务参数修改",0);
	}

	public function banks(){
		$data=M('bankname')->select();
		//dump($data);die;
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/banks","银行管理列表",0);
		
		$this->bankname=$data;
		$this->display();
	}
	
	public function savebanks(){
		$data[bankname]=I('bankname');
		$data[sortid]=I('sortid');
		$data[modify_time]=time();
		if(M('bankname')->where('id='.I('c-id'))->save($data))
			echo 'success';
		else
			echo 'error';
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/savebanks","修改银行列表",0);
	}

	public function addbanks(){
		$data[bankname]=I('bankname');
		$data[sortid]=I('sortid');
		$data[create_time]=time();
		if(M('bankname')->add($data))
			echo 'success';
		else
			echo 'error';
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/addbanks","添加银行列表",0);
	}
	
	//操作日志
	public function adminlogs(){
		$keyword=$_GET[keyword];
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		$where='';
		if($keyword!=''){
			$where="admin_name like '%$keyword%' or doing_things like '%$keyword%'";
		}
		if($_GET[fromdate]||$_GET[todate]){
			if($fromdate>0){
				$where.="and doing_time>$fromdate";
			}
			if($todate>0){
				$where.=" and doing_time<$todate+24*60*60";
			}
		}
		
		//$data_money=M('founder')->where($where)->sum('money');
		$data_num=M('admin_log')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$logslist=M('admin_log')->where($where)->order('doing_time desc')->limit($Page->firstRow,$Page->listRows)->select();
		//dump($logslist);
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/adminlogs","操作日志",0);
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->logslist=$logslist;
		$this->display(operation_log);
		
	}
	
	//操作日志导出Excel
	public function adminlogstoexcel(){
		$keyword=$_GET[keyword];
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		$where='';
		if($keyword!=''){
			$where="admin_name like '%$keyword%' or doing_things like '%$keyword%'";
		}
		if($_GET[fromdate]||$_GET[todate]){
			if($fromdate>0){
				$where.="and doing_time>$fromdate";
			}
			if($todate>0){
				$where.=" and doing_time<$todate+24*60*60";
			}
		}
		$xlsName  = "操作日志";
		$xlsCell  = array(
		array('id','日志编号'),
		array('admin_id','管理员ID'),
		array('admin_name','管理员名称'),
		array('doing_model','操作的模块'),
		array('doing_things','操作的内容'),
		array('relation_id','关联模块数据ID'),
		array('doing_ip','操作IP'),
		array('doing_time','操作的时间'),
		);
		$xlsData=M('admin_log')->where($where)->order('doing_time desc')->select();
		//dump($logslist);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/adminlogs","操作日志导出Excel",0);
		
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['doing_time']=$v['doing_time']>0?date('Y-m-d H:i',$v['doing_time']):'无';
			$xlsData[$k]['admin_id']=getcpname($v['admin_id']);
			
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
	}
	
	//分公司操作日志
	public function fgslogs(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($_GET[fromdate]||$_GET[todate]){
			if($fromdate>0){
				$where="doing_time>$fromdate";
			}
			if($todate>0){
				$where.=" and doing_time<$todate+24*60*60";
			}
		}
		else
		{
			$where="1=1";		
		}
		//$data_money=M('founder')->where($where)->sum('money');
		$data_num=M('fgs_log')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$logslist=M('fgs_log')->where($where)->order('doing_time desc')->limit($Page->firstRow,$Page->listRows)->select();
		//dump($logslist);
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/adminlogs","分公司操作日志",0);
		
		$this->page=$show;
		//$this->money=$data_money;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->logslist=$logslist;
		$this->display(fgs_operation_log);
		
	}
	
	//分公司操作日志导出Excel
	public function fgslogstoexcel(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($_GET[fromdate]||$_GET[todate]){
			if($fromdate>0){
				$where="doing_time>$fromdate";
			}
			if($todate>0){
				$where.=" and doing_time<$todate+24*60*60";
			}
		}
		else
		{
			$where="1=1";		
		}
		$xlsName  = "分公司操作日志";
		$xlsCell  = array(
		array('id','日志编号'),
		array('fgs_id','分公司ID'),
		array('fgs_name','分公司名称'),
		array('doing_model','操作的模块'),
		array('doing_things','操作的内容'),
		array('relation_id','关联模块数据ID'),
		array('doing_ip','操作IP'),
		array('doing_time','操作的时间'),
		);
		$xlsData=M('fgs_log')->where($where)->order('doing_time desc')->select();
		//dump($logslist);
		
		//写入操作日志
		admin_log(session('admin_id'),session('admin_name'),"Sys/adminlogs","分公司操作日志导出Excel",0);
		
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['doing_time']=$v['doing_time']>0?date('Y-m-d H:i',$v['doing_time']):'无';
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
	}
	
	//队列调整
	public function quickqueue(){
		$province=M("province")->select();
		$this->province=$province;
		//echo floor(10/100);
		$this->display();
	}
	
	//队列调整执行
	public function doquickqueue(){
		
		//规则：没出过的报账券，
		$dotype=$_GET[dotype];
		$fromqueue=$_GET[fromqueue];//开始位置
		$toqueue=$_GET[toqueue];//结束位置
		$canin=$_GET[canin]?$_GET[canin]:1;//每个用户允许的张数
		$caninpercent=$_GET[caninpercent];//占报账券数的百分比
		$caninday=$_GET[caninday];//多少天没有排出券
		$province_id=$_GET[province_id];//省份id
		$city_id=$_GET[city_id];//城市id
		$area_id=$_GET[area_id];//地区id
		$fromdate=$_GET[fromdate];//生成开始时间
		$todate=$_GET[todate];//生成结束时间
		$allnum=$_GET[allnum];//调整总数
		//操作参数
		$submit_param="成员从:".$fromqueue."-到:".$toqueue."-每个用户允许的张数:".$canin."-占报账券数的百分比:".$caninpercent."-多少天没有排出券:".$caninday."-省:".$province_id."-市:".$city_id."-区:".$area_id."-生成时间从:".$fromdate."-到:".$todate."-调整总数".$allnum;
		$password=$_GET[password];//调整密码

		if($password!=C('QUEUE_PASS')){
			echo -1;exit;//密码错误
		}
		$where='status=0 and loop_times=0 and is_raal=1';//没有排出过的，没有循环过的,是真实的报账券
		
		//开始位置
		if($fromqueue!=''){
			$where.=" and queue_position>=$fromqueue ";
		}
		//结束位置
		if($toqueue!=''){
			$where.=" and queue_position<=$toqueue ";
		}
		
		//开始时间
		$whereproof1='';
		$whereproof2='';//加速过的
		if($fromdate!=''){
			$fromdate=strtotime($fromdate);
			$where.=" and in_time>=$fromdate ";
			
			//排入时间大于开始时间，针对未排出并未调整过的
			$whereproof2.= " and in_time>=$fromdate ";//加速过的
		}
		
		//结束时间
		if($todate!=''){
			$todate=strtotime($todate);
			$where.=" and in_time<=$todate ";
			$whereproof2.= " and in_time<=$todate";
		}
		if($caninday!=''){
			$oktime=time()-$caninday*24*60*60;
			//排入时间大于间隔时间
			$whereproof1.= " and out_time>$oktime ";//未加速过的
			$whereproof2.= " and out_time>$oktime";//加速过的
		}
		

		$queues=M("queue")->where($where)->select();
		if(!$queues){
			echo -2;exit;
		}
		//p($queues);
		$donum=0;//起始操作的数量
		//echo "aaa<br>";
		$pids='';
		$notinproof_ids=1;
		foreach($queues as $k=>$v){
			//echo $notinproof_ids."</br>";
			$proofs=M("proof")->field('consumer_id')->where("id=$v[proof_id] ")->find();
			
			//同一消费者是否存在已经排出过的报账券(在指定天数未排出之内)
			$okproofnum=M("proof")->where("consumer_id=$proofs[consumer_id] and status>0 ".$whereproof1)->count();
			//echo "排出过的券:".$okproofnum."---".$proofs[consumer_id]."------<br>";
			//$pids.=	$notinproof_ids."===\n";
			//同一消费者是否存在已经排出过的报账券(加速过的),
			$okproofnum2=M("proof")->where("consumer_id=$proofs[consumer_id] and status>0 and is_quick_queue=1".$whereproof2)->count();
			
			//echo "加速过的券:".$okproofnum2."<br>";
			
			if($donum==0){
				$myokproofnum=$okproofnum;
				
			}
			
			//可排进的数量
			if($caninpercent!=''){
				//同一消费者未排出报账券数
				$okproofnum3=M("proof")->where("consumer_id=$proofs[consumer_id] and id not in($notinproof_ids) and status=0 and is_quick_queue=0".$whereproof2)->count();
				$canin=floor($okproofnum3*($caninpercent/100));//取整
				if($canin==0)
					$canin=1;//只取第一次，确定可调整多少张(减去第一次算同加速过的)
			}
				
			//同一消费者排出了多少张，
			$outcsnum=0;
			$outcs=M("proof")->field('consumer_id')->where("id!=1 and id in($notinproof_ids)")->select();
			if($outcs){
				foreach($outcs as $k2=>$v2){
					//如果存在已排出csid,可排进减一
					if($v2[consumer_id]==$proofs[consumer_id]){
						$outcsnum++;
					}
				}
			}
			$canin=$canin-$outcsnum;

			
			
			//对应报账券关联的订单所在的区域
			//$pto=M("proof2order")->join("bzb_orders on bzb_orders.id=bzb_proof2order.order_id")->where("bzb_proof2order.proof_id=$v[proof_id]")->select();
			//p($pto);
			//如果小于一个用户允许进入的张数，并在指定区域内，进行排出
			//echo "bbb--";	
			//进行执行
			if($dotype==1){
				if($myokproofnum==0&&$okproofnum2<$canin&&$donum<$allnum){
					$notinproof_ids.=','.$v[proof_id];
					//echo "符合条件的券：".$v[proof_id]."==".$donum."<br>";
					//队列成员进行循环加一
					$rs=M("queue")->where("id=$v[id]")->setInc('loop_times');
					//报账券进行状态加一
					$rs2=M("proof")->where("id=$v[proof_id]")->setInc('status');
					$proof_data['out_time2']=time();//最新一次循环排出时间
					$rs2=M("proof")->where("id=$v[proof_id]")->save($proof_data);
					
					//进行加钱
					$queue_data['proof_id']=$v[proof_id];
					
					proofOutput($queue_data);//进行排出
					$pids.=getusernamefromcsid($proofs[consumer_id])."-".$proofs[consumer_id]."-".$v[proof_id]."-".$v[id]."|";
					$donum++;

				}
			}
			//进行输出
			if($dotype==2){
				if($myokproofnum==0&&$okproofnum2<$canin&&$donum<$allnum){
					//echo "符合条件的券：".$v[proof_id]."==".$donum."<br>";
					//队列成员进行循环加一
					//$rs=M("queue")->where("id=$v[id]")->setInc('loop_times');
					//报账券进行状态加一
					//$rs2=M("proof")->where("id=$v[proof_id]")->setInc('status');
					
					//进行加钱
					//$queue_data['proof_id']=$v[proof_id];
					$notinproof_ids.=','.$v[proof_id];
					//proofOutput($queue_data);
					$pids.=$canin."-".getusernamefromcsid($proofs[consumer_id])."-".$proofs[consumer_id]."-报账券id:".$v[proof_id]."-队列id:".$v[id]."|\n";
					$donum++;//排出数递增
					
				}
			}
			
			
			
			
		}
		if($dotype==1){
			echo $donum;
			//写入操作日志
			editqueue_log(session('admin_id'),session('admin_name'),$donum,$pids,$submit_param);
		}
		if($dotype==2){
			echo "一共".$donum."张：\n".$pids;
			//写入操作日志
			//editqueue_log(session('admin_id'),session('admin_name'),$donum,$pids,$submit_param);
		}
		
	}
	
	//队列调整二
	public function quickqueue2(){
		//echo "aaa<br>";
		
		$this->display();
	}
	
	//队列调整执行二
	public function doquickqueue2(){
		$queuenums=$_GET[queuenums];//成员数
		$dotype=$_GET[dotype];
		//操作参数
		$submit_param="加入的成员数:".$queuenums;
		$password=$_GET[password];//调整密码

		if($password!=C('QUEUE_PASS')){
			echo -1;exit;//密码错误
		}
		if($dotype==1){
			//最早一张
			//进行排队操作,队列成员排入
			$rs=addQueue($queuenums);
			echo $rs[ok];
			//写入操作日志
			$pids=$rs[outpids];
			editqueue_log(session('admin_id'),session('admin_name'),$queuenums,$pids,$submit_param);
		}
		if($dotype==2){
			$firstQueue=M('queue')->where('status<5 and is_real=1')->order('queue_position')->find();
			$oknum=$firstQueue[status];
			$canout=floor(($queuenums+$oknum)/4);//取整
			echo "将会排出".$canout."张：\n从".$firstQueue[id]."号开始";
		}
		
	}
	
	//操作日志
	public function editqueuelogs(){
		$fromdate=strtotime($_GET[fromdate]);
		$todate=strtotime($_GET[todate]);
		if($_GET[fromdate]||$_GET[todate]){
			if($fromdate>0){
				$where="doing_time>$fromdate";
			}
			if($todate>0){
				$where.=" and doing_time<$todate+24*60*60";
			}
		}
		else
		{
			$where="1=1";		
		}
		//$data_money=M('founder')->where($where)->sum('money');
		$data_num=M('editqueue_log')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$logslist=M('editqueue_log')->where($where)->order('doing_time desc')->limit($Page->firstRow,$Page->listRows)->select();
		//dump($logslist);
		
		$this->page=$show;
		//$this->money=$data_money;
		$this->fromdate=$fromdate;
		$this->todate=$todate;
		$this->logslist=$logslist;
		$this->display(editqueue_log);
		
	}
	
	function checkproof(){
		$token=$_GET[token];
		$okproof='';
		if($token=="zhigu22304001768"){
			$proof=M("proof")->select();
			foreach($proof as $k=>$v){
				$queue=M("queue")->where("proof_id=$v[id] and loop_times>0")->order("id desc")->find();
				if($queue){
					$myproof[status]=$queue[loop_times];
					$rs=M("proof")->where("id=$v[id]")->save($myproof);
					$okproof.=$v[id].",";
				}
			}
			echo $okproof;
		}else{
			echo "非法操作";
		}
		
	}
	
	function checkuser(){
		$token=$_GET[token];
		$okcompany='';
		if($token=="zhigu22304001768"){
			$user=M("user")->select();
			foreach($user as $k=>$v){
				//检查是否手机或用户我重复
				echo $v[user_id]."<br>";
				$user2=M("user")->where("(user_name='$v[user_name]' or phone='$v[phone]') and user_id!=$v[user_id] and phone!=''")->select();
				if($user2){
					foreach($user2 as $k2=>$v2){
						echo $v2[user_id]."---".$v2[user_name]."---".$v2[phone]."<br>";
						$rs=M("")->delete();
					}
				}
				//echo "-------------------------------<br>";
				//
				if($v[company_id]>0){
					$com=M("company")->where("company_id=$v[company_id]")->count();
					if($com==0){
						echo $v[user_id]."---".$v[user_name]."===".$v[user_name]."<br>";
						$mycom[company_id]=0;
						$rs2=M("user")->where("user_id=$v[user_id]")->save($mycom);
					}
				}
				
			}
			//echo $okproof;
		}else{
			echo "非法操作";
		}
		
	}
	
	
}