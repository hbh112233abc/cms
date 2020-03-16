<?php

namespace app\index\controller;

use think\facade\View;

class Index extends Base
{
    /**
     * 首页
     * @return \think\response\View
     */
    public function index()
    {
        return View::fetch('index');
    }

    /**
     * 业务介绍|解决方案
     * @return \think\response\View
     */
    public function business()
    {
        return View::fetch('business');
    }

    /**
     * 合作伙伴|合作案例
     * @return \think\response\View
     */
    public function partner()
    {
        return View::fetch('partner');
    }

    /**
     * 关于我们|自我介绍
     * @return \think\response\View
     */
    public function about()
    {
        return View::fetch('about');
    }

    /**
     * 联系我们|联系方式
     * @return \think\response\View
     */
    public function contact()
    {
        return View::fetch('contact');
    }

    /**
     * 加入我们|岗位招聘
     * @return \think\response\View
     */
    public function jobs()
    {
        return View::fetch('jobs');
    }
}
