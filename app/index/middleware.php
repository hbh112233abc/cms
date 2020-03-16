<?php
return [
    // 检查安装
    \app\common\middleware\Install::class,
    // 访问日志记录
    \app\common\middleware\Log::class,
    // cookie设置
    \app\common\middleware\From::class,
    // 模板选择
    \app\common\middleware\Theme::class,
];
