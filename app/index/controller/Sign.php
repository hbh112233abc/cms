<?php


namespace app\index\controller;


class Sign extends \app\common\controller\Sign
{
    public function initialize()
    {
        parent::initialize();

        $config = [
            'login_success_view' => url('index/Member/overview'),
            'logout_success_view' => url('index/Index/index'),
            'register_enable' => true,
            'register_code_type' => 'mail',
            'reset_enable' => true,
            'reset_code_type' => 'mail',
        ];

        $this->defaultConfig = array_merge($this->defaultConfig, $config);

        $this->view->engine->layout(false);
    }
}
