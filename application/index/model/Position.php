<?php 
namespace app\index\model;
use think\Db;
class Position extends Base{
	//指定数据表的名字
	protected $name = 'position';

	//连表查询职位和部门信息
	public function searchPage($where = array(),$fields="*",$num=10){
		return Db::name($this->name)->alias('a')->join('tp_department b','a.depart_id = b.id','left')->where($where)->field($fields)->paginate($num);
	}
}

 ?>