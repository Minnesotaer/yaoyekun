<?php
namespace Admin\Controller;
use Think\Controller;
class WenzhangController extends BaseController {

	//文章列表
	public function wenzhang(){
		$keyword=$_GET[keyword];
		$scms_type=$_GET[cms_type];
		if($keyword!=''){
			$where="(cms_title like '%$keyword%' or cms_sub_title like '%$keyword%') and is_trash=0";
		}
		else
		{
			$where="is_trash=0";		
		}
		if($scms_type!=''){
			$where .=" and cms_type=$scms_type";
		}
		$data_num=M('yk_cms')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('yk_cms')->limit($Page->firstRow,$Page->listRows)->order("cms_publish_date desc")->where($where)->select();
		
		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['ct_id']]['catid']   = $row['ct_id'];
			$cats[$row['ct_id']]['catname'] = $row['ct_name'];

			$childcat=M("yk_cms_type")->where("ct_parent_id=$row[ct_id]")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['id']   = $row2['ct_id'];
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['name'] = $row2['ct_name'];
				}
			}
		}
		
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cms_type=$cats;
		$this->scms_type=$scms_type;
		$this->cms_data=$data;
		$this->display();
	}
	//文章回收站
	public function wenzhang_trash(){
		$keyword=$_GET[keyword];
		$scms_type=$_GET[cms_type];
		if($keyword!=''){
			$where="(cms_title like '%$keyword%' or cms_sub_title like '%$keyword%') and is_trash=1";
		}
		else
		{
			$where="is_trash=1";		
		}
		if($scms_type!=''){
			$where .=" and cms_type=$scms_type";
		}
		$data_num=M('yk_cms')->where($where)->count();
		$Page = new \Think\Page($data_num,20);//导入分页类
		$show  = $Page->show();
		$data=M('yk_cms')->limit($Page->firstRow,$Page->listRows)->order("cms_publish_date desc")->where($where)->select();
		
		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['ct_id']]['catid']   = $row['ct_id'];
			$cats[$row['ct_id']]['catname'] = $row['ct_name'];

			$childcat=M("yk_cms_type")->where("ct_parent_id=$row[ct_id]")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['id']   = $row2['ct_id'];
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['name'] = $row2['ct_name'];
				}
			}
		}
		
		$this->page=$show;
		$this->keyword=$keyword;
		$this->cms_type=$cats;
		$this->scms_type=$scms_type;
		$this->cms_data=$data;
		$this->display();
	}
	//编辑文章
	public function update(){
		$wenzhang=M('yk_cms')->find(I('cms_id'));

		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['ct_id']]['catid']   = $row['ct_id'];
			$cats[$row['ct_id']]['catname'] = $row['ct_name'];

			$childcat=M("yk_cms_type")->where("ct_parent_id=$row[ct_id]")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['id']   = $row2['ct_id'];
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['name'] = $row2['ct_name'];
				}
			}
		}
		//dump($cats);exit;
		$this->category=$cats;
		$this->wenzhang=$wenzhang;
		$this->display();
	}
	//执行编辑文章
	public function edit(){
		//dump($_POST);die;
		if(M('yk_cms')->save($_POST))
			$this->success('保存成功',U('wenzhang'));
		else
			$this->error('error');
	}
	//添加文章
	public function add(){
		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['ct_id']]['catid']   = $row['ct_id'];
			$cats[$row['ct_id']]['catname'] = $row['ct_name'];

			$childcat=M("yk_cms_type")->where("ct_parent_id=$row[ct_id]")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['id']   = $row2['ct_id'];
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['name'] = $row2['ct_name'];
				}
			}
		}
		
		$this->category=$cats;
		$this->display();
	}
	//执行添加文章
	public function save(){
		$_POST['cms_publish_date']=time();
		if(M('yk_cms')->add($_POST))
			$this->success('保存成功',U('wenzhang'));
		else
			$this->error('error');
	}
	//删除文章
	public function del(){
		M('yk_cms')->where('cms_id='.I('cms_id'))->setField('is_trash',1);
		echo 'success';
	}
	//彻底删除文章
	public function delok(){
		M('yk_cms')->where('cms_id='.I('cms_id'))->delete();
		echo 'success';
	}
	//还原文章
	public function delback(){
		M('yk_cms')->where('cms_id='.I('cms_id'))->setField('is_trash',0);
		echo 'success';
	}
	
	//文章分类
	public function wenzhangcat(){
		$keyword=$_GET[keyword];
		$scms_type=$_GET[cms_type];
		if($keyword!=''){
			$where="ct_name like '%$keyword%' and ct_parent_id=0";
		}
		else
		{
			$where="ct_parent_id=0";		
		}
		$cat=M("yk_cms_type")->where($where)->select();
		
		$cats = array();
		foreach ($cat as $row) {
			$cats[$row['ct_id']]['catid']   = $row['ct_id'];
			$cats[$row['ct_id']]['catname'] = $row['ct_name'];

			$childcat=M("yk_cms_type")->where("ct_parent_id=$row[ct_id]")->select();
			if($childcat) {
				foreach ($childcat as $row2){
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['id']   = $row2['ct_id'];
				$cats[$row['ct_id']]['children'][$row2['ct_id']]['name'] = $row2['ct_name'];
				}
			}
		}

		//dump($cats);exit;
		$this->keyword=$keyword;
		$this->cats=$cats;
		$this->display();
	}

	//添加分类
	public function addcat(){
		
		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		$this->cat=$cat;
		$this->display();
	}
	//执行添加分类
	public function savecat(){
		
		if(M('yk_cms_type')->add($_POST))
			$this->success('添加成功',U('addcat'));
		else
			$this->error('error');
	}
	//添加分类
	public function updatecat(){
		$ct_id=$_GET[ct_id];
		$cms_cat=M("yk_cms_type")->where("ct_id=$ct_id")->find();
		$cat=M("yk_cms_type")->where("ct_parent_id=0")->select();
		//dump($cms_cat);exit;
		$this->cms_cat=$cms_cat;
		$this->cat=$cat;
		$this->display();
	}
	//执行添加分类
	public function doupdatecat(){
		
		if(M('yk_cms_type')->save($_POST))
			$this->success('修改成功');
		else
			$this->error('error');
	}
	//删除分类
	public function delcat(){
		M('yk_cms_type')->where('ct_id='.I('ct_id'))->delete();
		echo 'success';
	}
}