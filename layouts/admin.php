<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: admin.php
 * $Id: admin.php v 1.0 2016-06-22 14:20:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-08-25 11:26:03 $
 * @brief
 *
 ******************************************************************/

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\Dictionary;
$Path = Dictionary::indexKeyValue('App', 'Host', false);
if(!isset($this->context->unRegisterAppAsset)){
    AppAsset::register($this);//注册前端资源
}
//用户登录了。取用户设置的语言优先
$language =\Yii::$app->language;
//是否是内容页，默认都是，内容页不需要菜单
$isContentPage = !isset($this->context->notContentPage);
?><?php $this->beginPage() ?><!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7"  lang="<?= $language    ?>"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8"  lang="<?= $language   ?>"><![endif]-->
<!--[if IE 8]><html class="lt-ie9"  lang="<?= $language   ?>"><![endif]-->
<!--[if gt IE 8]><!--><html lang="<?= $language   ?>"><!--<![endif]-->
<head>
<meta charset="<?= Yii::$app->charset ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="renderer" content="webkit">
<?= Html::csrfMetaTags() ?>
<title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
<script type="text/javascript">var $_Path="<?= $Path;?>";</script>
<!--[if lt IE 9]><!-->
<script type="text/javascript" src="<?= Url::to(["/static/js/oldbrowsers.js"])?>"></script>
<!--<![endif]-->
<?php $this->head() ?>
<!-- Material Design fonts -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Bootstrap Material Design -->
<link href="/static/bootstrap-material-design/dist/css/bootstrap-material-design.css" rel="stylesheet">
<link href="/static/bootstrap-material-design/dist/css/ripples.min.css" rel="stylesheet">
<link href="/static/snackbarjs/dist/snackbar.min.css" rel="stylesheet">
<link href="/static/css/index.css" rel="stylesheet">
</head>
<body <?= isset($_GET["fullscreen"])?' class="fullscreen" ':"" ?> >
<div id="mask" class="mask"></div>

<?php
if($isContentPage){
?>
<?= $content ?>
<?php
}else{ // is not contentpage
?>
    <!--隐藏按钮 -->
    <button class="btn btn-primary" id="hide-btn" style="position: fixed;top:2px;left:-30px; z-index: 9999;width: 5%">隐藏</button>
<div class="wrapper" >
    <div class="side-nav">
        <div class="title">
            <a href="#">
                <span>享运</span><br>
                享运物流后台系统
            </a>
        </div>
        <div class="nav-list" id="j-nav" deep="show">
            <ul>

                <li>
                    <a href="javascript:;" target="mainframe" class="sched-nav select-menu">调度中心<span class="badge" id="total-cnt">0</span><i class="glyphicon glyphicon-time" ></i></a>
                    <ul class="sub-nav" style="display: none;">
                        <li>
                            <a href="/sched/order-web/new?sort=-time" target="mainframe">发布管理<span class="badge" id="new-cnt">0</span></a>
                        </li>
                        <li>
                            <a href="/sched/order-web/bid-list" target="mainframe">报价管理<span class="badge" id="bid-cnt">0</span></a>
                        </li>
                        <li>
                            <a href="/sched/order-web/transport-list" target="mainframe">运输管理<span class="badge" id="trans-cnt">0</span></a>
                        </li>
                        <li>
                            <a href="/sched/order-web/reject-list" target="mainframe">报价失败列表<span class="badge" id="reject-cnt">0</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/finance/order-web/order-list" target="mainframe" class="nav">订单管理<i class="glyphicon glyphicon-list-alt" ></i></a>

                </li>
                <?php if ((\Yii::$app->user->identity->type) != 'manager') :?>
                    <li>
                        <a href="/finance/bill-shipper-web/list" target="mainframe" class="nav">货主账单管理<i class="glyphicon glyphicon-tasks" ></i></a>
                    </li>
                    <li>
                        <a href="/finance/bill-driver-web/list" target="mainframe" class="nav">司机账单管理<i class="glyphicon glyphicon-tasks" ></i></a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/finance/order-web/order-management" target="mainframe" class="nav">账单管理<i class="glyphicon glyphicon-tasks" ></i></a>
                    </li>
                <?php endif;?>
                <li>
                    <a href="javascript:;" target="mainframe" class="user-nav select-menu">用户管理<i class="glyphicon glyphicon-user" ></i></a>
                    <ul class="sub-nav" style="display: none;">
                        <li>
                            <a href="/admin/user-web/managers" target="mainframe">后台管理员</a>
                        </li>
                        <li>
                            <a href="/admin/user-web/app-users" target="mainframe">APP用户</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/admin/goods-category" target="mainframe" class="nav">货物类型管理<i class="glyphicon glyphicon-gift" ></i></a>
                </li>
                <li>
                    <a href="javascript:;" target="mainframe" class="truck-nav select-menu">货车管理<i class="glyphicon glyphicon-dashboard" ></i></a>
                    <ul class="sub-nav" style="display: none;">
                        <li>
                            <a href="/admin/truck-cat/index?sort=order" target="mainframe">货车类型管理</a>
                        </li>
                        <li>
                            <a href="/admin/truck-length/index?sort=order" target="mainframe">车长管理</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/admin/sched-router" target="mainframe" class="nav">调度路线管理<i class="glyphicon glyphicon-map-marker" ></i></a>
                </li>
                <li>
                    <a href="/admin/activity-web" target="mainframe" class="nav">活动中心<i class="glyphicon glyphicon-star-empty" ></i></a>
                </li>
                <li>
                    <a href="/finance/order-web/recycle" target="mainframe" class="nav">回收站<i class="glyphicon glyphicon-trash" ></i></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main">
        <?= $content ?>
    </div>
</div>
<?php
}// end if is not contentpage
?>

<?php $this->endBody() ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<script  type="text/javascript" src="/static/bootstrap-material-design/dist/js/ripples.min.js"></script>
<script  type="text/javascript" src="/static/bootstrap-material-design/dist/js/material.min.js"></script>
<script  type="text/javascript" src="/static/snackbarjs/dist/snackbar.min.js"></script>
<script  type="text/javascript" src="/static/js/index.js"></script>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>
<?php
if(!$isContentPage){
?>
<script type="text/javascript">
$(function() {
    $('html').addClass('no-scroll')
    $('#mainframe').height($(window).height())
    $(window).on('resize', function() {
        $('#mainframe').height($(window).height())
    })

    function orderCnt() {
        $.getJSON($_Path+'/sched/order-web/order-cnt', function(data) {
            if(data.total){$("#total-cnt").show().html(data.total);}
            if(data.new){$("#new-cnt").show().html(data.new);}
            if(data.bid){$("#bid-cnt").show().html(data.bid);}
            if(data.reject){$("#reject-cnt").show().html(data.reject);}
            if(data.trans){$("#trans-cnt").show().html(data.trans);}

        })
    }
    setInterval(function() {
        orderCnt()
    }, 5000)

    $('#j-nav .nav').on('click', function() {
        $('.sub-nav').hide();
        $('#j-nav a').removeClass('cur');
        $(this).addClass('cur');
    })

    $('.user-nav,.sched-nav,.truck-nav,.bid-nav').on('click', function() {
        if($(this).hasClass('cur')) {
            $(this).removeClass('cur')
            $(this).next('.sub-nav').hide();
        }
        else {
            $('#j-nav .nav').removeClass('cur')
            $(this).addClass('cur')
            $(this).next('.sub-nav').show();
        }
    })

    $('.sub-nav a').on('click', function() {
        $('.sub-nav a').removeClass('cur')
        $(this).addClass('cur');
    })

    // 隐藏按钮
    $('#hide-btn').click(function(){
        if ($('.side-nav').prop('deep') == 'hide') {
            $('.side-nav').toggle('show').prop('deep','show');
            $('#hide-btn').html('隐藏');
            $('.main').css('margin-left', '270px');
        } else {
            $('#hide-btn').html('显示');
            $('.side-nav').toggle('show').prop('deep','hide');
            $('.main').css('margin-left', '0px');
        }

    })

});

</script>
<?php
}
?>
</body>
</html>
<?php $this->endPage() ?>
