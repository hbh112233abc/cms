<?php

declare(strict_types=1);

namespace app\common\middleware;

use app\common\model\ActionLogModel;
use app\common\logic\ActionLogLogic;

class Spider
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $params = $request->param();
        if (isset($params['password'])) {
            $params['password'] = '********';
        }

        $userAgent = $request->header('user-agent');
        if (strlen($userAgent) > 255) {
            $userAgent = substr($userAgent, 0, 255);
        }

        //登录日志
        $actionLog = new ActionLogLogic();
        $actionLog->addLog(0, ActionLogModel::ACTION_ACCESS, $userAgent, $params);

        return $next($request);
    }
}
