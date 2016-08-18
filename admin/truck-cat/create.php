<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TruckCat */

$this->title = Yii::t('app', 'Create Truck Cat');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Truck Cats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="truck-cat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
