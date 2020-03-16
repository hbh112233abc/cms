<?php

declare(strict_types=1);

namespace app\common\middleware;

use think\facade\Cookie;

class From
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
        if (!Cookie::has('from_referee') && !Cookie::has('entrance_url')) {
            $fromReferee = $request->server('HTTP_REFERER');
            Cookie::set('from_referee', $fromReferee, 0);
            $fromUrl = $request->url(true);
            Cookie::set('entrance_url', $fromUrl, 0);
        }
        return $next($request);
    }
}
