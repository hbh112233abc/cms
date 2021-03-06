<?php

namespace app\common\controller;

/**
 * Trait后台管理 插件组件
 * 使用方法：use \app\common\controller\AddonAdmin;
 * @package app\common\controller
 */
trait AddonAdmin
{
    /**
     * 检测session和auth统一入口
     */
    protected function check()
    {
        $this->checkSession();

        $this->checkAuth();
    }

    /**
     * 检测登录session
     */
    protected function checkSession()
    {
        //判断登陆session('uid')
        $uid = session('uid');
        if (!$uid) {
            if (request()->isAjax()) {
                return $this->error('请重新登陆', url('admin/Sign/index'));
            }
            return $this->redirect('admin/Sign/index');
        }

        //实现用户单个端登录，方法: 通过判断cookie和服务器cache的login_hash值
        $localLoginHash = cookie($uid . '_login_hash');
        $cacheLoginHash = cache($uid . '_login_hash');
        if ($localLoginHash != $cacheLoginHash) {
            return $this->error('请重新登陆', url('admin/Sign/index'));
        }

        //用户有请求操作时，session时间重置
        $expire = config('session.expire'); //缓存期限
        session('uid', $uid);
        cookie('uid', $uid, $expire);
    }

    /**
     * 检测权限
     */
    protected function checkAuth()
    {
        $uid = session('uid');
        //权限验证
        if (config('cms.auth_on') == 'on') {
            $node = request()->module() . '/' . request()->controller() . '/' . request()->action();

            $auth = new \liliuwei\think\Auth;
            if (!$auth->check($node, $uid)) {
                return $this->error('没有访问权限', 'javascript:void(0);');
            }
        }
    }
}
