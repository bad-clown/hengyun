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

<div class="content">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="javascript:;">调度中心</a></li>
			<li><a href="<?= $Path;?>/sched/order-web/new?sort=-time">发布管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="<?= $Path;?>/sched/order-web/new?sort=-time" class="back-control">返回</a>
	</div>

	<div class="order-detail">
		<div id="J-order-detail" class="clearfix"></div>
		<div class="goods-label"><span class="label label-default">货物明细</span></div>
		<div id="J-goods-detail" class="goods-detail clearfix"></div>
	</div>

	<div class="pub-control">
		<div class="control-btns">
			<a href="javascript:;" class="btn-pub">发布</a>
			<a href="javascript:;" class="btn-del">删除</a>
		</div>
		<div class="pub-label"><span>已选：发布</span></div>
	</div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/sched/order/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			var $order = $('#J-order-detail');
			var $goods = $('#J-goods-detail');
			var t = _global.FormatTime(data.deliverTime);
			var orderHTML = '<div class="form-group label-floating"><label class="control-label">订单号</label><input class="form-control" readonly="readonly" value="'+ data.orderNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">总件数</label><input class="form-control" readonly="readonly" value="'+ data.goodsCnt +'件" type="text"></div><div class="form-group label-floating"><label class="control-label">总吨数</label><input class="form-control" readonly="readonly" value="'+ data.totalWeight +'吨" type="text"></div><div class="form-group label-floating"><label class="control-label">几装几卸</label><input class="form-control" readonly="readonly" value="'+ data.pickupDrop +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货最长</label><input class="form-control" readonly="readonly" value="'+ data.goodsMaxLen +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货最宽</label><input class="form-control" readonly="readonly" value="'+ data.goodsMaxWidth +'" type="text"></div><div class="form-group label-floating"><label class="control-label">起点</label><input class="form-control" readonly="readonly" value="'+ data.provinceFrom+data.cityFrom+data.districtFrom +'" type="text"></div><div class="form-group label-floating"><label class="control-label">终点</label><input class="form-control" readonly="readonly" value="'+ data.provinceTo+data.cityTo+data.districtTo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">提货时间</label><input class="form-control" readonly="readonly" value="'+ t +'" type="text"></div><div class="form-group label-floating form-group-last"><label class="control-label">简介</label><input class="form-control" readonly="readonly" value="'+ data.note +'" type="text"></div>';

			$.each(data.goods, function(i, o) {
				var goodsHTML = '<div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 名称</label><input class="form-control" readonly="readonly" value="'+ o["category"].name +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 重量</label><input class="form-control" readonly="readonly" value="'+ o.count+o["category"].unit +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 提货详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressFrom +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 送达详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressTo +'" type="text"></div>';
				$goods.append(goodsHTML)
			})

			$order.html(orderHTML)
		}
	})

	$('.btn-pub').on('click', function() {
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/publish?orderId=<?= $_id;?>",
			success : function(data) {
				if(data.code == "0") {
					alert('发布成功！');
					window.location.href = '<?= $Path;?>/sched/order-web/new?sort=-time'
				}
			}
		})
	})
	$('.btn-del').on('click', function() {
		if(confirm("确定删除？")) {
			$.ajax({
				type : "GET",
				url : "<?= $Path;?>/sched/order-web/del-order?id=<?= $_id;?>",
				dataType : "json",
				success : function(data) {
					if(data.code == '0') {
						alert('删除成功！')
						window.location.href = '<?= $Path;?>/sched/order-web/new?sort=-time'
					}
				}
			})
		}
	})
})
</script>
<?php $this->endBlock();  ?>
