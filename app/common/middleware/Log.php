<?php

declare(strict_types=1);

namespace app\common\middleware;

class Log
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
        $baseUrl = $request->baseUrl();
        $ip = $request->ip();

        $datetime = "[" . date_time() . ' ' . (millisecond() % 1000) . "] ";
        $params = $request->param();
        //不输出登陆密码
        if (isset($params['password'])) {
            $params['password'] = '********';
        }
        \think\facade\Log::info($datetime . 'ip=' . $ip . '|path=' . $baseUrl . '|params=' . json_encode($params));
        return $next($request);
    }
}
