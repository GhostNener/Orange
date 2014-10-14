<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>时讯XXX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/bigorange/Public/css/bootstrap-theme.css" />
    <link rel="stylesheet" href="/bigorange/Public/css/bootstrap.css" />
    <link rel="stylesheet" href="/bigorange/Public/css/huaxi_css.css" />
    <link rel="shortcut icon" href="/bigorange/Public/Img/favico.png" type="image/x-icon" />
    <script src="/bigorange/Public/js/jquery-1.8.0.min.js"></script>
    <script src="/bigorange/Public/js/bootstrap.js"></script>
</head>

<body>
    <!--顶-->
    <div id="wrap">
        <!-- 顶栏-->
        <div id="top_bar" class="container-fluid top_bar">
            <div class=" container">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 ">
                        <ul class="top_bar_ul navbar-right">
                            <li>
                                <a href="">
                                    <span class="glyphicon glyphicon-bell"></span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="glyphicon glyphicon-cog"></span>
                                </a>
                            </li>
                            <li>&nbsp;</li>
                            <!--分割线-->
                            <li class="top_bar_spiter"></li>
                            <!--顶栏下拉菜单-->
                            <li class="dropdown">
                                <a role="button" class="dropdown-toggle dropdown-toggle-avatar" data-toggle="dropdown">
                                    admin&nbsp; <i style="font-size: 10px" class="glyphicon glyphicon-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu text-left" role="menu">
                                    <li>
                                        <a href="">
                                            <span class="glyphicon glyphicon-user"></span>
                                            &nbsp;&nbsp;个人主页
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <span class="glyphicon glyphicon-star"></span>
                                            &nbsp;&nbsp;我的收藏
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" target="_blank">
                                            <span class="glyphicon glyphicon-dashboard"></span>
                                            &nbsp;&nbsp;管理后台
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <span class="glyphicon glyphicon-off"></span>
                                            &nbsp;&nbsp;注销
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--顶栏下拉菜单结束-->
                            <!--分割线-->
                            <li class="top_bar_spiter"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 图片 搜索框-->
        <div id="logo_bar" class="logo_bar container-fluid">
            <div class="container">
                <div class="row logo">
                    <!--Logo-->
                    <div class="col-md-9 logo_img">
                        <a href="">
                            <h2>花溪大学城服务站</h2>
                        </a>
                    </div>
                    <!--搜索框-->
                    <div class="col-md-3 logo_submit">
                        <div class="pull-right text-right" style="padding-top:4px;">
                            <form action="" class="navbar-form navbar-right search_bar" role="search" id="forum_search" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <!--搜索框-->
                                        <input id="seachkeywords" class="form-control" name="keywords" placeholder="查找" type="text">
                                        <div class="input-group-btn text-left">
                                            <!--搜索按钮-->
                                            <a id="seachbutton" type="button" class="btn btn-seach">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 导航-->
        <div id="nav_bar" class="nav_bar">
            <nav class="navbar navbar-default huaxinav" role="navigation">
                <div class="container">
                    <ul id="nav_ul" class="nav navbar-nav ">
                        <li class="active_nav">
                            <a href="" target="_self">首页</a>
                        </li>
                        <li class="">
                            <a href="" target="_self">时讯</a>
                        </li>
                        <li class="">
                            <a href="forum.html" target="_self">论坛</a>
                        </li>
                        <li class="">
                            <a href="" target="_self">商城</a>
                        </li>
                        <li class="">
                            <a href="" target="_self">活动</a>
                        </li>
                        <!--导航下拉菜单-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                其他
                                <span class="caret"></span>
                            </a>
                            <!--下拉菜单选项-->
                            <ul id="dropdown_ls" class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#">哈哈</a>
                                </li>
                                <li>
                                    <a href="#">木有了</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- 内容-->
        <div id="mian" class="main">
            <!-- 二级导航-->
            <div class="container ">
                <nav id="sub_navbar" class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-7">
                                <span class="sr-only">二级导航</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <span class="navbar-brand">资讯</span>
                        </div>
                        <div class="collapse navbar-collapse " id="sub_navbar_main">
                            <ul class="nav navbar-nav">
                                <li class="active_nav">
                                    <a href="">首页</a>
                                </li>
                                <li class="">
                                    <a href="">热点新闻</a>
                                </li>
                                <li class="">
                                    <a href="">专卖推送</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- 二级导航结束-->
            <!-- 实际内容-->
            <div class="container ">
                <div class="row">
                    <!--左边-->
                    <div class="col-md-8 ">
                        <div class="content padding_lr_x">
                            <!--标题大图片-->
                            <div class="content_titlepic">
                                <!--标题大图片-->
                                <img class="content_titlepic" src="/bigorange/Public/Img/defaultpic.png" alt="标题XXXX">
                            </div>
                            <div class="common_block_border">
                                <div class="common_block_title clearfix">
                                    <!--新闻标题-->
                                    <h2>标题XXXX</h2>
                                    <p>
                                        <!--作者以及发表时间-->
                                        <span class="pull-left">
                                            <span class="author">
                                                <a data-hasqtip="0" href="" ucard="1">管理员</a>
                                            </span>
                                            <span>发表于 2014-07-25 10:07</span>
                                        </span>
                                        <!--上一篇 下一篇-->
                                        <span class="pull-right">
                                            <a href="">上一篇</a>
                                            <a href="">下一篇</a>
                                        </span>
                                    </p>
                                </div>
                                <hr>
                                <!--内容-->
                                <section id="contents">
                                    <div class="padding_lr_xx">
                                        <p>内容XXXX</p>
                                    </div>
                                </section>
                                <!--评论区-->
                                <div>
                                    <hr>
                                    <div class="row insert-face-container">
                                        <div class="col-md-12 weibo_post_box">
                                            <h5 class="text-primary padding_lr_xx">评论</h5>
                                            <div class="row padding_lr_xx">
                                                <div class="col-md-12">
                                                    <p>
                                                        <textarea id="comment-content" style="height: 8em; width: 100%;" class="form-control insert-face-textarea" placeholder="说点什么吧～"></textarea>
                                                    </p>
                                                    <!--表情-->
                                                    <a class="col-md-10 padding_lr_o padding_tb_o" >
                                                        <img src="/bigorange/Public/Img/bq.png">
                                                    </a>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <button class="btn btn-block btn-primary " id="submit-comment" data-url="">发表</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <!--评论-->
                                            <!--单条评论-->
                                            <div class="row padding_lr_xx">
                                                <div class="col-sm-12">
                                                    <a href="" class="pull-left">
                                                        <img data-hasqtip="1" src="/bigorange/Public/Img/head.jpg" uid="1647" class="avatar-img">
                                                    </a>
                                                    <div style="overflow: hidden">
                                                        <div class="" style="padding-left: 16px;">
                                                            <p>
                                                                <a data-hasqtip="2" href="" uid="1647">admin</a>
                                                            </p>
                                                            <p>AD？</p>
                                                            <p>
                                                                <span class="text-muted">08月24日 11:26</span>
                                                                <a class="pull-right local-comment-reply" data-uid="1647" data-username="admin">回复</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row padding_lr_xx">
                                                <div class="col-sm-12">
                                                    <a href="" class="pull-left">
                                                        <img data-hasqtip="1" src="/bigorange/Public/Img/head.jpg" uid="1647" class="avatar-img">
                                                    </a>
                                                    <div style="overflow: hidden">
                                                        <div class="" style="padding-left: 16px;">
                                                            <p>
                                                                <a data-hasqtip="2" href="" uid="1647">admin</a>
                                                            </p>
                                                            <p>AD？</p>
                                                            <p>
                                                                <span class="text-muted">08月24日 11:26</span>
                                                                <a class="pull-right local-comment-reply" data-uid="1647" data-username="admin">回复</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row padding_lr_xx">
                                                <div class="col-sm-12">
                                                    <a href="" class="pull-left">
                                                        <img data-hasqtip="1" src="/bigorange/Public/Img/head.jpg" uid="1647" class="avatar-img">
                                                    </a>
                                                    <div style="overflow: hidden">
                                                        <div class="" style="padding-left: 16px;">
                                                            <p>
                                                                <a data-hasqtip="2" href="" uid="1647">admin</a>
                                                            </p>
                                                            <p>AD？</p>
                                                            <p>
                                                                <span class="text-muted">08月24日 11:26</span>
                                                                <a class="pull-right local-comment-reply" data-uid="1647" data-username="admin">回复</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <!--下一页-->
                                            <div class="pull-right">
                                                <div class="pagination">
                                                    <li class="active"><a class="active">1</a>
                                                    </li>
                                                    <li><a class="num" href="">2</a>
                                                    </li>
                                                    <li><a class="next" href="">&gt;&gt;</a>
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--右边-->
                    <div class="col-md-4 ">
                        <!--作者-->
                        <div class="content ">
                            <div class="content_title md_title">作者</div>
                            <div class="col-md-4">
                                <img class="avatar-img_md" src="/bigorange/Public/Img/defaultpic.png">
                            </div>
                            <div class="col-md-8">
                                <span class="author  ">
                                    <a data-hasqtip="12" href="" ucard="1">管理员</a>
                                </span>
                                <p>Plus官方账号</p>
                                <p>文章：17</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="padding_tb_x"></div>
                        <!--热门-->
                        <div class="content ">
                            <!--标题-->
                            <div class="content_title md_title">热门</div>
                            <!--内容-->
                            <div class="clearfix" style="position: relative">
                                <div class="col-md-4">
                                    <a title="XXXX" href="">
                                        <img alt="XXX_PIC" src="/bigorange/Public/Img/defaultpic.png" class="news_right_pic">
                                    </a>
                                </div>
                                <div class="col-md-8 new_right_content">
                                    <div>
                                        <h3 class="text-more" style="width: 100%">
                                            <a title="XXX" href="">标题XXX</a>
                                        </h3>
                                    </div>
                                    <div>
                                        <span class="author author_right ">
                                            <a data-hasqtip="12" href="" ucard="1">管理员</a>
                                            &nbsp;&nbsp;2014-07-25 10:05:00
                                        </span>
                                    </div>
                                    <div></div>
                                </div>
                                <span class="pull-right hot_tag author_right">
                                    &nbsp;&nbsp;
                                    <span class="author_right"> <i class="glyphicon glyphicon-fire"></i>
                                        419
                                    </span>
                                    &nbsp;&nbsp;
                                </span>
                            </div>
                            <!--分割线-->
                            <div class="hr_soil"></div>
                            <div class="clearfix" style="position: relative">
                                <div class="col-md-4">
                                    <a title="XXXX" href="">
                                        <img alt="XXX_PIC" src="/bigorange/Public/Img/defaultpic.png" class="news_right_pic">
                                    </a>
                                </div>
                                <div class="col-md-8 new_right_content">
                                    <div>
                                        <h3 class="text-more" style="width: 100%">
                                            <a title="XXX" href="">标题XXX</a>
                                        </h3>
                                    </div>
                                    <div>
                                        <span class="author author_right ">
                                            <a data-hasqtip="12" href="" ucard="1">管理员</a>
                                            &nbsp;&nbsp;2014-07-25 10:05:00
                                        </span>
                                    </div>
                                    <div></div>
                                </div>
                                <span class="pull-right hot_tag author_right">
                                    &nbsp;&nbsp;
                                    <span class="author_right">
                                        <i class="glyphicon glyphicon-fire"></i>
                                        419
                                    </span>
                                    &nbsp;&nbsp;
                                </span>
                            </div>
                            <!--分割线-->
                            <div class="hr_soil"></div>
                            <div class="clearfix" style="position: relative">
                                <div class="col-md-4">
                                    <a title="XXXX" href="">
                                        <img alt="XXX_PIC" src="/bigorange/Public/Img/defaultpic.png" class="news_right_pic">
                                    </a>
                                </div>
                                <div class="col-md-8 new_right_content">
                                    <div>
                                        <h3 class="text-more" style="width: 100%">
                                            <a title="XXX" href="">标题XXX</a>
                                        </h3>
                                    </div>
                                    <div>
                                        <span class="author author_right ">
                                            <a data-hasqtip="12" href="" ucard="1">管理员</a>
                                            &nbsp;&nbsp;2014-07-25 10:05:00
                                        </span>
                                    </div>
                                    <div></div>
                                </div>
                                <span class="pull-right hot_tag author_right">
                                    &nbsp;&nbsp;
                                    <span class="author_right">
                                        <i class="glyphicon glyphicon-fire"></i>
                                        419
                                    </span>
                                    &nbsp;&nbsp;
                                </span>
                            </div>
                            <!--站位div-->
                            <div class="cutdiv"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 底栏-->
    <div id="footer" class="text-center">
        <div class="container">
            <span>Power By XXX</span>
            <a data-toggle="modal" data-target="#fingertipModal">联系</a>
            <div class="modal fade footer_contac" id="fingertipModal" tabindex="-1" role="dialog" aria-labelledby="fingertipModalabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="fingertipModalLabel">指尖科技</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                E-mail:
                                <a style="text-decoration: none;" href="mailto:493628086@qq.com">493628086@qq.com</a>
                            </p>
                            <p>
                                E-mail:
                                <a style="text-decoration: none;" href="mailto:714571611@qq.com">714571611@qq.com</a>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </div>
</body>

</html>