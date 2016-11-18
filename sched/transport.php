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
        <input type="text" class="search-text" name="search" value="" placeholder="搜索订单" id="search_submit"/>
        <i class="glyphicon glyphicon-search"></i>
    </div>
    <div class="username">
        <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
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
					<th>调度员</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
    $(document).bind('change','#search_submit',function(){
        var search_val = $('#search_submit').val();
        getData(search_val);
    });
	function getData(search_val) {
	    var obj = {};
        if (!search_val) {
            search_val = '';
        }
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/transport-list",
            data: {
                search_val:search_val
            },
			dataType : "json",
			success : function(data) {
				// console.log(data)
				if(data.code == "0") {
					var c = $('#listContent').find('tbody');
					c.empty();
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
						var h = '<tr><td><div class="form-group"><label>'+Sched.status[o.status]+'</label></div></td><td>'+o.orderNo+'</td><td>'+t+'</td><td class="from">'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td class="to">'+o.provinceTo+o.cityTo+o.districtTo+'</td><td class="cnt"><a href="javascript:;" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td class="weight">'+(o.realTotalWeight || 0)+'</td><td class="drop">'+o.pickupDrop+'</td><td>'+driverBid+'</td><td>'+bidPrice+'</td><td>'+ o.scheduler +'</td><td width="100"><a class="btn-default" href="<?= $Path;?>/sched/order-web/detail-trans?id='+o._id+'">查看详情</a></td></tr>';

						c.append(h)
					})
					_global.badge();
				}
			}
		})
	}
	getData()

	setInterval(function() {
		getData()
	}, 30000)
})
</script>
<?php $this->endBlock();  ?>
