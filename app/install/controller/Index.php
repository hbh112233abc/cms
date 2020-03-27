<?php

namespace app\install\controller;

use app\install\logic\InstallLogic;
use think\facade\View;

/**
 * 安装控制器
 */
class Index extends \app\BaseController
{

    public function initialize()
    {
        parent::initialize();

        $this->checkInstall();
    }

    /**
     * 检查是否已安装
     * @author cattong
     *
     */
    public function checkInstall()
    {
        $installLockFile = root_path() . 'data' . DIRECTORY_SEPARATOR . 'install.lock';
        if (file_exists($installLockFile)) {
            return $this->error('已经成功安装，请勿重复安装!', '/');
        }
    }

    /**
     * 安装引导首页
     *
     * @author cattong
     *
     * @return mixed
     */
    public function index()
    {
        View::assign('dirfile', check_dir_chmod());
        View::assign('env', check_env());
        View::assign('func', check_func());

        return View::fetch('index');
    }

    /**
     * 站点数据写入
     *
     * @author cattong
     *
     * @param null $site
     * @param null $admin
     *
     * @return array|mixed
     */
    public function step1($site = null, $admin = null)
    {

        if ($this->request->isPost()) {
            $installLogic = new InstallLogic();
            $check        = $installLogic->checkSiteConfig($site, $admin);
            if ($check !== true) {
                return $this->error($installLogic->getError());
            }

            return $this->success('保存数据成功');
        }

        return View::fetch('step1');
    }

    /**
     * 安装数据写入
     *
     * @author cattong
     * @param null $db
     * @return array|bool|mixed
     */
    public function step2($db = null)
    {

        if ($this->request->isPost()) {
            $installLogic = new InstallLogic();

            // 检查安装数据
            $check = $installLogic->checkDbConfig($db);
            if ($check !== true) {
                return $this->error($installLogic->getError());
            }

            //检查是否安装过,确保安全
            $check = $installLogic->checkInstalled($db);
            if ($check === true) {
                return $this->error($installLogic->getError());
            }

            // 开始安装
            $check = $installLogic->install();
            if ($check === true) {
                return $this->success('安装成功');
            } else {
                return $this->error($installLogic->getError());
            }
        }

        return View::fetch('step2');
    }
}
