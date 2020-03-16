<?php

namespace app\index\controller;

/**
 * 空控制器,空操作
 * 'empty_controller'       => 'Error',
 */
class Error
{
    public function __call($name, $arguments)
    {
        return view('public/404');
    }
}
