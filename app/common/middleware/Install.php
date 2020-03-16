<?php

declare(strict_types=1);

namespace app\common\middleware;

class Install
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
        if (!file_exists(root_path() . '/data/install.lock')) {
            return redirect(url('install/Index/index')->build());
        }
        return $next($request);
    }
}
