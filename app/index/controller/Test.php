<?php

/**
 * Created by PhpStorm.
 * User: cattong
 * Date: 2018-06-12
 * Time: 11:02
 */

namespace app\index\controller;

/**
 * 用于平台测试使用
 * @package app\index\controller
 */
class Test extends Base
{
    //addons: test 测试
    public function testAddons()
    {
        //勾子方法调用测试
        //hook('demo', ['id'=>1]);

        return view('testAddons');
    }

    //标签 <cms/> <article> 测试
    public function testTags()
    {
        View::assign('demo_time', time());

        return view('testTags');
    }
}
