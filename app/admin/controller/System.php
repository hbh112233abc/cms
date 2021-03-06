<?php

namespace app\admin\controller;

use app\common\model\ActionLogModel;
use app\common\model\ConfigModel;
use app\common\model\LinksModel;
use think\Controller;
use think\facade\Cache;
use think\facade\View;

/**
 * 系统设置控制器
 */
class System extends Base
{
    //系统设置
    public function index()
    {
        $tab = input('get.tab', 'base');
        View::assign('tab', $tab);

        if (request()->isAjax()) {
            $data = input('param.');
            unset($data['s']); //路由地址参数不做处理；

            $ConfigModel = new ConfigModel();
            foreach ($data as $k => $v) {
                $config = ConfigModel::where('name', $k)->findOrEmpty();
                if (!$config->isEmpty()) {
                    $ConfigModel->where('name', $k)->update(['value' => $v]);
                } else {
                    $ConfigModel->save(['name' => $k, 'value' => $v]);
                }
            }
            cache('config', null);
            return $this->success('设置成功');
        }

        //获取标签组
        $ConfigModel = new ConfigModel();
        $tabMeta     = $ConfigModel->where('name', 'tab_meta')->value('value');
        $tabs        = json_decode($tabMeta, true);
        View::assign('tabs', $tabs);

        $configs = $ConfigModel->where('tab', '=', $tab)->order('sort asc')->select();
        View::assign('configs', $configs);

        return View::fetch('index');
    }

    //ueditor设置
    public function ueditor()
    {
        return view();
    }

    //联系信息
    public function contact()
    {
        if (request()->isAjax()) {
            $data        = input('post.');
            $ConfigModel = new ConfigModel();
            foreach ($data as $k => $v) {
                $ConfigModel->where('name', $k)->setField('value', $v);
            }
            cache('config', null);
            return $this->success('设置成功');
        }

        return view();
    }

    //邮箱设置
    public function email()
    {
        if (request()->isAjax()) {
            $data        = input('post.');
            $ConfigModel = new ConfigModel();
            foreach ($data as $k => $v) {
                $ConfigModel->where('name', $k)->setField('value', $v);
            }
            cache('config', null);
            return $this->success('设置成功');
        }

        return view();
    }

    //SEO设置
    public function seo()
    {
        if (request()->isAjax()) {
            $data        = input('post.');
            $ConfigModel = new ConfigModel();
            foreach ($data as $k => $v) {
                $ConfigModel->where('name', $k)->setField('value', $v);
            }
            cache('config', null);
            return $this->success('设置成功');
        }

        return view();
    }

    //清理缓存
    public function clearCache()
    {
        if (request()->isPost()) {
            $runtimePath = public_path('runtime');
            $dir         = new \youyi\util\Dir($runtimePath);
            $types       = input('types');
            if (count($types)) {
                foreach ($types as $k => $v) {
                    switch ($v) {
                        case 'temp':
                            runtime_clear('temp');
                            break;
                        case 'data':
                            runtime_clear('cache');
                            Cache::clear();
                            break;
                        case 'log':
                            runtime_clear('log');
                            break;
                        case 'vars':
                            //删除自定义的缓存，已经的缓存变量
                            Cache::delete('menu_' . session('uid'));
                            Cache::delete('config');
                            break;
                        default:
                            break;
                    }
                }
            }

            return $this->success("清除缓存成功！");
        }

        return view('clearCache');
    }

    //广告位设置
    public function ad()
    {
        if (request()->isAjax()) {
        }

        return view();
    }

    //友情链接设置
    public function links()
    {
        $LinksModel = new LinksModel();
        $list       = $LinksModel->order('sort')->select();
        View::assign('list', $list);
        return view();
    }

    //新增友链
    public function addLinks()
    {
        $data       = input('post.');
        $LinksModel = new LinksModel();
        $res        = $LinksModel->insert($data);
        if ($res) {
            cache('links', null);
            return $this->success('添加成功');
        } else {
            return $this->error('添加失败');
        }
    }

    //编辑友链
    public function editLinks()
    {
        $data = input('post.');

        $res = LinksModel::update($data);
        if ($res) {
            cache('links', null);
            return $this->success('修改成功');
        } else {
            return $this->error('修改失败');
        }
    }

    //排序友链
    public function orderLinks()
    {
        $data       = input('post.');
        $LinksModel = new LinksModel();
        foreach ($data as $k => $v) {
            $LinksModel->where('id', $k)->setField('sort', $v);
        }
        cache('links', null);
        return $this->success('成功排序');
    }

    //删除友链
    public function deleteLinks()
    {
        $id         = input('param.id/d', 0);
        $LinksModel = new LinksModel();
        $res        = $LinksModel->where('id', $id)->delete();
        if ($res) {
            cache('links', null);
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    //日志审计
    public function actionLogs()
    {
        $ActionLogModel = new ActionLogModel();

        $key    = input('param.key');
        $action = input('param.action', '');

        $startTime = input('param.startTime');
        $endTime   = input('param.endTime');
        if (!(isset($startTime) && isset($endTime))) {
            $startTime = date('Y-m-d', strtotime('-31 day'));
            $endTime   = date('Y-m-d');
        }

        $startDatetime = date('Y-m-d 00:00:00', strtotime($startTime));
        $endDatetime   = date('Y-m-d 23:59:59', strtotime($endTime));

        $where = [
            ['remark', 'like', "%{$key}%"],
            ['action', '=', $action],
            ['create_time', 'between', [$startDatetime, $endDatetime]],
        ];
        if ($action == '' && $key == '') {
            $where = [
                ['create_time', 'between', [$startDatetime, $endDatetime]],
            ];
        } else if ($action == '') {
            $where = [
                ['remark', 'like', "%{$key}%"],
                ['create_time', 'between', [$startDatetime, $endDatetime]],
            ];
        }

        $fields     = 'id, user_id, action, module, ip, remark, data, create_time';
        $pageConfig = [
            'type'  => '\\app\\common\\paginator\\BootstrapTable',
            'query' => input('param.'),
        ];
        $listRow = input('param.list_rows/d') ? input('param.list_rows/d') : 20;

        $list           = $ActionLogModel->where($where)->field($fields)->order('id desc')->paginate($listRow, false, $pageConfig);
        $startTimestamp = strtotime($startTime);
        $endTimestamp   = strtotime($endTime);

        View::assign('startTime', $startTime);
        View::assign('endTime', $endTime);
        View::assign('startTimestamp', $startTimestamp);
        View::assign('endTimestamp', $endTimestamp);
        View::assign('list', $list);
        View::assign('pages', $list->render());

        return View::fetch('actionLogs');
    }
}
