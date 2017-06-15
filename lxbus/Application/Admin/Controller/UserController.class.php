<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController {
	//用户列表
    public function index(){
		$keyword=$_GET[keyword];
		$keyword1=$_GET[keyword1];
		$keyword2=$_GET[keyword2];


		$time1=strtotime($keyword);
		$time2=strtotime($keyword1);

		if($keyword!=''&& $keyword1!='' && $keyword2!=''){
			$where="(create_time)>=$time1 and (create_time)<=$time2 and user_name like '%$keyword2%'";
		}elseif(empty($keyword) && empty($keyword1) && !empty($keyword2)){
			$where = "user_name like '%$keyword2%'";

		}
		elseif(!empty($keyword) && !empty($keyword1) && empty($keyword2)){
			$where="(create_time)>=$time1 and create_time <=$time2";
		};

		$usernum=M('user')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('user')->field('user_id,user_name,password,email,phone,real_name,qq,create_time,last_login_time,last_login_ip,user_type')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		//dump($views_data['userlist']);die;
		
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->keyword1=$keyword1;
		$this->keyword2=$keyword2;
		$this->userlist=$userlist;
		$this->display();
    }
	public function photo(){
		$keyword=$_GET[keyword];
		$keyword1=$_GET[keyword1];

		$time1=strtotime($keyword);
		$time2=strtotime($keyword1);
		//dump($time1);//die;
		if($keyword!=''&& $keyword1!=''){
			$where="(create_time)>=$time1 and (create_time
)<=$time2 ";
		}
		$usernum=M('message')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('driver_pic')->field('id,user_id,pic_url,create_time,create_ip')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		//dump($views_data['userlist']);die;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->userlist=$userlist;
		$this->display();
	}
	public function message(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="message like '%$keyword%' or user_id like '%$keyword%'";
		}else
		{
			$where="1=1";
		}
		$usernum=M('message')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('message')->field('id,user_id,msg_type,message,is_read,create_time,read_time')->where($where)->limit($Page->firstRow,$Page->listRows)->order("create_time desc")->select();
		//dump($views_data['userlist']);die;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/index","用户列表",0);

		$this->usernum=$usernum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->userlist=$userlist;
		$this->display();
	}
	//登录
	public function loginByphone(){

		//session_destroy();
		$where['phone']=I('phone');
		$where['password']=I('password');
//		$where['is_active']=1;
		//dump($where);die;
		$M_USER=M('user');
		$user=$M_USER->where($where)->find();
		//判断有没有对应的消费者，企业或业务经理
		if(!$user){
			echo -1;
		}
		else{
			$data=array(
				'user_id' => $user['user_id'],
				'last_login_time' => time(),
				'last_login_ip' => get_client_ip()
				);
			$M_USER->save($data);
			session('user_id',$user['user_id']);
			session('user_name',$user['user_name']);
			$u_type=0;
			if($user['consumer_id']>0){
				session('consumer_id',$user['consumer_id']);
				session('consumer_name',$user['real_name']);
				session('login_name',$user['real_name']);
				$u_type++;
			}
			if($user['company_id']>0){
				session('company_id',$user['company_id']);
				$company=M('company')->find($user['company_id']);
				session('company_name',$company['company_name']);
				session('login_name',$company['company_name']);
				$u_type+=2;
			}
			else{
				session('company_id',0);
			}
			session('u_type',$u_type);
			//dump($_SESSION);die;
			//写入操作日志
//			admin_log(session('admin_id'),session('admin_name'),"User/loginByphone->".I('phone'),"登录用户",0);
//			echo 0;
		}
	}

	//导出用户Excel
	public function usertoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="real_name like '%$keyword%' or phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}

		$xlsName  = "用户";
		$xlsCell  = array(
		array('user_id','用户ID'),
		array('user_name','用户名'),
		array('email','邮箱'),
		array('phone','手机号'),
		array('real_name','真名'),
		array('qq','qq'),

		array('create_time','注册时间'),
		array('last_login_time','最后登录时间'),
		array('last_login_ip','最后登录IP'),
		array('user_type','注册类型'),
//		array('is_active','是否激活')

		);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/usertoexcel","导出用户Excel",0);

		$xlsModel = M('user');

		$xlsData  = $xlsModel->Field('user_id,user_name,email,phone,real_name,qq,create_time,last_login_time,last_login_ip,user_type')->where($where)->order("create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{

			$xlsData[$k]['user_type']=getregtype($v['user_type']);
//			$xlsData[$k]['is_active']=$v['is_active']==1?'已激活':'未激活';
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['last_login_time']=$v['last_login_time']>0?date('Y-m-d H:i',$v['last_login_time']):'未登录过';
		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}
	//编辑用户
	public function edit_user(){
		$data['user_id']=I('user-id');
		$data['user_name']=I('phone');
		$data['phone']=I('phone');
		$data['email']=I('email');

		$data['real_name']=I('real_name');
		$data['qq']=I('qq');
		if(I('password')!=''){
			$data['password']=md5(I('password'));
			$data['access_token']=md5(I('password'));
		}
		M('user')->save($data);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/edit_user","编辑用户",$data['user_id']);

		echo 'edit-success';
	}

	//禁用，启用用户
	public function active_user(){
		$data['user_id']=I('active_uid');
		$nowactiveid=I('active_id');
		if($nowactiveid==1){
//			$data['is_active']=0;
		}
		if($nowactiveid==0){
//			$data['is_active']=1;
		}
		M('user')->save($data);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/active_user","禁用，启用用户",$data['user_id']);

		echo 'active-success';
	}

	//解绑微信
	public function dowx_user(){
//		$data['user_id']=I('wx_id');
//		$data['wx_id']='';
		$data['wx_name']='';
		$data['wx_city']='';
		$data['wx_province']='';
		$data['wx_country']='';
		$data['headpic']='';
		M('user')->save($data);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/dowx_user","用户解绑微信",$data['user_id']);

		echo 'active-success';
	}

	//编辑企业
	public function edit_company(){
		$user['company_id']=I('user-id');
		$user['phone']=I('phone');
		$user['email']=I('email');
		$user['qq']=I('qq');
		M('user')->where('company_id='.$user['company_id'])->save($user);
		$company['company_id']=I('user-id');
		$company['company_name']=I('company_name');
		$company['telphone']=I('telphone');
		$company['company_address']=I('address');
		$company['legal_person']=I('legal_person');
		M('company')->save($company);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/edit_company","编辑企业",$data['company_id']);

		echo 'edit-success';
	}

	//删除企业

	public function delcom(){
		$id=$_GET[id];
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/delcom","删除企业",$id);

		$num=M("orders")->where("company_id=$id")->count();
		if($num>0){
			echo -2;exit;
		}
		$num2=M("charge")->where("company_id=$id")->count();
		if($num2>0){
			echo -3;exit;
		}
		$rs=M("company")->where("company_id=$id")->delete();
		if($rs){
			$com[company_id]=0;
			$rs2=M("user")->where("company_id=$id")->save($com);
			echo 0;
		}else{
			echo -1;
		}
		//写入操作日志
		//admin_log(session('admin_id'),session('admin_name'),"User/delcom","删除企业",$id);

	}

	//消费者列表
	public function cs(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="real_name like '%$keyword%' or phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}
		$tjren=$_GET[tjren];
		if($tjren!=''){
			$where.=" and tjren=$tjren";
		}
		$csnum=M("user")->join('bzb_consumer on bzb_consumer.consumer_id =bzb_user.consumer_id')->where($where)->count();
		$Page = new \Think\Page($csnum,20);//导入分页类
		$show  = $Page->show();
		$cslist=M('user')->join('bzb_consumer on bzb_consumer.consumer_id =bzb_user.consumer_id')->where($where)->field('bzb_user.*,bzb_consumer.create_time as cs_time,bzb_consumer.tjren as tjren')->limit($Page->firstRow,$Page->listRows)->order("bzb_user.create_time desc")->select();

		//写入操作日志
//		$aaa=admin_log(session('admin_id'),session('admin_name'),"User/cs","消费者列表",0);
//		echo $aaa;
		$this->csnum=$csnum;
		$this->page=$show;
		$this->cslist=$cslist;
		$this->display();
	}
	//导出消费者Excel
	public function cstoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="real_name like '%$keyword%' or phone like '%$keyword%'";
		}
		else
		{
			$where="1=1";
		}
		$tjren=$_GET[tjren];
		if($tjren!=''){
			$where.=" and tjren=$tjren";
		}

		$xlsName  = "消费者";
		$xlsCell  = array(
		array('user_id','用户ID'),
		array('user_name','用户名'),
		array('create_time','注册时间'),
		array('email','邮箱'),
		array('phone','手机号'),
		array('real_name','姓名'),
		array('qq','qq'),
		array('tjren','推荐人'),
		array('last_login_time','最后登录时间'),
		array('last_login_ip','最后登录IP'),

		);
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cstoexcel","导出消费者Excel",0);

		$xlsData  = M('user')->join('bzb_consumer on bzb_consumer.consumer_id =bzb_user.consumer_id')->where($where)->Field('user_id,user_name,bzb_user.create_time as create_time ,email,phone,real_name,qq,bzb_consumer.tjren,last_login_time,last_login_ip')->where($where)->order("create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['tjren']=getuserphone($v['tjren']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['last_login_time']=$v['last_login_time']>0?date('Y-m-d H:i',$v['last_login_time']):'未登录过';

		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}

	//企业列表
	public function cp(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}

		$tjren=$_GET[tjren];
		if($tjren!=''){
			$where.=" and tjren=$tjren";
		}
		$cpnum=M('company')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->count();
		$Page = new \Think\Page($cpnum,20);//导入分页类
		$show  = $Page->show();
		$cplist=M('company')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_company.create_time desc")->select();

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cp","企业列表",0);

		$this->cpnum=$cpnum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cplist=$cplist;
		$this->display();
	}
	//导出企业Excel
	public function cptoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}

		$tjren=$_GET[tjren];
		if($tjren!=''){
			$where.=" and tjren=$tjren";
		}

		$xlsName  = "企业会员";
		$xlsCell  = array(
		array('company_id','企业编号'),
		array('company_name','企业名称'),
		array('create_time','注册时间'),
		array('phone','联系手机'),
		array('telphone','电话'),
		array('legal_person','法人'),
		array('email','邮箱'),
		array('qq','qq'),
		array('province_id','省份'),
		array('city_id','城市'),
		array('area_id','区县'),
		array('company_address','详细地址'),
		array('tjren','推荐人'),
		array('last_login_time','最后登录时间'),
		array('last_login_ip','最后登录IP'),

		);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cptoexcel","导出企业Excel",0);

		$xlsData  = M('company')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->Field('bzb_company.company_id,bzb_company.company_name,bzb_company.create_time as create_time ,bzb_company.phone,bzb_company.telphone,bzb_company.legal_person,bzb_company.email,bzb_company.qq,bzb_company.province_id,bzb_company.city_id,bzb_company.area_id,bzb_company.company_address,bzb_company.tjren,last_login_time,last_login_ip')->where($where)->order("create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['province_id']=getprovincename($v['province_id']);
			$xlsData[$k]['city_id']=getcityname($v['city_id']);
			$xlsData[$k]['area_id']=getareaname($v['area_id']);
			$xlsData[$k]['tjren']=getuserphone($v['tjren']);
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['last_login_time']=$v['last_login_time']>0?date('Y-m-d H:i',$v['last_login_time']):'未登录过';

		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}
	//业务经理列表
	public function mn(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.real_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}
		$mntype=$_GET[mntype];
		if($mntype!=''){
			$where.=" and bzb_manager.level=$mntype";
		}
		$mnnum=M('manager')->join('bzb_user on bzb_user.manager_id=bzb_manager.manager_id')->where($where)->count();
		$Page = new \Think\Page($mnnum,20);//导入分页类
		$show  = $Page->show();
		$mnlist=M('manager')->join('bzb_user on bzb_user.manager_id=bzb_manager.manager_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_manager.create_time desc")->select();

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/mn","业务经理列表",0);

		$this->mnnum=$mnnum;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->mntype=$mntype;
		$this->mnlist=$mnlist;
		//dump($mnlist);die;
		$this->display();
	}
	//导出业务经理Excel
	public function mntoexcel(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="bzb_user.real_name like '%$keyword%' or bzb_user.phone like '%$keyword%'";
		}else
		{
			$where="1=1";
		}
		$mntype=$_GET[mntype];
		if($mntype!=''){
			$where.=" and bzb_manager.level=$mntype";
		}


		if($mntype=="0")
			$xlsName  = "合伙人";
		if($mntype=="1")
			$xlsName  = "业务经理";
		if($mntype=='')
			$xlsName = "业务会员";

		$xlsCell  = array(
		array('manager_id','用户ID'),
		array('user_name','用户名'),
		array('create_time','注册时间'),
		array('email','邮箱'),
		array('phone','手机号'),
		array('real_name','姓名'),
		array('qq','qq'),
		array('level','业务等级'),
		array('tjcs','推荐消费者'),
		array('tjcp','推荐企业'),
		array('last_login_time','最后登录时间'),
		array('last_login_ip','最后登录IP'),

		);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/mntoexcel","导出业务经理Excel",0);

		$xlsData  = M('manager')->join('bzb_user on bzb_user.manager_id=bzb_manager.manager_id')->where($where)->Field('bzb_manager.manager_id as manager_id,bzb_user.user_name as user_name,bzb_user.create_time as create_time ,bzb_user.email as email,bzb_user.phone as phone,bzb_user.real_name as real_name,bzb_user.qq as qq,bzb_manager.level as level,bzb_user.user_id as tjcs,bzb_user.user_id as tjcp,bzb_user.last_login_time,bzb_user.last_login_ip')->where($where)->order("create_time desc")->select();
		foreach ($xlsData as $k => $v)
		{
			$xlsData[$k]['create_time']=$v['create_time']>0?date('Y-m-d H:i',$v['create_time']):'无';
			$xlsData[$k]['last_login_time']=$v['last_login_time']>0?date('Y-m-d H:i',$v['last_login_time']):'未登录过';
			$xlsData[$k]['level']=getManagerGrade($v['level']);
			$xlsData[$k]['tjcs']=gettjcsnum($v['tjcs']);
			$xlsData[$k]['tjcp']=gettjcpnum($v['tjcp']);

		}
		exportExcel($xlsName,$xlsCell,$xlsData);

	}


	//个人认证申请列表
	public function userapply(){
		$keyword=$_GET[keyword];
		$is_rz=$_GET[is_rz];
		if($keyword!=''){
			$where="(real_name like '%$keyword%' or phone like '%$keyword%') and idcard_face!='' and idcard_back!=''";
		}else if($is_rz!=''){
			$where="is_check_idcard='$is_rz' and idcard_face!='' and idcard_back!=''";
		}else{
			$where="idcard_face!='' and idcard_back!=''";
		}
		$usernum=M('user')->where($where)->count();

		$Page = new \Think\Page($usernum,20);//导入分页类
		$show  = $Page->show();
		$userlist=M('user')->where($where)->limit($Page->firstRow,$Page->listRows)->order("idcard_addtime desc")->select();
		//dump($views_data['userlist']);die;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/userapply","个人认证申请列表",0);

		$this->page=$show;
		$this->keyword=$keyword;
		$this->is_rz=$is_rz;
		$this->userlist=$userlist;
		$this->display();
	}
	//个人认证审核通过
	public function check_user(){
		$data[user_id]=$_GET[user_id];
		$data[check_remark]=$_GET[check_remark];
		$data[is_check_idcard]=$_GET[is_check];
		$data[idcard_checktime]=time();
		$rs=M("user")->save($data);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/check_user","个人认证审核通过",$data['user_id']);

		if($rs)
			echo "do-success";

	}

	public function adduserrz(){
		$phone=$_GET[phone];
		$user=M("user")->where("phone='$phone'")->find();
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/adduserrz","打开添加个人认证",$data['user_id']);

		$this->user=$user;
		$this->display();
	}

	//添加认证
	public function doadduserrz(){
		$data['user_id']=$_GET['user_id'];
		$data['real_name']=I('real_name');
		$data['idcard_id']=I('idcard_id');

		$data['idcard_addtime']=time();
		$data['is_check_idcard']=1;
		if(I('idcard_face'))
			$data['idcard_face']=I('idcard_face');
		if(I('idcard_back'))
			$data['idcard_back']=I('idcard_back');

		//查询提交认证的次数,达到认证数，不能提交
		$idcard_id=I('idcard_id');
		$rznum=M('user')->where("idcard_id='$idcard_id'")->count();
		if($rznum==C('idcard_num')){
			echo -2;exit;
		}
		$rs=M('user')->save($data);
		if($rs)
			echo 0;
		else
			echo -1;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/doadduserrz","执行个人认证审核通过",$data['user_id']);
	}
	//个人认证上传图片
	public function picAdd()
	{

		$user_id=$_POST[user_id];
		$img = $_POST['img'];

		if(empty($img))
		{
			return false;
		}

		// 获取图片
		list($type, $data) = explode(',', $img);

		// 判断类型
		if(strstr($type,'image/jpeg')!=='')
		{
			$ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=='')
		{
			$ext = '.gif';
		}elseif(strstr($type,'image/png')!=='')
		{
			$ext = '.png';
		}
		//判断是否是修改的
		$type=$_POST[type];

		if($type==1){
			$uswhere="user_id=$user_id and idcard_face<>''";
		}
		if($type==2){
			$uswhere="user_id=$user_id and idcard_back<>''";
		}

		$r = M("user")->field('user_id')->where($uswhere)->find();
		if(!empty($r)){
			$ret = array('img'=>'','msg'=>'您已经传过一张照片了,请先删除！');
			echo json_encode($ret);
			exit;
		}else{
			// 生成的文件名
			$photo = "Uploads/userpic/".time().$ext;

			// 生成文件
			file_put_contents($photo, base64_decode($data), true);
			// 返回
			header('content-type:application/json;charset=utf-8');
			$ret = array('img'=>$photo,'msg'=>'图片上传成功');
			echo json_encode($ret);
			exit;
		}

	}
	//个有认证删除图片
	public function picdel()
	{
		$user_id=$_GET[user_id];
		$type=$_GET[type];
		$pic=$_GET[pic];

		if($type==1){
			$rs=M("user")->where("user_id=$user_id")->save(array('idcard_face'=>''));
			unlink(".".$pic);
			echo 0;
		}
		elseif($type==2){
			$rs=M("user")->where("user_id=$user_id")->save(array('idcard_back'=>''));
			unlink(".".$pic);
			echo 0;
		}

	}
	//企业认证上传图片
	public function picAdd2()
	{

		$company_id=$_POST[company_id];
		$img = $_POST['img'];

		if(empty($img))
		{
			return false;
		}

		// 获取图片
		list($type, $data) = explode(',', $img);

		// 判断类型
		if(strstr($type,'image/jpeg')!=='')
		{
			$ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=='')
		{
			$ext = '.gif';
		}elseif(strstr($type,'image/png')!=='')
		{
			$ext = '.png';
		}
		//判断是否是修改的
		$type=$_POST[type];

		if($type==1){
			$uswhere="company_id=$company_id and idcard_face<>''";
		}
		if($type==2){
			$uswhere="company_id=$company_id and idcard_back<>''";
		}
		if($type==3){
			$uswhere="company_id=$company_id and license_pic<>''";
		}

		$r = M("company")->where($uswhere)->find();
		if(!empty($r)){
			$ret = array('img'=>'','msg'=>'您已经传过一张照片了,请先删除！');
			echo json_encode($ret);
			exit;
		}else{
			// 生成的文件名
			$photo = "Uploads/company/".time().$ext;

			// 生成文件
			file_put_contents($photo, base64_decode($data), true);
			// 返回
			header('content-type:application/json;charset=utf-8');
			$ret = array('img'=>$photo,'msg'=>'图片上传成功');
			echo json_encode($ret);
			exit;
		}

	}

	//企业认证删除图片
	public function picdel2()
	{
		$company_id=$_GET[company_id];
		$type=$_GET[type];
		$pic=$_GET[pic];

		if($type==1){
			$rs=M("company")->where("company_id=$company_id")->save(array('idcard_face'=>''));
			unlink(".".$pic);
			echo 0;
		}
		elseif($type==2){
			$rs=M("company")->where("company_id=$company_id")->save(array('idcard_back'=>''));
			unlink(".".$pic);
			echo 0;
		}
		elseif($type==3){
			$rs=M("company")->where("company_id=$company_id")->save(array('license_pic'=>''));
			unlink(".".$pic);
			echo 0;
		}

	}

	//企业申请列表
	public function cpapply(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
//			$where="(bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%') and bzb_company.is_active=0";
		}else
		{
//			$where="bzb_company.is_active=0";
		}
//		$cpnum=M('company')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->count();
//		$Page = new \Think\Page($cpnum,20);//导入分页类
//		$show  = $Page->show();
//		$cplist=M('company')->field('bzb_company.*')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_company.create_time desc")->select();

		//省份
		$province=M("province")->field('provinceid,province')->select();
		//dump($province);exit;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cpapply","企业申请列表",0);

		$this->province=$province;
//		$this->page=$show;
		$this->keyword=$keyword;
//		$this->cplist=$cplist;
		$this->display();
	}

	//审核企业
	public function apply_company(){

		$company['company_id']=I('company_id');
		$company['apply_remark']=I('apply_remark')."(".date('Y-m-d H:i',time()).")";
//		$company['is_active']=I('is_active');

		$rs=M('company')->save($company);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/apply_company","审核企业",$data['company_id']);

		if($rs)
			echo 'edit-success';
	}

	//企业认证列表
	public function cprz(){
		$keyword=$_GET[keyword];
		$is_rzcp=$_GET[is_rzcp];
		if($keyword!=''){
			$where="(bzb_company.company_name like '%$keyword%' or bzb_user.phone like '%$keyword%') and bzb_company.rz_time!=0";
		}
		else if($is_rzcp!=''){//20160629添加企业认证时按照是否认证来查询。villageHead
			$where="bzb_company.is_check='$is_rzcp' and bzb_company.rz_time!=0";//20160629添加企业认证时按照是否认证来查询。villageHead
		}else{
			$where="bzb_company.rz_time!=0";
		}
		$cpnum=M('company')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->count();
		$Page = new \Think\Page($cpnum,20);//导入分页类
		$show  = $Page->show();
		$cplist=M('company')->field('bzb_company.*')->join('bzb_user on bzb_user.company_id=bzb_company.company_id')->where($where)->limit($Page->firstRow,$Page->listRows)->order("bzb_company.create_time desc")->select();

		//省份
		$province=M("province")->field('provinceid,province')->select();
		//dump($province);exit;

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cprz","企业申请列表",0);

		$this->province=$province;
		$this->page=$show;
		$this->keyword=$keyword;
		$this->is_rzcp=$is_rzcp;
		$this->cplist=$cplist;
		$this->display();
	}
	//审核企业
	public function check_company(){

		$company['company_id']=I('company_id');
		$company['check_remark']=I('check_remark');
		$company[is_check]=$_GET[is_check];
		$company[check_time]=time();


		$rs=M('company')->save($company);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/check_company","审核企业",$data['company_id']);

		if($rs)
			echo 'do-success';
	}

	public function addcompanyrz()
	{
		$phone=$_GET[phone];
		$province=M("province")->field('provinceid,province')->select();
		$cpid=M('user')->where("phone='$phone'")->find();
		if($cpid)
			$company=M("company")->find($cpid['company_id']);
		//dump($company);
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/addcompanyrz","打开认证企业",$company['company_id']);
		$this->province=$province;
//		$this->company=$company;
		$this->display();

	}

	//添加企业认证
	public function doaddcompanyrz(){
		$data['company_id']=I('company_id');
		$data['company_name']=I('company_name');
		$data['company_license']=I('company_license');

		$data['province_id']=I('provinceid');
		$data['city_id']=I('cityid');
		$data['area_id']=I('areaid');
		$data['company_address']=I('company_address');

		$data['legal_person']=I('legal_person');
		$data['legal_person_idcard']=I('legal_person_idcard');
		$data['is_check']=1;
		$data['rz_time']=time();
		if(I('idcard_face'))
			$data['idcard_face']=I('idcard_face');
		if(I('idcard_back'))
			$data['idcard_back']=I('idcard_back');
		if(I('license_pic'))
			$data['license_pic']=I('license_pic');

		$rs=M('company')->save($data);
		if($rs)
			echo 0;
		else
			echo -1;
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/doaddcompanyrz","执行认证企业",$company['company_id']);
	}


	//业务员升级列表
	public function mnupgrade(){
		$keyword=$_GET[keyword];
		if($keyword!=''){
			$where="(bzb_user.real_name like '%$keyword%' or bzb_user.phone like '%$keyword%') and bzb_manager_apply.status!=1";
		}else
		{
			$where="bzb_manager_apply.status!=1";
		}
		$mnnum=M('manager_apply')->join('bzb_user on bzb_user.manager_id=bzb_manager_apply.manager_id')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_apply.manager_id')->where($where)->count();
		$Page = new \Think\Page($mnnum,20);//导入分页类
		$show  = $Page->show();
		$mnlist=M('manager_apply')->join('bzb_user on bzb_user.manager_id=bzb_manager_apply.manager_id')->join('bzb_manager on bzb_manager.manager_id=bzb_manager_apply.manager_id')->where($where)->field('bzb_manager_apply.*,bzb_manager.level as oldlevel,bzb_user.phone,bzb_user.real_name')->limit($Page->firstRow,$Page->listRows)->order("bzb_manager_apply.create_time desc")->select();
		//dump($mnlist);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/cprz","企业申请列表",0);

		$this->page=$show;
		$this->keyword=$keyword;
		$this->mnlist=$mnlist;
		$this->display();
	}
	//审核业务经理
	public function check_manager(){
		$mn_id=I('manager_id');
		$level=I('level');
		$manager['id']=I('id');
		$manager['manager_id']=$mn_id;
		$manager['check_remark']=I('check_remark');
		$manager[status]=$_GET[is_check];
		$manager[check_time]=time();

		$rs=M('manager_apply')->save($manager);

		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/check_manager","审核业务经理",$data['manager_id']);

		if($rs){
			if($_GET[is_check]==1)
				//修改等级
				$rs2=M('manager')->where("manager_id=$mn_id")->save(array('level'=>$level));

			echo 'do-success';
		}
	}

	//修改推荐关系
	public function edittuijian(){
		//写入操作日志
//		admin_log(session('admin_id'),session('admin_name'),"User/edittuijian","修改推荐关系",0);
		$this->display();
	}
	public function doedittuijian(){
		$tjtype=$_GET[tjtype];
		$phone=$_GET[phone];
		$tjphone=$_GET[tjphone];
		$password=$_GET[password];
		//dump($_GET);exit;
		//echo $tjtype;exit;
		if($password!=C('TUIJIAN_PASS')){
			echo -1;exit;//密码错误
		}
		$rs1=M('user')->where("phone='$phone'")->find();
		if(!$rs1){
			echo -2;exit;
		}
		$rs2=M('user')->where("phone='$tjphone'")->find();
		if(!$rs2){
			echo -3;exit;
		}

		//修改消费者推荐关系
		if($tjtype==1){
			$csid=$rs1[consumer_id];
			$cstjphone=$phone;
			$tjuid=$rs2[user_id];
			$data[tjren]=$tjuid;
			$rs3=M("consumer")->where("consumer_id=$csid")->save($data);

			//写入操作日志
//			admin_log(session('admin_id'),session('admin_name'),"User/doedittuijian","修改消费者推荐关系:由".$cstjphone."改为".$tjphone,0);
//
//			echo 0;
		}
		elseif($tjtype==2){
			$cpid=$rs1[company_id];
			$cptjphone=$phone;
			$tjuid=$rs2[user_id];
			$data2[tjren]=$tjuid;
			$rs3=M("company")->where("company_id=$cpid")->save($data2);

			//写入操作日志
//			admin_log(session('admin_id'),session('admin_name'),"User/doedittuijian","修改企业推荐关系:由".$cptjphone."改为".$tjphone,0);
//
//			echo 0;
		}
		else{
			echo -4;
		}
		
		
	}
}

