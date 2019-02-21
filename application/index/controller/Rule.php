<?php 
namespace app\index\controller;
use think\Controller;
/**
 * 权限规则管理
 */
class Rule extends Base{


	protected $model;

	public function _initialize(){
		parent::_initialize();
		$this->model = model("rule");
	}


	//权限展示
	public function  index(){

		//查询出权限表rule的数据
		$list = $this->model->searchData();

		//将数据使用公共文件的层级函数处理
		$list = getRuleLevel($list);
		
		$this->assign("list",$list);
		//模板
		return view();

	}

	public function add(){
		if(request()->isGet()){
			//获取权限规则
			//查询出所有开启状态的权限数据
			$data = $this->model->searchData(['status'=>1]);
			//读取层级结构
			$data = getRuleLevel($data);
			//发送数据
			$this->assign('rules',$data);
			//模板展示
			return view();

		}elseif(request()->isPost()){

			//接收表单提交的数据
			$param = input('post.');
			//验证数据
			//进行数据库新增操作
			$ret = $this->model->addData($param);
			
			if($ret===false){
				$this->error($this->model->getError());
			}else{
				$this->success('添加成功','rule/index');
			}
		}
		
	}

	public function edit(){
		if(request()->isGet()){
			//get传输  默认是整形的0
			$id = input('id/d',0);
			//判断数据是否为空
			if(empty($id)){
				$this->error("参数错误");
			}
			//查询id为get传值的数据  所有字段  是否是单条数据 1代表单条数据
			$info = $this->model->searchData(['id'=>$id],"*",1);
			//查询出所有开启的权限 以便修改是选择
			$data = $this->model->searchData(['status'=>1]);
			//权限层级结构
			$data = getRuleLevel($data);
			//发送数据
			$this->assign('rules',$data);
			$this->assign('info',$info);
			//模板展示
			return view();
		}elseif(request()->isPost()){
			//接收表单数据
			$param = input('post.');
			//执行修改 修改id值为$param['id']  数据为$param
			$ret = $this->model->editData(['id'=>$param['id']],$param);
			//判断修改结果，如果修改成功，则跳转到列表页面
			if($ret===false){
				$this->error('修改失败');
			}else{
				$this->success('修改成功','rule/index');
			}
		}
		
	}

		//删除操作
	public function delete(){
		$id = input('id');//接收编号参数
		$condition = [];
		
		$condition['id'] = $id;
	
		$ret = $this->model->del($condition);
		if($ret===false){
			$this->error('删除失败');
		}else{
			$this->success('删除成功','rule/index');
		}
	}


}	