<?php
namespace Admin\Controller;
use Think\Controller;
class RbacController extends BaseController{
	public function index(){
		$user = M('admin')->field('a.*,b.role_id')->join('as a left join bzb_role_admin as b on a.user_id = b.user_id')->select();
		foreach ($user as $key => &$value) {
			$value['role'] = M('role')->where(array('id'=>$value['role_id']))->getField('remark');
		}
		
		$this->user=$user;
		$this->display();
	}
		/**
	 * 添加编辑用户
	 */
	public function addUser(){
		$user_id = I('get.user_id');
		$role_id = I('get.role_id');
		if(!empty($user_id)){
			$data = M('admin')->where(array('user_id'=>$user_id))->find();
			$this->assign('data',$data);
		}
		$role = M('role')->select();
		$this->assign('role_id',$role_id);
		$this->assign('role',$role);
		$this->display();
	}

	/**
	 * 添加动作
	 * @return [type] [description]
	 */
	public function do_user(){
		$user_id = I('post.user_id');
		$role_id = I('post.role_id');
		if(empty($user_id)){
			$data = array(
				'username' => I('post.username'),
				'password' => md5(I('post.password')),
				'created_at'   => time()
			);
			$status = M('admin')->add($data);
			$data = array(
				'role_id' => $role_id,
				'user_id' => $status
				);
			$status = M('role_admin')->add($data);
			if($status){
				$this->success('添加成功',U('Admin/Rbac/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			if(I('post.password')!=''){
				$pass=md5(I('post.password'));
			}
			else{
				$pass=I('post.passwordold');
			}
			$data = array(
				'username' => I('post.username'),
				'password' => $pass,
				'updated_at'   => time()
			);
			$status = M('admin')->where(array('user_id'=>$user_id))->setField($data);
			M('role_admin')->where(array('user_id'=>$user_id))->delete();
			$data = array(
				'role_id' => $role_id,
				'user_id' => $user_id,
				);
			$status = M('role_admin')->add($data);
			if($status){
				$this->success('修改成功',U('Admin/Rbac/index'));
			}else{
				$this->error('修改失败');
			}
		}
		
	}


	/**
	 * 删除用户
	 * @return [type] [description]
	 */
	public function del_user(){
		$id = I('get.id');
		$status = M('admin')->where(array('user_id'=>$id))->delete();
		if($status){
			$status = M('role_admin')->where(array('user_id'=>$id))->delete();
			if($status){
				$this->success('删除成功',U('Admin/Rbac/index'));
			}else{
				$this->error('删除失败1');
			}
		}else{
			$this->error('删除失败0');
		}
	}

		
	//角色列表
	public function role(){
		$this->role = M('role')->select();
		$this->display();
	}
		
	//节点列表
	public function node(){
		$field = array('id','name','title','pid');
		$node = M('node')->field($field)->order('sort')->select();
		$this->node = node_merge($node);
		$this->display();
	}
		
	//添加角色
	public function addRole(){
		//echo '添加角色';
		$this->display();
	}
		
	//角色添加处理
	public function addRoleHandle(){
		//p($_POST);die;
		if(M('role')->add($_POST)){
			$this->success('添加成功', U('Rbac/role'));
		} else {
			$this->error('添加失败');
		}
	}
	/**
	 * 删除角色
	 * @return [type] [description]
	 */
	public function del_role(){
		$id = I('get.id');
		$status = M('role')->where(array('id'=>$id))->delete();
		if($status){
			$this->success('删除成功',U('Admin/Rbac/role'));
		}else{
			$this->error('删除失败');
		}
	}
		
	//添加节点
	public function addNode(){
		$this->pid = I('pid', 0, 'intval');
		$this->level = I('level', 1, 'intval');
		switch($this->level){
			case 1:
				$this->type = "应用";
				break;
			case 2:
				$this->type = "控制器";
				break;
			case 3:
				$this->type = "方法";
				break;
		}
		
		$this->display();
	}
		
	//添加节点处理
	public function addNodeHandle(){
		//p($_POST);die;
		if(M('node')->add($_POST)){
			$this->success('添加成功', U('Rbac/node'));
		} else {
			$this->error('添加失败');
		}
	}
	
	/**
	 * 删除节点
	 * @return [type] [description]
	 */
	public function del_node(){
		$id = I('get.id');
		$status = M('node')->where(array('id'=>$id))->delete();
		if($status){
			$this->success('删除成功',U('Admin/Rbac/node'));
		}else{
			$this->error('删除失败');
		}
	}
		
	//权限处理
	public function access(){
		$rid = I('rid', 0, 'intval');
		$field = array('id', 'name', 'title', 'pid');
		$node = M('node')->order('sort')->field($field)->select();
		
		//原有权限
		$access = M('access')->where(array('role_id' => $rid))->getField('node_id', true);
		$this->node = node_merge($node, $access);
		$this->rid = $rid;
		$this->display();
	}
		
	public function setAccess(){
		$rid = I('rid', 0, 'intval');
		$db = M('access');
		
		//删除原来的权限
		$db->where(array('role_id' => $rid))->delete();
		
		$data = array();
		foreach ($_POST['access'] as $v){
			$tmp = explode('_', $v);
			$data[] = array(
				'role_id' => $rid,
				'node_id' => $tmp[0],
				'level' => $tmp[1],
			);
		}
		
		if($db->addAll($data)){
			$this->success('添加成功', 'role');
		} else {
			$this->error('添加失败');
		}
	}
}
?>