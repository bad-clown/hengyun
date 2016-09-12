<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SchedRouter */

$this->title = Yii::t('app', 'Create Sched Router');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sched Routers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sched-router-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
