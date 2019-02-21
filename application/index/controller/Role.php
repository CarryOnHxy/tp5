<?php 
namespace app\index\controller;
use think\Controller;
/**
 * 角色管理 角色添加、修改、删除、查询
 * @time 2018-08-15
 */
class Role extends Base{

	protected $model;

	public function _initialize(){
		parent::_initialize();
		$this->model = model("role");
	}



	public  function index(){

		//查询出所有的角色 并分页
		// 没有条件  默认显示5条信息一页  通过id 升序排序
		$list = $this->model->searchPage([],'*',6,'id ASC');

		// var_dump($list);
		// die();
		$this->assign('list',$list);
		return view();
	}


	//角色添加页面
	public function add(){
		if(request()->isGet()){
			//查询出所有的顶级权限
			$allrule = model('rule')->searchData(['status'=>1]);
			$allrule = getRuleTree($allrule);//调用递归函数，生成权限规则树形结构
			$this->assign('allrule',$allrule);
			return view();
		}elseif(request()->isPost()){
			$param = input('post.');
			//数据验证
			if(empty($param['role_name'])){
				$this->error('请填写角色名称');
			}
			//验证成功后进行数据提交操作

			if($param['role_rules'] && is_array($param['role_rules'])){

				$param['role_rules'] = implode(',',$param['role_rules']);
			}
			$result = $this->model->addData($param);
			if($result===false){
				$this->error($this->model->getError());
			}else{
				$this->success('添加成功','role/index');
			}
		}
		
	}

	//角色编辑页面
	public function edit(){

		$id = input('id/d',0);
		if(empty($id)){

			$this->error('缺少参数');
		}
		if(request()->isGet()){
			//
			$info = $this->model->searchData(['id'=>$id],"*",1);//角色信息
			//角色已分配的权限
			$rolerules = explode(',',$info['role_rules']);
			$allrule = model('rule')->searchData(['status'=>1]);
			foreach($allrule as $key => $val){
				$allrule[$key]['checked'] = false;
				if(in_array($val['id'],$rolerules)){
					$allrule[$key]['checked'] = true;
				}
			}
			$allrule = getRuleTree($allrule);//调用递归函数，生成权限规则树形结构
			$this->assign('info',$info);
			$this->assign('allrule',$allrule);
			return view();
		}elseif(request()->isPost()){
			$param = input('post.');
			//数据验证
			if(empty($param['role_name'])){
				$this->error('请填写角色名称');
			}
			//验证成功后进行数据提交操作
			if($param['role_rules'] && is_array($param['role_rules'])){
				$param['role_rules'] = implode(',',$param['role_rules']);
			}
			$result = $this->model->editData(['id'=>$id],$param);
			if($result===false){
				$this->error($this->model->getError());
			}else{
				$this->success('添加成功','role/index');
			}
		}
	}

	//角色删除操作
	public function delete(){
		$id = input('id');//接收职位编号参数
		$condition = [];
		$condition['id'] = $id;
		$ret = $this->model->del($condition);
		if($ret===false){
			$this->error('删除失败');
		}else{
			$this->success('删除成功','role/index');
		}
	}

}	