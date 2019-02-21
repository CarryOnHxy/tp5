<?php 
namespace app\index\controller;
use think\Controller;
use  think\Db;
/**
 * 员工管理 员工列表 员工添加 员工修改 员工删除
 * @author fmj
 * @time 2018-08-14
 */
class Employee extends Base{
	protected $model;
	public function _initialize(){
		parent::_initialize();
		$this->model = model('employee');
	}
	//员工列表
	public function index(){
		//接收检索条件
		
		$condition = [];
		$list = $this->model->searchPage($condition,'a.*,b.depart_name,c.position_name,d.role_name');

		// echo Db::getLastSql();

		// die();
		//所有的部门信息
		$this->assign('departs',model('depart')->searchData(['status'=>1]));
		$this->assign('list',$list);
		return view();
	}

	//员工添加页面
	public function add(){
		
		if(request()->isGet()){
			//查询出所有的部门
		$departs = model('depart')->searchPage(['status'=>1]);
		//查询出所有的角色信息
		$roles = model('role')->searchData(['status'=>1]);
		//查询出所有的职位信息
		$position = model('position')->searchData(['status'=>1]);

		$this->assign('departs',$departs);
		$this->assign('roles',$roles);
		$this->assign('position',$position);
		return view();
		}elseif(request()->isPost()){
			$param = input('post.');
		//员工表基础数据
		$data = array(
			'depart_id' => $param['depart_id'],
			'position_id' => $param['position_id'],
			'user_code' => $param['user_code'],
			"role_id"=>$param['role_id'],
			'user_pwd' => md5($param['user_pwd']),
			'real_name' => $param['real_name'],
			'status' => $param['status']
		);
		// var_dump($data);
		$ret = $this->model->addData($data);
		//判断添加结果，如果添加成功，则跳转到列表页面
		if($ret){
			$this->success('添加成功','employee/index');
		}else{
			$this->error('添加失败');
		}

		}
		
	}


	

	

	//员工修改页面
	public function edit(){
		if(request()->isGet()){
			$id = input('id/d',0);//接收id参数
			if(empty($id)){
				$this->error("参数错误");
			}
			//查询出该员工信息
			
			$info = $this->model->searchData(['id'=>$id],"*",1);

			// var_dump($info);
		
			//查询出所有部门信息
			$departs = model('depart')->searchData(['status'=>1]);
			//部门该员工职位信息
			$position = model('position')->searchData(['depart_id'=>$info['depart_id']]);
			//查询出所有的角色信息
			$roles = model('role')->searchData(['status'=>1]);

			//查询出本部门所有的用户(除了自己)
			$users = model('employee')->searchData(['id'=>['not in',$info['id']],'depart_id'=>$info['depart_id']],'id,real_name');

			// var_dump($users);

			// echo \think\Db::getLastSql();die;
			$this->assign('info',$info);
			$this->assign('departs',$departs);
			$this->assign('roles',$roles);
			$this->assign('position',$position);
			$this->assign('users',$users);
			return view();
	}elseif(request()->isPost()){
			$param = input('post.');

			var_dump($param);


			$id = $param['id'] ? $param['id'] : 0; 
			if(empty($id)){
				$this->error('缺少参数');
			}
			//员工表基础数据
			$data = array(
				'depart_id' => $param['depart_id'],
				'position_id' => $param['position_id'],
				'role_id' => $param['role_id'],
				'leader_id' => $param['leader_id'],//直属领导编号
				'user_code' => $param['user_code'],
				'real_name' => $param['real_name'],
				'status' => $param['status']
			);
			if($param['user_pwd']){
				$data['user_pwd'] = md5($param['user_pwd']);
			}
			$ret = $this->model->editData(['id'=>$id],$data);
			//判断添加结果，如果添加成功，则跳转到列表页面
			if($ret){
				$this->success('修改成功','employee/index');
			}else{
				$this->error('修改失败');
			}
		}

	}

	public function info(){

		if(request()->isGet()){
			//查询id为登录用户的信息
			$info = $this->model->searchData(['id'=>session('userinfo.id')],"id,user_code,real_name",1);
			//发送数据  
			$this->assign('info',$info);
			return view();

		}elseif(request()->isPost()){
			//获取post传递的参数
			$param = input('post.');

			//获取上传的图片
			$file = request()->file("img");
			if($file){
				//文件验证器 验证后缀名4中，最大大小1M 上传的路径为public/uploads
				$info = $file->validate(['ext'=>'jpg,png,gif,jpeg','size'=>1024*1024*1])->move(ROOT_PATH . 'public' ."/". 'uploads');
				if($info){
					//上传成功
					$param['user_img'] = '/uploads/'. $info->getSaveName();

					$param['user_img'] = str_replace("\\","/",$param['user_img']);
					// var_dump($param);

					//更新session中的图片信息
					session("userinfo.user_img",$param['user_img']);
					// die();
				}else{
					$this->error($file->getError());
				}
			}
			//如果修改密码  密码加密
			if($param['user_pwd']){
				$param['user_pwd'] = md5($param['user_pwd']);
			}else{
				unset($param['user_pwd']);
			}

			$ret = $this->model->editData(['id'=>session('userinfo.id')],$param);
			if($ret === false){
				$this->error("修改失败");
			}
			$this->success("修改成功");
		}
	}
	

	
	//员工删除操作
	public function delete(){
		$id = input('id');//接收部门编号参数
		
		$condition['id'] = $id;
		
		$ret = $this->model->del($condition);
		if($ret===false){
			$this->error('删除失败');
		}else{
			$this->success('删除成功','employee/index');
		}
	}

}
 ?>
