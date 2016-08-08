<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;

?>



<div class="breadcrumbBox">
	<ul class="breadcrumb">
		<li><a href="javascript:;">调度中心</a></li>
		<li><a href="javascript:;">发布管理</a></li>
		<li class="active">查看详情</li>
	</ul>
	<a href="<?= $Path;?>/sched/order-web/new?sort=-time" class="batch">返回</a>
</div>

<div class="order-detail clearfix" id="J-order-detail">
	<table>
		<thead>
			<tr>
				<th>header</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>data</td>
			</tr>
		</tbody>
	</table>
	<div class="form-group label-floating">
		<label class="control-label" for="focusedInput1">手机</label>
		<input class="form-control" id="focusedInput1" type="text">
	</div>
	<div class="form-group label-floating">
		<label class="control-label" for="focusedInput1">手机</label>
		<input class="form-control" id="focusedInput1" type="text">
	</div>
	<div class="form-group label-floating">
		<label class="control-label" for="focusedInput1">手机</label>
		<input class="form-control" id="focusedInput1" type="text">
	</div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/sched/order/detail?id=57a2dfa46a9ce6e67a8b456e",
		dataType : "json",
		success : function(data) {
			var html = '<div class="form-group label-floating"><label class="control-label" for="focusedInput1">订单号</label><input class="form-control" value="'+ data.orderNo +'" id="focusedInput1" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput2">总件数</label><input class="form-control" value="'+ data.goodsCnt +'件" id="focusedInput2" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput3">总吨数</label><input class="form-control" value="'+ data.totalWeight +'吨" id="focusedInput3" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput4">几装几卸</label><input class="form-control" value="'+ data.pickupDrop +'" id="focusedInput4" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput5">货最长</label><input class="form-control" value="'+ data.goodsMaxLen +'" id="focusedInput5" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput6">货最宽</label><input class="form-control" value="'+ data.goodsMaxWidth +'" id="focusedInput6" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput7">起点</label><input class="form-control" value="'+ data.provinceFrom+data.cityFrom+data.districtFrom +'" id="focusedInput7" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput8">终点</label><input class="form-control" value="'+ data.provinceTo+data.cityTo+data.districtTo +'" id="focusedInput8" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput9">提货时间</label><input class="form-control" value="'+ data.deliverTime +'" id="focusedInput9" type="text"></div><div class="form-group label-floating"><label class="control-label" for="focusedInput10">简介</label><input class="form-control" value="'+ data.note +'" id="focusedInput10" type="text"></div>'


			$('#J-order-detail').html(html)
		}
	})
})
</script>
<?php $this->endBlock();  ?>
