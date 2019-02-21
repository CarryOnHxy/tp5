<?php 
namespace app\index\validate;
use think\Validate;
class Position extends Validate{
	//验证规则
	protected $rule = array(
		'name' => 'require|max:10',
		'departid' => 'require',
		'status' => 'require'
	);
	//验证提示信息
	protected $message = array(
		'name.require' => '请填写职位名称',
		'name.max' => '职位名称不能超过10个字符',
		'departid' => '请选择所属部门',
		'status' => '请选择职位状态',
	);
}

 ?>