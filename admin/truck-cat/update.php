<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Truck Cat',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Truck Cats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="topbar">
    <div class="search">
        <input type="text" class="search-text" name="search" value="" placeholder="搜索订单" />
        <i class="glyphicon glyphicon-search"></i>
    </div>
    <div class="username">
        <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
    </div>
</div>

<div class="content">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="<?= $Path;?>/admin/truck-cat">货车类型管理</a></li>
			<li class="active">新增货车类型</li>
		</ul>
		<!-- <a href="javascript:;" id="j-save-control" class="save-control">保存</a> -->
		<a href="<?= $Path;?>/admin/truck-cat" class="back-control">返回</a>
	</div>

	<div class="truck-cat-update">

	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>

	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<?php $this->endBlock();  ?>
