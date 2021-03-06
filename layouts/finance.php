<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: admin.php
 * $Id: admin.php v 1.0 2016-06-22 14:20:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-06-23 16:33:01 $
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
<link href="<?= $Path;?>/static/bootstrap-material-design/dist/css/bootstrap-material-design.css" rel="stylesheet">
<link href="<?= $Path;?>/static/bootstrap-material-design/dist/css/ripples.min.css" rel="stylesheet">
<link href="<?= $Path;?>/static/snackbarjs/dist/snackbar.min.css" rel="stylesheet">
<link href="<?= $Path;?>/static/css/index.css" rel="stylesheet">
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
<div class="wrapper">
    <div class="side-nav">
        <div class="title">
            <a href="#">
                <span>享运</span><br>
                享运物流后台系统
            </a>
        </div>
        <div class="nav-list" id="j-nav">
            <ul>
                <li>
                    <a href="/finance/order-web/order-list" target="mainframe" class="nav">订单管理<i class="glyphicon glyphicon-list-alt" ></i></a>
                </li>
                <li>
                    <a href="/finance/bill-shipper-web/list" target="mainframe" class="nav">货主账单管理<i class="glyphicon glyphicon-tasks" ></i></a>
                </li>
                <li>
                    <a href="/finance/bill-driver-web/list" target="mainframe" class="nav">司机账单管理<i class="glyphicon glyphicon-tasks" ></i></a>
                </li>
                <li>
                    <a href="javascript:;" target="mainframe" class="sched-nav select-menu">订单修改申请<i class="glyphicon glyphicon-edit" ></i></a>
                    <ul class="sub-nav" style="display: none;">
                        <li>
                            <a href="/finance/order-web/apply-update?approve=0" target="mainframe">审批中</a>
                        </li>
                        <li>
                            <a href="/finance/order-web/apply-update?approve=1" target="mainframe" >已批准</a>
                        </li>
                        <li>
                            <a href="/finance/order-web/apply-update?approve=-1" target="mainframe" >已拒绝</a>
                        </li>
                    </ul>
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
<script  type="text/javascript" src="<?= $Path;?>/static/bootstrap-material-design/dist/js/ripples.min.js"></script>
<script  type="text/javascript" src="<?= $Path;?>/static/bootstrap-material-design/dist/js/material.min.js"></script>
<script  type="text/javascript" src="<?= $Path;?>/static/snackbarjs/dist/snackbar.min.js"></script>
<script  type="text/javascript" src="<?= $Path;?>/static/js/index.js"></script>
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
    $('#j-nav .nav').on('click', function() {
        $('#j-nav .nav').removeClass('cur');
        $(this).addClass('cur');
    })

    $('.bid-nav').on('click', function() {
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

    $('.sched-nav').on('click', function() {
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
