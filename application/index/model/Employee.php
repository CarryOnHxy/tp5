<?php 
namespace app\index\model;
use think\Db;
/**
 * 部门表数据库操作
 */
class Employee extends Base{
	protected $name = 'employee';//数据表名称

	//连表查询员工信息，关联部门信息和职位信息
	public function searchPage($where = array(),$fields="*",$num=10,$order="id DESC"){
		return Db::name($this->name)->alias('a')->join('tp_department b','a.depart_id = b.id','left')->join('tp_position c','a.position_id = c.id','left')->join('tp_roles d','a.role_id=d.id','left')->where($where)->field($fields)->paginate($num);
	}
} 
 ?>