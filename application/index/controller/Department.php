<?php
namespace app\index\controller;

use think\Controller;

use think\Db;

use  think\Loader;

use app\index\model\Depart;


class Department extends Base{
/**
 * 部门管理
 * 部门的添加、修改、删除、列表
 */
    protected $model;

    public  function _initialize(){
        parent::_initialize();
        $this->model = model("Depart");

    }



    public function index()
    {
       
       //查询所有部门的信息分页
    	// $list= Db::name("department")->
    	// paginate(3);

       $list=  $this->model->searchPage(['status'=>'1'],"*","3");
    	$this->assign("list",$list);

    	// var_dump($list);
    	return view();


    }

    //显示添加的页面

    public function  add(){
        if(request()->isGet()){
            return view();
        }elseif(request()->isPost()){
            $param = input('post.');

        $validate = Loader::validate('Depart');

        // die();
        // 调用验证器的验证方法
        if(!$validate->check($param)){
            $this->error($validate->getError());
        }

        //部门基础数据

        // $res = Db::name("department")->insert($param);
        $res = $this->model->addData($param);

        //判断是否添加成功
        if($res){
            $this->success("添加部门成功",'department/index');
        }else{
            $this->error("添加失败");
        }


        }
    	
    }


  

    //显示编辑页面
    //查询单条数据 并发送
    public function edit(){
        if(request()->isGet()){

              //接收参数
            $id = input("id");  
            if(empty($id)){
                $this->error("缺少参数");
            }

            //数据库查询操作 
            // $info = Db::name("department")->find($id);
            $info = $this->model->searchData(['id'=>$id],"*",1);

            // var_dump($info);

            // die();
            $this->assign("info",$info);
            return view();

        }elseif(request()->isPost()){

            $param = input('post.');

        //自动验证
        $validate = Loader::validate("Depart");

        //调用验证器的验证方法
        if(!$validate->check($param)){
            $this->error($validate->getError());
        }

        $data = array(
            'depart_name'=>$param['depart_name'],
            'status'=>$param['status']
        );

        // $res = Db::name("department")->where("id","=",$param['id'])->update($data);
        $res = $this->model->editData(['id'=>$param['id']],$data);

        echo Db::getLastSql();

        //判断结果 
        if($res){
            $this->success('修改成功','department/index');
            
        }else{
            $this->error('修改失败');
        }
        }
  



    }

    
    //执行删除页面

    public function delete(){

    	$id = input("id");

    	// $res = Db::name("department")->delete($id);
        $res = $this->model->del(['id'=>$id]);

    	var_dump($res);
    	if($res){
    		$this->success("删除成功","department/index");	
    	}else{
    		$this->error("删除失败");
    	}
    }


}
