<?php

/**
 * Created by PhpStorm.
 * User: cattong
 * Date: 2018-03-16
 * Time: 12:31
 */

namespace app\index\controller;


class Base extends \app\BaseController
{

    public function initialize()
    {
        parent::initialize();
        if (!session('uid') && !session('visitor')) {
            $ip = request()->ip(0, true);
            $visitor = '游客-' . ip_to_address($ip, 'province,city');
            session('visitor', $visitor);
        }
    }
}
