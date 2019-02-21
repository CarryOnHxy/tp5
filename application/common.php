<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


//根据数据生成层级关系
function getRuleLevel($arr,$pid = 0,$step = 0){

    static $tree=[];

    //遍历数组，查询某个数组成员的子级元素
    foreach($arr as $key=>$val) {

        if($val['pid'] == $pid) {

            $flg = str_repeat('┕━',$step);//字符串重复多少次

            $val['rule_name'] = $flg.$val['rule_name'];

            $tree[] = $val;

            getRuleLevel($arr,$val['id'] ,$step+1);//调用自身函数，同时传递参数
        }
    }
    return $tree;
}


//无限极分类--生成权限树形结构
function getRuleTree($arr,$pid = 0){

    $tree = [];

    foreach($arr as $key => $val){

        if($val['pid'] == $pid){

            $val['child'] = getRuleTree($arr,$val['id']);//调用自身函数，查找当前权限的所有子级权限
           
            $tree[] = $val;
        }
    }
    return $tree;
}

