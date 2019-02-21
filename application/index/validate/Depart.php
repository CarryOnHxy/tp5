<?php 
namespace app\index\validate;
use think\Validate;
/**
 * 部门验证器
 */
class Depart extends Validate{
	//验证规则
	protected $rule = array(
		'depart_name' => 'require|max:10',
		'status' => 'require'
	);
	//验证提示信息
	protected $message = array(
		'depart_name.require' => '请填写部门名称',
		'depart_name.max' => '部门名称不能超过10个字符',
		'status' => '请选择部门状态'
	);
}
 ?>
