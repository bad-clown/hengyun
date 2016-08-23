<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;
$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            <li><a href="<?= $Path;?>/user/admin?sort=-time">用户管理</a></li>
            <li><a href="<?= $Path;?>/user/admin?sort=-time">后台管理员</a></li>
            <li class="active">新增后台管理员</li>
        </ul>
    </div>

    <div class="panel-body">
        <div class="alert alert-info">
            <?= Yii::t('user', 'Password and username will be sent to user by email') ?>.
            <?= Yii::t('user', 'If you want password to be generated automatically leave its field empty') ?>.
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => 25, 'autofocus' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(['admin' => '管理员', 'sched' => '调度员', 'finance' => '财务员']) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<?php $this->endBlock();  ?>