<?php /*a:4:{s:52:"D:\www\cms\public\theme\classic\tpl\index\index.html";i:1584420601;s:52:"D:\www\cms\public\theme\classic\tpl\public\base.html";i:1584420601;s:54:"D:\www\cms\public\theme\classic\tpl\public\header.html";i:1584420601;s:54:"D:\www\cms\public\theme\classic\tpl\public\footer.html";i:1584420601;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title><?php echo get_config('title'); ?> - <?php echo get_config('site_name'); ?></title>
    <meta name="keywords" content="<?php echo get_config('keywords'); ?>">
    <meta name="description" content="<?php echo get_config('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" type="text/css" href="/static/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/theme/classic/static/css/main.css">
    <link rel="shortcut icon" href="/theme/classic/static/favicon.ico">
     
    <!--加载meta IE兼容文件-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="lay-blog">
    <div class="header">
    <div class="menu-btn">
        <div class="menu"></div>
    </div>
    <h1 class="logo">
        <a href="<?php echo url('index/index/index'); ?>">
            <span>MYBLOG</span>
            <img src="/theme/classic/static/img/logo.png">
        </a>
    </h1>

    <div class="nav">
        <a href="<?php echo url('index/index/index'); ?>" <?php if(request()->controller() == 'Index' && request()->action() == 'index'): ?> class="active" <?php endif; ?>>首页</a>
        <a href="<?php echo url('index/Article/index'); ?>">微语</a>
        <?php   $_cid_mK08Rr = 0;   $_cname_o9OUNu = "";  $_list_hGCn31 = [];  if (empty($_cid_mK08Rr) && !empty($_cname_o9OUNu)) {    $internalCategory = \app\common\model\CategoryModel::where(['title_en'=>$_cname_o9OUNu])->find();    if (!empty($internalCategory)) { $_cid_mK08Rr = $internalCategory['id'];}  }  $cacheMark = 'categorys_' . 0 . $_cid_mK08Rr . 2;  $where = [];  $where[] = ['status' , '=', \app\common\model\CategoryModel::STATUS_ONLINE];  $where[] = ['pid' , '=', $_cid_mK08Rr];  if (0) {     $_list_hGCn31 = cache($cacheMark);   }   if (empty($_list_hGCn31)) {     $CategoryModel = new \app\common\model\CategoryModel();    $_list_hGCn31 = $CategoryModel->where($where)->order('sort asc,id asc')->limit(2)->select();    if (0) {      cache($cacheMark, $_list_hGCn31, 0);    }  }   $oee7KTQj35 = $_list_hGCn31;  if(is_array($_list_hGCn31) || $_list_hGCn31 instanceof \think\Collection || $_list_hGCn31 instanceof \think\Paginator): $i = 0; $__LIST__ = $_list_hGCn31;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?> 
        <a href="<?php echo url('index/Article/articleList',['cname'=>$vo['title_en']]); ?>"><?php echo htmlentities($vo['title_cn']); ?></a>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        <a href="<?php echo url('index/Feedback/index'); ?>" <?php if(request()->controller() == 'Feedback' && request()->action() == 'index'): ?> class="active" <?php endif; ?>>留言</a>
        <a href="<?php echo url('index/Index/about'); ?>" <?php if(request()->controller() == 'Index' && request()->action() == 'about'): ?> class="active" <?php endif; ?>>关于</a>
    </div>
    <ul class="layui-nav header-down-nav">
        <li class="layui-nav-item"><a href="<?php echo url('index/index/index'); ?>" >文章</a></li>
        <li class="layui-nav-item"><a href="<?php echo url('index/Article/index'); ?>">微语</a></li>
        <li class="layui-nav-item"><a href="#">相册</a></li>
        <li class="layui-nav-item"><a href="<?php echo url('index/Feedback/index'); ?>" >留言</a></li>
        <li class="layui-nav-item"><a href="<?php echo url('index/Index/about'); ?>">关于</a></li>
    </ul>
    <p class="welcome-text">
        <?php echo get_config('site_name'); ?>
    </p>
</div>

    
<div class="banner">

    <div class="cont w1000">
        <div class="title">
            <h3><?php echo get_config('site_name'); ?></h3>
            <h4>well-balanced heart</h4>
        </div>
        <div class="amount">
            <p><span class="text">访问量</span><span class="access">1000</span></p>
            <p><span class="text">日志</span><span class="daily-record">1000</span></p>
        </div>
    </div>
</div>

<div class="content">
    <div class="cont w1000">
        <div class="title">
        <span class="layui-breadcrumb" lay-separator="|">
          <a href="<?php echo url('index/index/index'); ?>" <?php if(request()->controller() == 'Index' && request()->action() == 'index'): ?> class="active"<?php endif; ?>>所有文章</a>
          <?php   $_cid_sqRhSp = 0;   $_cname_ytdEtC = "";  $_list_KWYxCQ = [];  if (empty($_cid_sqRhSp) && !empty($_cname_ytdEtC)) {    $internalCategory = \app\common\model\CategoryModel::where(['title_en'=>$_cname_ytdEtC])->find();    if (!empty($internalCategory)) { $_cid_sqRhSp = $internalCategory['id'];}  }  $cacheMark = 'categorys_' . 120 . $_cid_sqRhSp . 0;  $where = [];  $where[] = ['status' , '=', \app\common\model\CategoryModel::STATUS_ONLINE];  $where[] = ['pid' , '=', $_cid_sqRhSp];  if (120) {     $_list_KWYxCQ = cache($cacheMark);   }   if (empty($_list_KWYxCQ)) {     $CategoryModel = new \app\common\model\CategoryModel();    $_list_KWYxCQ = $CategoryModel->where($where)->order('sort asc,id asc')->limit(0)->select();    if (120) {      cache($cacheMark, $_list_KWYxCQ, 120);    }  }   $HSrKJ6TYMb = $_list_KWYxCQ;  if(is_array($_list_KWYxCQ) || $_list_KWYxCQ instanceof \think\Collection || $_list_KWYxCQ instanceof \think\Paginator): $i = 0; $__LIST__ = $_list_KWYxCQ;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?> 
          <a href="<?php echo url('index/Article/articleList', ['cid'=>$vo['id']]); ?>"
             <?php if(request()->controller() == 'Article' && request()->action() == 'articlelist' && $vo['id'] == $cid): ?> class="active"<?php endif; ?>>
            <?php echo htmlentities($vo['title_cn']); ?></a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </span>
        </div>

        <?php   $page = input('page/d', 1);   $_cid_PEjOSC2XOl = 0;   $_cname_LMbHSWBPtk = "";  $_list_n7pU9Vap3g = [];  if (empty($_cid_PEjOSC2XOl) && !empty($_cname_LMbHSWBPtk)) {    $category = \app\common\model\CategoryModel::where(['title_en'=>$_cname_LMbHSWBPtk])->find();    if (!empty($category)) { $_cid_PEjOSC2XOl = $category['id'];} else { $_cid_PEjOSC2XOl = -1;}  }  $cacheMark = 'index_category_' . $_cid_PEjOSC2XOl . '_' . 10 . '_' . $page;  $where = [];  $where[] = ['status', '=', \app\common\model\ArticleModel::STATUS_PUBLISHED];  $targetFields = 'id,title,description,author,thumb_image_id,post_time,read_count,comment_count';  if (120) {     $_list_n7pU9Vap3g = cache($cacheMark);   }   if (empty($_list_n7pU9Vap3g)) {     if ($_cid_PEjOSC2XOl) {       $childs = \app\common\model\CategoryModel::getChild($_cid_PEjOSC2XOl);      $cids = $childs['ids'];      array_push($cids, $_cid_PEjOSC2XOl);      $_list_n7pU9Vap3g = \app\common\model\ArticleModel::hasWhere('CategoryArticle', [['category_id','in',$cids]])->where($where)->field($targetFields)->order('is_top desc,sort,post_time desc')->paginate(10,false,['query'=>input('param.')]);    } else {       $ArticleModel = new \app\common\model\ArticleModel();      $_list_n7pU9Vap3g = $ArticleModel->where($where)->field($targetFields)->order('is_top desc,sort,post_time desc')->paginate(10,false,['query'=>input('param.')]);    }     if (120) {      cache($cacheMark, $_list_n7pU9Vap3g, 120);    }  }   $list = $_list_n7pU9Vap3g;  if(is_array($_list_n7pU9Vap3g) || $_list_n7pU9Vap3g instanceof \think\Collection || $_list_n7pU9Vap3g instanceof \think\Paginator): $i = 0; $__LIST__ = $_list_n7pU9Vap3g;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$art): $mod = ($i % 2 );++$i;?>
        <div class="list-item">
            <div class="item">
                <div class="layui-fluid">
                    <div class="layui-row">
                        <div class="layui-col-xs12 layui-col-sm4 layui-col-md5">
                            <div class="img"><img src="/theme/classic/static/img/sy_img1.jpg" alt=""></div>
                        </div>
                        <div class="layui-col-xs12 layui-col-sm8 layui-col-md7">
                            <div class="item-cont">
                                <h3><a href="<?php echo url('index/Article/viewArticle',['aid' => $art['id']]); ?>"><?php echo htmlentities($art['title']); ?></a></h3>
                                <h5>发布于：<span> <?php echo htmlentities(date('Y-m-d H:i',!is_numeric($art['post_time'])? strtotime($art['post_time']) : $art['post_time'])); ?></span></h5>
                                <p><article style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">
                                    <?php echo $art['description']; ?>
                                </article></p>
                                <a href="<?php echo url('index/Article/viewArticle',['aid' => $art['id']]); ?>" class="go-icon"></a>
                                <div class="comment count" style="font-size: 20px;margin: 0px 6px;background: transparent;color: #0d8ddb">
                                    <a style="width: 360px; height: 56px" href="<?php echo url('index/Article/viewArticle',['aid' => $art['id']]); ?> ">评论</a>
                                    <a style="margin: 0 50px; width: 360px; height: 56px" href="javascript:;" class="like">点赞</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        <div style="text-align: center; padding:30px 0">
            <?php echo $list->render(); ?>
        </div>

    </div>
</div>



    <div class="footer-wrap">
    <div class="footer w1000">
        <div class="qrcode">
            <img src="/theme/classic/static/img/erweima.jpg">
        </div>
        <div class="practice-mode">
            <img src="/theme/classic/static/img/down_img.jpg">
            <div class="text">
                <h4 class="title">我的联系方式</h4>
                <p>微信<span class="WeChat"><?php echo get_config('weixin'); ?></span></p>
                <p>手机<span class="iphone"><?php echo get_config('qq'); ?></span></p>
                <p>邮箱<span class="email"><?php echo get_config('email'); ?></span></p>
            </div>
        </div>
    </div>
</div>

    <?php echo get_config('stat_code'); ?>

    <script src="/static/inspinia/js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="/static/layui/layui.js"></script>
    

</body>
</html>