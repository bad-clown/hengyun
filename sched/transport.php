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
<div class="topbar">
	<div class="search">
		<input type="text" class="search-text" name="search" value="" placeholder="搜索" />
		<i class="glyphicon glyphicon-search"></i>
	</div>
	<div class="username">
		<a href="#"><span><?= \Yii::$app->user->identity->type;?></span></a> | <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
	</div>
</div>
<div class="content">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="javascript:;">调度中心</a></li>
			<li class="active">运输管理</li>
		</ul>
	</div>

	<div class="listBox">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th>状态</th>
					<th>订单号</th>
					<th>提货时间</th>
					<th>起点</th>
					<th>终点</th>
					<th>总件数</th>
					<th>总吨数</th>
					<th>几装几卸</th>
					<th>司机报价</th>
					<th>货主的价格</th>
					<th>交易员</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
$(function() {
   var actKey = '';
	actKey = $('.search-text').val();
	function getData() {

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/transport-list",
            data: {
				actKey : actKey
            },
			dataType : "json",
			success : function(data) {
				if(data.code == "0") {
					var $listContent = $('#listContent').find('tbody');
					var htmlCont = '';
					$listContent.empty();
					$.each(data.data, function(i,o) {
						if(!o.driverBid) {
							var driverBid = '暂无司机报价';
						}
						else {
							var driverBid = Sched.priceType[o.driverBid["bidPriceType"]]+"："+o.driverBid["bidPrice"]+'元<br>合计：'+o.driverBid["realTotalMoney"]+'<br>'+_global.FormatTime(o.driverBid["bidTime"]);;
						}
						if(!o.bid["bidPrice"] || !o.bid["bidTime"]) {
							var bidPrice = '还未给货主报价';
						}
						else {
							var bidPrice = Sched.priceType[o.bid["bidPriceType"]]+"："+o.bid["bidPrice"]+'元<br>合计：'+o.realTotalMoney+'<br>'+_global.FormatTime(o.bid["bidTime"]);
						}

						var t = _global.FormatTime(o.deliverTime);
						htmlCont += '<tr><td><div class="form-group"><label>'+Sched.status[o.status]+'</label></div></td><td>'+o.orderNo+'</td><td>'+t+'</td><td class="from">'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td class="to">'+o.provinceTo+o.cityTo+o.districtTo+'</td><td class="cnt"><a href="javascript:;" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td class="weight">'+(o.realTotalWeight || 0)+'</td><td class="drop">'+o.pickupDrop+'</td><td>'+driverBid+'</td><td>'+bidPrice+'</td><td>'+ o.scheduler +'</td><td width="100"><a class="btn-default" href="<?= $Path;?>/sched/order-web/detail-trans?id='+o._id+'">查看详情</a></td></tr>';
					})
					$listContent.append(htmlCont)
					_global.badge();
				}
			}
		})
	}
	getData()

	$(document).on('focus', '.search-text', function () {
		clearInterval(timer);
	});
	$(document).on('blur', '.search-text', function () {
		timer = setInterval(function () {
			getData()
		}, 30000);

	});
	var timer =  setInterval(function () {
		getData()
	}, 30000);

	$(document).on('keypress','.search-text', function(e) {
		if(e.keyCode == 13) {
			actKey = $(this).val();
			getData()
		}
	})
})
</script>
<?php $this->endBlock();  ?>
