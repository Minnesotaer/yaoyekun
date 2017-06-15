<?php

//记录后台日志
/*
*param $admin_id:管理员id;
*param $admin_name:管理员用户名;
*param $doing_model:操作的模块与方法;
*param $doing_things:操作内容;
*param $relation_id:操作数据关联的id;
*/
function admin_log($admin_id,$admin_name,$doing_model,$doing_things,$relation_id=0){
	$data[admin_id]=$admin_id;
	$data[admin_name]=$admin_name;
	$data[doing_model]=$doing_model;
	$data[doing_things]=$doing_things;
	$data[relation_id]=$relation_id;
	$data[doing_time]=time();
	$data[doing_ip]=get_client_ip();
	
	$rs=M('admin_log')->add($data);
}

//自定义 p();
//function p($arr){
//	echo '<pre>'.print_r($arr, true).'</pre>';
//}
//银行卡每四位一个空格
function rebank($str){
	$arr=str_split($str,4);//4的意思就是每4个为一组
	$str=implode('&nbsp;&nbsp;',$arr);
	return $str;
}
//递归重组节点信息为多维数组
function node_merge($node, $access = null, $pid = 0){
	$arr = array();
	foreach($node as $v){
		if(is_array($access)){
			$v['access'] = in_array($v['id'], $access) ? 1 : 0;
		}
		if($v['pid'] == $pid){
			$v['child'] = node_merge($node, $access, $v['id']);
			$arr[] =$v;
		}
	}
	return $arr;
}

//报账券状态
function getProofStatusDes($status){
	$arr=array(
	-1=>'等待排队',
	0=>'已进入排队',
	1=>'完成1/10',
	2=>'完成2/10',
	3=>'完成3/10',
	4=>'完成4/10',
	5=>'完成5/10',
	6=>'完成6/10',
	7=>'完成7/10',
	8=>'完成8/10',
	9=>'排队完成'
	);
	return $arr[$status];
}

//推荐消费者数量
function gettjcsnum($id){
	$num=M('consumer')->where("tjren=$id")->count();
	return $num;
}
//推荐企业数量
function gettjcpnum($id){
	$num=M('company')->where("tjren=$id")->count();
	return $num;
}
//用户id获取用户手机号
function getuserphone($id){
	$rs=M('user')->where("user_id=$id")->find();
	if($rs[realname]=='')
		$rs[realname]="~~";
	return $rs[realname].":".$rs[phone];
}
//获取注册来源
function getregtype($id){
	$arr=array(
		1=>'网站注册',
		2=>'手机注册',
		3=>'APP注册',
		4=>'优莱汇购注册',
		5=>'微信注册'
		);
	return $arr[$id];
}

//由分公司id获取分公司名
function getfengongshiname($fgsid){
	$rs=M("fengongshi")->where("id=$fgsid")->find();
	return $rs[fgsname];
}
//获取分类名
function getcmstypename($id){
	$rs=M("yk_cms_type")->where("ct_id=$id")->find();
	return $rs[ct_name];
}

//获取订单审核状态
function getOrderischeckDes($is_check){
	$arr=array(0=>'未审核',
	1=>'已审核',
	2=>'已拒绝'
	);
	return $arr[$is_check];
}
//由报账券id获取报账券号
function getProofnoFromId($id){
	$rs=M('proof')->where("id=$id")->find();
	return $rs[proof_no];
}
//由报账券id获取用户手机
function getUserphoneFromId($id){
	$rs=M('proof')->where("id=$id")->find();
	$phone=getcsuserphonereal($rs[consumer_id]);
	return $phone;
}
//由报账券id获取用户真名
function getUserRealnameFromId($id){
	$rs=M('proof')->where("id=$id")->find();
	$rs2=M("user")->where("consumer_id=$rs[consumer_id]")->find();
	return $rs2[realname];
}

//由取得剩余可以排出次数
function getQueueOkStatus($status){
	$okstatus=5-$status;
	$arr=array(
	0=>'等待有效队列成员',
	1=>'差'.$okstatus.'个队列成员',
	2=>'差'.$okstatus.'个队列成员',
	3=>'差'.$okstatus.'个队列成员',
	4=>'差'.$okstatus.'个队列成员',
	5=>'此次已排出',
	);
	return $arr[$status];
}

function getWithdrawDes($is_check){
	$arr=array(0=>'等待转账',
	1=>'已转出',
	2=>'已到账'
	);
	return $arr[$is_check];
}
function getOrderischeckClass($is_check){
	$arr=array(0=>'btn btn-info btn-xs',
	1=>'btn btn-success btn-xs',
	2=>'btn btn-danger btn-xs'
	);
	return $arr[$is_check];
}
function getCheckstausdes($status){
	$arr=array(
	0=>'<button class="btn btn-info btn-xs">未审核</button>',
	1=>'<button class="btn btn-success btn-xs">已审核</button>',
	2=>'<button class="btn btn-danger btn-xs">已拒绝</button>'
	);
	return $arr[$status];
}
function getCheckstausdes2($status){
	$arr=array(
	0=>'未审核',
	1=>'已审核',
	2=>'已拒绝'
	);
	return $arr[$status];
}
	
function getStatusReason($status_code){
	$status_reason='';
	if($status_code>=0)
		return 'success';
	switch($status_code){
		case -1:
		$status_reason='data not found';break;
		case -99:
		$status_reason='余额不足';break;
		case -400:
		$status_reason='未知错误';break;
		default:
		$status_reason='error';
	}
	return $status_reason;
}
//获取消费者积分
function getJifenByConsumerid($consumer_id){
	$where['consumer_id']=$consumer_id;
	$where['status']=-2;
	$proof_data=M('proof')->where($where)->find();
	if($proof_data)
		return $proof_data['money'];
	return 0;
}
	
//获取消费者报账券数
function getBZBcount($consumer_id=0){
	if($consumer_id !=0)
		$where['consumer_id']=$consumer_id;
	$where['status']=array('gt',-2);
	return M('proof')->where($where)->count();
}

	
//admin模块新增
//消费者数量
function getCScount(){
	return M('consumer')->count();
}

//企业总额
function getCPcount(){
	return M('company')->count();
}

//消费总额
function getMoneycount(){
	return M('orders')->where('is_check=1')->sum('money');
}

//后台商家审核充值
function cp_money_move($company_id,$money,$type,$t_id){
		
		if($type=='商家充值'){
			$money_record['company_id']=$company_id;
			$money_record['money']=$money;
			$money_record['type']=$type;
			$money_record['t_id']=$t_id;
			$money_record['is_jia']=1;
			$money_record['create_time']=time();
			if(M('company_money_record')->add($money_record)){
				M('company')->where('company_id='.$company_id)->setInc('money',$money);
				return true;
			}
		}
		else if($type=='商家提现'){
			$money_record['company_id']=$company_id;
			$money_record['money']=$money;
			$money_record['type']=$type;
			$money_record['t_id']=$t_id;
			$money_record['is_jia']=2;
			$money_record['create_time']=time();
			if(M('company_money_record')->add($money_record)){
				M('company')->where('company_id='.$company_id)->setDec('money',$money);
				return true;
			}
		}
		return false;
	}
	
	
	//进行排入操作,激励$nums为队列成员数,4个出一张
function addQueue($nums){
	$outpids='';
	$i=0;
	$ok=0;
	while($i<$nums){
		
		$queue=M('queue');
		
		$add_data['proof_id']=3014;//为18888888888的一个proof_id
		$add_data['loop_times']=0;//循环次数为0
		$add_data['status']=0;//处于五出一的零位
		$add_data['is_real']=0;//不是真实的队列成员
		$add_data['in_time']=time();//进入队列时间
		//$add_data['queue_no']=date("YmdH", time()).''.$proofID;
		$maxPosition=$queue->max('queue_position');//队列的末尾数
		$add_data['queue_position']=$maxPosition+1;//插入队列所处的位置
		if($queue->add($add_data))
		{
			//对应凭证排进队列，保存排入时间
			//M('proof')->where('id='.$proofID)->setField('in_time',time());
			//第一个未排出队列成员小于五的,即是未排出的,并且是真实的队列成员
			$firstQueue=$queue->where('status<5 and is_real=1')->order('queue_position')->find();
			//进行加1
			$firstQueue['status'] +=1;
			$queue->save($firstQueue);
			//dump($firstQueue);die;
			//如果加一后为五，进行排出，
			if($firstQueue['status']==5){
				//保存对应成员的排出时间
				$queue->where("id=$firstQueue[id]")->save(array('out_time'=>time()));
				//proofOutput($firstQueue['proof_id'],$firstQueue['loop_times']+1);//排出操作
				proofOutput($firstQueue);//排出操作
				
				//如果这个队列成员循环次数不足9次
				if($firstQueue['loop_times']<9){
					//生成一条新的凭证对应得队列成员
					$loopQueue['proof_id']=$firstQueue['proof_id'];
					//循环加一
					$loopQueue['loop_times']=$firstQueue['loop_times']+1;
					//放在加入队列的成员后面
					$loopQueue['queue_position']=$maxPosition+2;
					//处于五出一的零位
					$loopQueue['status']=0;
					$loopQueue['in_time']=time();//排入时间
					//$loopQueue['queue_no']=$firstQueue['queue_no'];
					//dump($loopQueue);
					//dump($firstQueue);die;
					$queue->add($loopQueue);
					
					//最新的不足五次的队列成员，进行加一
					$firstQueue2=$queue->where('status<5 and is_real=1')->find();
					$firstQueue2['status'] +=1;
					$queue->save($firstQueue2);
				}
				else{
					//排队完成,保存报账券的排出时间
					M('proof')->where('id='.$firstQueue[proof_id])->setField('out_time',time());
				}
				$result[outpids].=$firstQueue[proof_id].",";
			}
			$result[status].='1,';//添加排队完成
			$ok++;
		}
		else
			$result[status].='-2,';//添加排队失败
		
		$i++;
	}
	$result[ok]=$ok;
	return $result;
}
	
//排出报账后操作
function proofOutput($queue_data){
		$proof_data=M('proof')->find($queue_data['proof_id']);
		$csmoney=10;//排出一张消费者得的钱，也就是一张报出的钱
		cs_add_money($proof_data['consumer_id'],$csmoney,$queue_data['proof_id']);//消费者添加钱
		
		$cp_id=getcpid_from_proofid($proof_data['id']);//企业id
		$cpmoney=1;//排出一张企业得的钱，也就是解冻的钱
		cp_add_money($cp_id,$cpmoney,$queue_data['proof_id']);//企业解冻钱
		
		//$proof_data['status']=$queue_data['status']=1;//修改报账券状态为完成
		$proof_data['status']=$queue_data['loop_times']+1;//修改报账券状态,为1~9
		
		M('proof')->save($proof_data);
}

//添加消费者账户金额
function cs_add_money($consumer_id,$money,$t_id){
		$cs_money_record['consumer_id']=$consumer_id;
		$cs_money_record['money']=$money;
		$cs_money_record['type']='排队报账完成';
		$cs_money_record['t_id']=$t_id;
		$cs_money_record['is_jia']=1;
		$cs_money_record['create_time']=time();
		M('consumer_money_record')->add($cs_money_record);//添加金额变动记录
		$consumer=M('consumer')->find($consumer_id);
		$consumer['money'] +=$money;
		M('consumer')->save($consumer);//添加用户金额
		
		//发送站内信息
		$proof_no=getproof_no_from_proofid($t_id);
		$csmoney=$money;
		$csuid=getuserid($consumer_id,'cs');
		$csmsg="你好,你对应ID为".$t_id."的报账券于".date('Y-m-d H:i:s')."排队报账完成,报出".$csmoney."元.";
		addMessage($csuid,1,$csmsg);//用户通知
		
		//发手机短信
		//报账劵兑现//ID:69464
		//【报账宝】尊敬的{1}您好，您于{2}持有编号为{3}的报账劵成功兑现{4}元，账户余额{5}元。报账宝-您的每次消费都是财富！
		//发送短信
		$csname=urlencode(getcsname($consumer_id));//消费者姓名
		$csphone=getcsuserphonereal($consumer_id);
		//$csphone="13977372957";
		$localtime=urlencode(date('Y-m-d H:i',time()));//报出时间
		$proofno=urlencode($proof_no);
		$okcsmoney=urlencode($csmoney);
		$nowmoney=get_cs_money($consumer_id);
		$url="http://www.bzb898.com/sendsms/SendTemplateSMS.php?token=bzb8982616ilum&typeid=69464&phone=".$csphone."&value_a=".$csname."&value_b=".$localtime."&value_c=".$proofno."&value_d=".$okcsmoney."&value_e=".$nowmoney;
		file_get_contents($url);//进行发送

		
}

//排出报账后返还商家金额
function cp_add_money($company_id,$money,$t_id){
	if($money>0){
		$cp_money_record['company_id']=$company_id;
		$cp_money_record['money']=$money;
		$cp_money_record['type']='排队报账完成信用奖励';
		$cp_money_record['t_id']=$t_id;
		$cp_money_record['is_jia']=1;
		$cp_money_record['create_time']=time();
		M('company_money_record')->add($cp_money_record);//添加金额变动记录
		//发送站内信息
		$jdmoney=$money;
		$cpuid=getuserid($company_id,'cp');
		$cpmsg="你好,你对应的报账券于".date('Y-m-d H:i:s')."排队报账完成,获得信用奖励".$jdmoney."元.";
		addMessage($cpuid,1,$cpmsg);//用户通知
		
		/*
		$cp_money_record2['company_id']=$company_id;
		$cp_money_record2['money']=$money/2;
		$cp_money_record2['type']='商家诚信订单奖励';
		$cp_money_record2['t_id']=$t_id;
		$cp_money_record2['is_jia']=1;
		$cp_money_record2['create_time']=time();
		M('company_money_record')->add($cp_money_record2);//添加金额变动记录
		//发送站内信息
		$prizemoney=$money/2;
		$prizemsg="你好,你对应的报账券于".date('Y-m-d H:i:s')."排队报账完成获得商家诚信订单奖励".$prizemoney."元.";
		addMessage($cpuid,1,$prizemsg);//用户通知
		*/
		
		$company=M('company')->find($company_id);
		$company['money'] +=$money;//金额添加
		$company['djmoney'] -=$money;//冻结金额减少
		M('company')->save($company);//添加企业金额
	}
		
}

//由cs_id获取消费者的余额
function get_cs_money($cs_id){
	$rs=M("consumer")->field("money")->where("consumer_id=$cs_id")->find();
	return $rs[money];
}
//由cp_id获取企业的余额
function get_cp_money($cp_id){
	$rs=M("company")->field("money")->where("company_id=$cp_id")->find();
	return $rs[money];
}
//由mn_id获取业务经理的余额
function get_mn_money($mn_id){
	$rs=M("manager")->field("money")->where("manager_id=$mn_id")->find();
	return $rs[money];
}
//由proof的id获取企业id
function getcpid_from_proofid($proof_id){
	$rs=M("proof2order")->field('b.company_id as cp_id')->alias('a')->join('bzb_orders b on b.id=a.order_id')->where("a.proof_id=$proof_id")->find();
	return $rs[cp_id];
}
//由proof的id获取消费者id
function getcsid_from_proofid($proof_id){
	$rs=M("proof")->field('consumer_id')->where("id=$proof_id")->find();
	return $rs[consumer_id];
}
//由proof的id获取订单id
function getorderid_from_proofid($proof_id){
	$rs=M("proof2order")->field('order_id')->where("proof_id=$proof_id")->find();
	return $rs[order_id];
}
//由proof的id获取报账券号
function getproof_no_from_proofid($proof_id){
	$rs=M("proof")->field('proof_no')->where("id=$proof_id")->find();
	return $rs[proof_no];
}

//记录调整日志
/*
*param $admin_id:管理员id;
*param $admin_name:管理员用户名;
*param $doing_nums:操作的模块与方法;
*param $doing_ids:操作操作的券id和成员id及csid;

*/
function editqueue_log($admin_id,$admin_name,$doing_nums,$doing_ids,$submit_param){
	$data[admin_id]=$admin_id;
	$data[admin_name]=$admin_name;
	$data[doing_nums]=$doing_nums;
	$data[doing_ids]=$doing_ids;
	$data[submit_param]=$submit_param;
	$data[doing_time]=time();
	$data[doing_ip]=get_client_ip();
	
	$rs=M('editqueue_log')->add($data);
}

//由id获取分类名称
function getkbcatname($id){
	$rs=M('kb_cat')->where("id=$id")->find();
	return $rs[name];
}

//有id获取店铺名称
function getkbshopname($id){
	$rs=M('kb_shop')->where("id=$id")->find();
	return $rs[name];
}
//有company_id获取企业名称
function getkbcompanyname($company_id){
	if($company_id!=''){
	$rs=M('company')->where("company_id=$company_id")->find();
	return $rs[company_name];
	}else
		return '--';
}
//获取用户名称
function getusername($user_id){
	$rs=M('user')->where("user_id=$user_id")->find();
	return $rs[user_name];
}

//口碑审核状态
function getkbcheck($is_check){
	$arr=array(
	0=>'等待审核',
	1=>'已通过审核',
	2=>'已拒绝',
	);
	return $arr[$is_check];
}

//由batch查口碑券的数量
function getcouponnum($batch){
	$rsnum=M("kb_coupon")->where("batch=$batch")->count();
	return $rsnum;
}
	
?>