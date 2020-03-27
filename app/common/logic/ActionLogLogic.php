<?php

/**
 * Created by PhpStorm.
 * User: cattong
 * Date: 2018-05-29
 * Time: 15:02
 */

namespace app\common\logic;

use app\common\model\ActionLogModel;

class ActionLogLogic
{
    //增加日志，data为传递的参数
    public function addLog($userId, $action, $remark, $data = [])
    {
        if (isset($data['password'])) {
            unset($data['password']);
        }

        $data = [
            'user_id' => $userId,
            'action'  => $action,
            'module'  => app('http')->getName(),
            'ip'      => request()->ip(0, true),
            'remark'  => $remark,
            'data'    => substr(json_encode($data), 0, 128),
        ];

        $ActionLogModel = new ActionLogModel();
        $result         = $ActionLogModel->save($data);

        return $result;
    }
}
