<?php /*a:2:{s:44:"D:\www\cms\app\install\view\index\step2.html";i:1584420601;s:39:"D:\www\cms\app\install\view\layout.html";i:1584420601;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>binghe安装程序 - Cms内容管理系统，最简明，易用，易定制，适用个人站长和企业网站等</title>

    <link href="/static/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/static/inspinia/css/animate.css" rel="stylesheet">
    <link href="/static/inspinia/css/style.css" rel="stylesheet">


    <link href="/static/layui/css/layui.css" rel="stylesheet">
</head>

<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            <div class="container">
                <nav class="navbar navbar-static-top" role="navigation">
                    <div class="navbar-header">
                        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                            <i class="fa fa-reorder"></i>
                        </button>
                        <a href="#" class="navbar-brand">binghe</a>
                    </div>
                    <div class="navbar-collapse collapse" id="navbar">
                        <!--
                        <ul class="nav navbar-nav">
                            <li class="active">
                                <a aria-expanded="false" role="button" href="layouts.html"> Back to main Layout page</a>
                            </li>
                            <li class="dropdown">
                                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                    <li><a href="">Menu item</a></li>
                                </ul>
                            </li>

                        </ul>
                        <ul class="nav navbar-top-links navbar-right">
                            <li>
                                <a href="login.html">
                                    <i class="fa fa-sign-out"></i> Log out
                                </a>
                            </li>
                        </ul>
                        -->
                    </div>
                </nav>
            </div>
        </div>

        <div class="wrapper wrapper-content">
            <div class="container">    <div class="row">        <div class="col-md-12">            <div class="ibox float-e-margins">                <div class="ibox-content">                    <div>                        <h3 class="font-bold no-margins">创建数据库</h3>                        <div class="hr-line-dashed"></div>                    </div>                    <br/>                    <form class="layui-form form-horizontal">                        <div class="form-group">                            <label class="col-sm-2 control-label">数据库类型</label>                            <div class="col-sm-9">                                <select name="db[type]" required lay-verify="required" class="form-control">                                    <option value="">请选择数据库数据库类型</option>                                    <option value="mysql">mysql</option>                                </select>                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">数据库地址</label>                            <div class="col-sm-9">                                <input type="text" name="db[hostname]" value="127.0.0.1" required lay-verify="required"  class="form-control" placeholder="数据库地址">                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">端口号</label>                            <div class="col-sm-9">                                <input type="text" name="db[hostport]" value="3306" required lay-verify="required"  class="form-control" placeholder="端口号">                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">数据库名</label>                            <div class="col-sm-9">                                <input type="text" name="db[database]" value="cms" required lay-verify="required"  class="form-control" placeholder="数据库名">                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">用户名</label>                            <div class="col-sm-9">                                <input type="text" name="db[username]" value="root" required lay-verify="required"  class="form-control" placeholder="用户名">                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">密码</label>                            <div class="col-sm-9">                                <input type="password" name="db[password]" value="" required lay-verify="required"  class="form-control" placeholder="密码">                            </div>                        </div>                        <div class="form-group">                            <label  class="col-sm-2 control-label">表前缀</label>                            <div class="col-sm-9">                                <input type="text" name="db[prefix]" value="cms_" required lay-verify="required"  class="form-control" placeholder="表前缀" readonly>                            </div>                        </div>                        <div class="form-group">                            <label class="col-sm-2 control-label"></label>                            <div class="col-sm-9">                                <a class="btn btn-white btn-outline btn-large" href="<?php echo url('install/Index/step1'); ?>">上一步</a>                                <button type="button" class="btn btn-primary btn-submit-install btn-fw" lay-submit lay-filter="app-install-submit">下一步</button>                            </div>                        </div>                    </form>                </div>            </div>        </div>    </div></div>

        </div>

        <div class="footer">
            <div class="pull-right">
                官网支持 <strong><a href="http://www.binghe.com" target="_blank">www.binghe.com</a></strong>
            </div>
            <div>
                <strong>Copyright</strong> binghe &copy; 2016-<?php echo date('Y'); ?>
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->
<script src="/static/common/js/jquery-2.1.1.min.js"></script>
<script src="/static/inspinia/js/bootstrap.min.js"></script>
<script src="/static/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/static/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/static/inspinia/js/inspinia.js"></script>

<script src="/static/layui/layui.js"></script>

<script>    layui.use(['form'], function(){        var $ = layui.$            ,form = layui.form;        //监听提交        form.on('submit(app-install-submit)', function(data){            var field = data.field;            //console.log(field)            //提交安装            $.ajax({                url:'<?php echo url('install/Index/step2'); ?>',                method:"POST",                data:field,                beforeSend: function() {                    layer.load(1);                },                complete: function() {                    layer.closeAll('loading');                },                success:function (res) {                    layer.msg(res.msg);                    if (res.code == 1){                        setTimeout(function () {                            location.href = '/'                        },1500)                    }                }            });        });    });</script>

</body>

</html>
