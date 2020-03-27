<?php

namespace app\admin\controller;

use app\common\model\AuthRuleModel;
use app\common\model\UserModel;
use think\facade\Cache;
use think\facade\View;

/**
 * 基础控制器
 */
class Base extends \app\BaseController
{
    protected $uid;

    public function initialize()
    {
        Cache::set('hbh', 111);
        //判断登陆session('uid')
        $uid = session('uid');
        if (!$uid) {
            if (request()->isAjax()) {
                return $this->error('请重新登陆', url('index/Sign/index'));
            }
            \think\facade\Log::error('uid not found at session');
            return $this->redirect('/admin/Sign/index', 302, ['redirect' => urlencode($this->request->url(true))]);
        }
        $this->uid = $uid;

        //实现用户单个端登录，方法: 通过判断cookie和服务器cache的login_hash值
        $localLoginHash = cookie($uid . '_login_hash');
        $cacheLoginHash = Cache::get($uid . '_login_hash');

        if ($localLoginHash != $cacheLoginHash) {
            if (request()->isAjax()) {
                return $this->error('请重新登陆', url('admin/Sign/index'));
            } else {
                \think\facade\Log::error('login hash not same[' . $localLoginHash . ']<>[' . $cacheLoginHash . ']');
                return $this->redirect('/admin/Sign/index', 302, ['redirect' => urlencode($this->request->url(true))]);
            }
        }

        //用户有请求操作时，session时间重置
        $expire = config('session.expire'); //缓存期限
        session('uid', $uid);
        cookie('uid', $uid, $expire);

        //权限验证
        if (config('cms.auth_on') == 'on') {
            $node = app('http')->getName() . '/' . request()->controller() . '/' . request()->action();

            $auth = new \liliuwei\think\Auth();
            if (!$auth->check($node, $uid)) {
                return $this->error('没有访问权限', 'javascript:void(0);');
            }
        }

        //用户信息
        $myself = UserModel::find($uid);
        View::assign('myself', $myself);

        //菜单数据,Cache::tag不支持redis
        if (Cache::has($uid . '_menu')) {
            $menus = Cache::get($uid . '_menu');
        } else {
            $AuthRuleModel = new AuthRuleModel();
            $menus         = $AuthRuleModel->getTreeDataBelongto('level', 'sort, id', 'name', 'id', 'pid', 'admin');
            Cache::set($uid . '_menu', $menus);
        }

        View::assign('menus', $menus);
    }
}
