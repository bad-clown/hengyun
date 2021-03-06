<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;

?>

<div class="topbar">
    <div class="search">
        <input type="text" class="search-text" name="search" value="" placeholder="搜索" />
        <i class="glyphicon glyphicon-search"></i>
    </div>
    <div class="username">
        <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
    </div>
</div>
<div class="content">
    <div class="breadcrumbBox">
        <ul class="breadcrumb">
            <li class="active">货物类型管理</li>
        </ul>
<!--        <a href="--><?//= $Path;?><!--/admin/goods-category/create" class="btn btn-control">-->
<!--            <span class="glyphicon glyphicon-plus"></span>-->
<!--            新增货物类型-->
<!--        </a>-->
    </div>

    <div class="listBox orderList">
        <div class="goods-category-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'unit',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'delete' => function ($url, $model) {
//                                return Html::a('删除', $url, [
//                                    'class' => 'btn-xs btn-raised btn-danger',
//                                    'data-method' => 'post',
//                                    'data-confirm' => '确定删除该职位？',
//                                    'title' => Yii::t('yii', 'Delete'),
//                                    ]);
                            },
                            'update' => function ($url, $model) {
//                                return Html::a('更新', $url, [
//                                    'class' => 'btn-xs btn-raised btn-info',
//                                    'title' => Yii::t('yii', 'Update'),
//                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">

</script>
<?php $this->endBlock();  ?>

