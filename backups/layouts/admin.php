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
    <!--[if gt IE 8]><!--><html  lang="<?= $language   ?>"><!--<![endif]-->
    <head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
    <!--[if lt IE 9]><!-->
    <script type="text/javascript" src="<?= Url::to(["/static/js/oldbrowsers.js"])?>"></script>
<!--<![endif]-->
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="<?=Url::to(["/static/css/admin.css","v"=>\Yii::$app->params["version"]])?>" />
    </head>
    <body <?= isset($_GET["fullscreen"])?' class="fullscreen" ':"" ?> >
    <?php $this->beginBody() ?>
    <?php if (isset($this->blocks['topcode'])): ?>
    <?= $this->blocks['topcode'] ?>
    <?php  endif; ?>
<?php
if($isContentPage){
?>
<?= $content ?>
<?php
}else{ // is not contentpage
?>
<?php
    NavBar::begin([
        'brandLabel' => Dictionary::indexKeyValue("App","SystemName"),
            //'brandUrl' => Yii::$app->homeUrl,
            'brandUrl' => "javascript:;",
            'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            ],
            ]);
    $items=[];
    $items[] = ['label' =>  "用户管理", 'url' => Url::to(['/user/admin',"sort"=>"-time"]),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "货物类型管理", 'url' => Url::to(['/admin/goods-category']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "货车类型管理", 'url' => Url::to(['/admin/truck-cat']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "反馈管理", 'url' => Url::to(['/admin/feedback']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "调度中心", 'url' => Url::to(['/sched']),'linkOptions'=>["target"=>"blank"]];
    if((!\Yii::$app->user->isGuest) ){
        //$items[]=  ['label' => '安全退出 (' . Yii::$app->user->identity->username . ')', 'url' => ['/user/logout'], 'linkOptions' => ['data-method' => 'post']];
        $items[]=  ['label' => '安全退出', 'url' => ['/user/logout-web'], 'linkOptions' => ['data-method' => 'post']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        ]);
    NavBar::end();
?>
<div class="wrap">
<?= $content ?>
</div>
<?php
}// end if is not contentpage
?>
<?php $this->endBody() ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>
</body>
</html>
<?php $this->endPage() ?>
