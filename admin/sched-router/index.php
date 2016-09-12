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
$this->title = Yii::t('app', 'Sched Routers');
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
            <li class="active">路线管理</li>
        </ul>
        <div class="btn-control">
            <span class="glyphicon glyphicon-plus"></span>
            <?= Html::a(Yii::t('app', '新增路线'), ['create'], ['id' => 'open-create']) ?>
        </div>
    </div>

    <div class="detail-box bid-detail">
        <div class="detail-label"><span class="label label-default">其他路线</span></div>
        <div class="clearfix" id="">
            <div class="form-group">
                <label for="name" class="control-label">调度员</label>
                <select id="name" name="name" class="form-control" disabled="disabled"></select>
            </div>
            <div class="form-group">
                <label class="control-label">联系电话</label>
                <input class="form-control" name="phone" id="phone" value="" type="text" readonly="readonly">
            </div>
            <a href="javascript:;">修改</a>
            <a href="javascript:;">保存</a>
        </div>
    </div>
</div>

<div class="content">
    <div class="listBox pt15">
        <div class="detail-label"><span class="label label-default">特定路线</span></div>
        <div class="" id="">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'provinceFrom',
                    'provinceTo',
                    'default',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    $.ajax({
        type : 'get',
        url : '<?= $Path;?>/admin/sched-router/default-sched',
        dataType : 'json',
        success : function(data) {
            var name = data.name || '暂无';
            var phone = data.phone || '暂无';
            $('select[name="name"]').get(0).options.add(new Option(name));
            $('input[name="phone"]').val(phone);
        }
    })
})
</script>
<?php $this->endBlock();  ?>
