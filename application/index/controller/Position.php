<?php 
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
/**
 * 部门职位管理 列表 添加 修改 删除
 * @author fmj
 * @time 2018-08-14
 */
class Position extends Base{

	protected $model;
	public function _initialize(){
		parent::_initialize();
		$this->model = model('position');
	}
	//部门职位列表
	public function index(){
		$did = input('did',0);
		$condition = array();//查询条件
		$departinfo = array();//部门信息
		if($did){
			$condition['depart_id'] = $did;
			if(request()->isAjax()){
				$departs = $this->model->searchData($condition);
				return json($departs);
			}
			$departinfo = Db::name('department')->find($did);
		}
		// 查询出所有的部门信息，拼接成一维数组 键名（编号），键值（部门名称）
		// $departmodel = model('depart');
		// $departs = $departmodel->searchData();
		// $departs = Db::name('department')->column('id,depart_name');
		// $this->assign('departs',$departs);
		$list = $this->model->searchPage($condition,'a.*,b.depart_name',3);
		$this->assign('departinfo',$departinfo);
		$this->assign('list',$list);
		return view();
	}

	//添加部门职位页面
	public function add(){
		if(request()->isGet()){
		$did = input('did',0);

		$departinfo = array();//部门信息

		$departmodel = model('depart');

		if($did){
			$departinfo = $departmodel->searchData(['id'=>$did],"*",1);
		}
		// echo  Db::getLastSql();
		// die();

		//查询所有的部门
		$departs = $departmodel->searchData(['status'=>1]);

		$this->assign('departs',$departs);

		$this->assign('departinfo',$departinfo);
		return view();
	
	}elseif(request()->isPOst()){

	$param = input('post.');//接收表单提交的数据
		//职位基础数据
		$data = array(
			'depart_id' => $param['departid'],//部门编号
			'position_name' => $param['name'],//职位名称
			'status' => $param['status']//状态
		);
		$result = $this->model->save($data);
		if($result===false){
			$this->error($this->model->getError());
		}else{
			$this->success('添加成功','position/index');
		}


	}

}

	

	//部门职位编辑页面
	public function edit(){

		if(request()->isGet()){
				$id = input('id');
		//查询所有的部门
		$departmodel = model('depart');

		$departs = $departmodel->searchData(['status'=>1]);

		$model = model('position');

		$this->assign('departs',$departs);

		$this->assign('info',$model->get($id));
		return view();


		}elseif(request()->isPost()){

			$param = input('post.');//接收表单数据
		///数据自动验证
		$validate = Loader::validate('Position');
		// 调用验证器的验证方法
		if(!$validate->check($param)){
			$this->error($validate->getError());
		}
		//职位基础数据
		//键名[数据库表的真实字段]=》键值[表单中的name属性]
		$data = array(
			'depart_id' => $param['departid'],//部门编号
			'position_name' => $param['name'],//职位名称
			'status' => $param['status']//状态
		);
		$ret = $this->model->editData(['id'=>$param['id']],$data);
		//判断添加结果，如果添加成功，则跳转到列表页面
		if($ret===false){
			$this->error('修改失败');
		}else{
			$this->success('修改成功','position/index');
		}


		}
		
	}



	//删除操作
	public function delete(){
		


		$id = input('pid');//接收部门编号参数

		


		$ret = $this->model->del(['id'=>$id]);
		if($ret===false){
			$this->error('删除失败');
		}else{
			$this->success('删除成功','position/index');
		}
	}
}