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
    <div class="username">
        <a href="#"><span><?= \Yii::$app->user->identity->type;?></span></a> | <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
    </div>
</div>
<div class="content">
    <div class="breadcrumbBox">
        <ul class="breadcrumb">
            <li class="active">货车类型管理</li>
        </ul>
        <a href="<?= $Path;?>/admin/truck-cat/create" class="btn btn-control">
            <span class="glyphicon glyphicon-plus"></span>
            新增货车类型
        </a>
    </div>

    <div class="listBox orderList">
        <div class="truck-cat-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'order',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'delete' => function ($url, $model) {
//                                return Html::a('删除', $url, [
//                                    'class' => 'btn-xs btn-danger',
//                                    'data-method' => 'post',
//                                    'data-confirm' => '确定删除该职位？',
//                                    'title' => Yii::t('yii', 'Delete'),
//                                    ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('更新', $url, [
                                    'class' => 'btn-xs btn-info',
                                    'title' => Yii::t('yii', 'Update'),
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">

</script>
<?php $this->endBlock();  ?>

