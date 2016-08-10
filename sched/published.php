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
		<li class="active">报价管理</li>
	</ul>
</div>

<div class="listBox">
	<table class="table table-striped table-hover" id="newOrder">
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
				<th>报价人数</th>
				<th>货主的价格</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<div class="price-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li class="active">报价给货主</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary" id="j-submit-price" title="保存">保存</a>
			</div>
			<div class="priceBox clearfix">
				<div class="form-group">
					<select name="priceType" id="priceType" class="form-control">
						<option>单价</option>
						<option>一口价</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="price" id="price" class="form-control" placeholder="请输入给货主的报价">
					<label class="label-unit">元 / 吨</label>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="details-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="grid-view">
			<div style="height: 308px;overflow-y: auto;overflow-x:hidden;">
				<table class="table table-striped table-bordered" id="orderDetails">
					<thead>
						<tr>
							<th>提货地址</th>
							<th>卸货地址</th>
							<th>数量</th>
							<th>分类</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="driver-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li class="active">撮合司机</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary" id="j-submit-driver" title="保存">保存</a>
			</div>
			<div class="driverBox clearfix" id="driverOrder">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>报价</th>
							<th>合计</th>
							<th>报价时间</th>
							<th>电话</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="pricelist-pop popup">
	<a href="javascrip:;" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="grid-view">
		<div style="height: 308px;overflow-y: auto;overflow-x:hidden;">
			<table class="table table-striped table-bordered" id="priceOrder">
				<thead>
					<tr>
						<th>报价</th>
						<th>报价时间</th>
						<th>电话</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="overlay"></div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {

	function getData() {
		var status = {
			100 : "新发布",
			200 : "待确认",
			300 : "待派车",
			400 : "待提货",
			500 : "在途中",
			600 : "已送达",
			700 : "已完成",
			800 : "已拒绝",
			900 : "已过期",
			1000 : "已失效"
		};
		var priceType = {0 : '单价', 1 : '一口价'}
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/published-and-wait-confirm-list",
			dataType : "json",
			success : function(data) {
				// console.log(data)
				if(data.code == "0") {
					var c = $('#newOrder').find('tbody');
					c.empty();
					$.each(data.data, function(i,o) {
						if(!o.bidCnt) {
							var bidCnt = '暂无司机报价';
						}
						else {
							var bidCnt = '<a href="javascript:;" class="j-price-list" data-key="'+o._id+'">'+o.bidCnt+'人</a>'
						}
						if(!o.bid["bidPrice"] || !o.bid["bidTime"]) {
							var bidPrice = '还未给货主报价';
						}
						else {
							var bidPrice = priceType[o.bid["bidPriceType"]]+"："+o.bid["bidPrice"]+'元<br>合计：'+o.realTotalMoney+'<br>'+_global.FormatTime(o.bid["bidTime"]);
						}
						var t = _global.FormatTime(o.deliverTime);
						var h = '<tr><td>'+status[o.status]+'</td><td>'+o.orderNo+'</td><td>'+t+'</td><td class="from">'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td class="to">'+o.provinceTo+o.cityTo+o.districtTo+'</td><td class="cnt"><a href="javascript:;" class="orderDetails" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td class="weight">'+o.realTotalWeight+'</td><td class="drop">'+o.pickupDrop+'</td><td>'+bidCnt+'</td><td>'+bidPrice+'</td><td width="250"><a class="btn-info" href="javascript:;">查看详情</a><a class="btn-primary j-price" href="javascript:;" data-key="'+o._id+'" title="">报价</a><a class="btn-primary  j-driver" data-key="'+o._id+'" href="javascript:;">撮合</a></td></tr>';

						c.append(h)
					})
				}
			}
		})
	}
	getData()

	$(document).on('click', '.j-price', function() {
		var tr = $(this).parents('tr:eq(0)');
		$('.price-pop > .popup-header').html('<table><tbody><tr><td>起点：'+ $.trim(tr.find('.from').text()) +'</td><td>终点：'+ $.trim(tr.find('.to').text()) +'</td><td>件数：'+ $.trim(tr.find('.cnt').text()) +'</td><td>总吨数：'+ $.trim(tr.find('.weight').text()) +'吨</td><td>几装几卸：'+ $.trim(tr.find('.drop').text()) +'</td></tr></tbody></table>')
		$('#j-submit-price').data('key', $(this).data('key'));
		$('.price-pop:eq(0)').show()
		$('.overlay:eq(0)').show()
	})

	$('#j-submit-price').on('click', function() {
		var k = $(this).data('key'),
			p = $.trim($('#price').val()),
			t = $('#priceType').val();
		if(!p) {$('#price').focus();return false;}
		if(isNaN(p)) {
			alert("请输入数字");
	　　　　$('#price').focus()
	　　　　return false;
		}

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid",
			data : {
				orderId : k,
				price : p,
				priceType : t
			},
			success : function(data) {
				if(data.code == "0") {
					alert('提交成功！')
					$('.close-btn').click()
					getData()
				}
				else {
					alert('提交失败！')
					getData()
				}
			}
		})
	})

	$(document).on('click', '.orderDetails', function() {
		var k = $(this).data('key');
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order-web/goods-detail?orderNo="+k,
			dataType : "json",
			success : function(data) {
				console.log(data)
				var c = $('#orderDetails').find('tbody');
				c.empty();
				$.each(data, function(i, o) {
					var h = '<tr><td>'+ o.addressFrom +'</td><td>'+ o.addressTo +'</td><td>'+ o.count + o.category["unit"]+'</td><td>'+ o.category["name"] +'</td></tr>';

					c.append(h)
					$('.details-pop:eq(0)').show();
					$('.overlay:eq(0)').show();
				})
			}
		})
	})

	$(document).on('click', '.j-price-list', function() {
		var k = $(this).data('key');
		var priceType = {0 : '单价', 1 : '一口价'}
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid-list?orderId="+k,
			dataType : "json",
			success : function(data) {
				if(data.code == '0') {
					var c = $('#priceOrder').find('tbody');
					c.empty();
					$.each(data.data, function(i, o) {
						var h = '<tr><td>'+priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+_global.FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td></tr>'

						c.append(h)
						$('.pricelist-pop:eq(0)').show();
						$('.overlay:eq(0)').show();
					})
				}
			}
		})
	})

	$(document).on('click', '.j-driver', function() {
		var k = $(this).data('key');
		var priceType = {0 : '单价', 1 : '一口价'}
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid-list?orderId="+k,
			dataType : "json",
			success : function(data) {
				var c = $('#driverOrder').find('tbody');
				c.empty();
				$.each(data.data, function(i, o) {
					var h = '<tr><td>'+priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+o.realTotalMoney+'元</td><td>'+_global.FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td><td><input type="radio" name="driverId" data-key="'+o.driverId+'" /></td></tr>'

					c.append(h)
				})
				$('#driverSubmit').data('key', k);
				$('.driver-pop:eq(0)').show();
				$('.overlay:eq(0)').show();
			}
		})
	})

	$('#driverSubmit').on('click', function() {
		var orderId = $(this).data('key'), driverId = null;;
		var driverIdList = $("input[name='driverId']");
		driverIdList.each(function(i, o) {
			if(o.checked) {driverId = $(o).data("key");}
		})
		if(!driverId) {alert('请先选择司机！');return false;};

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/choose-driver",
			data : {
				orderId : orderId,
				driverId : driverId
			},
			success : function(data) {
				if(data.code == '0') {
					alert('提交成功！');
					$('.close-btn').click();
					getData();
				}
				else {
					alert('提交失败！');
				}
			}
		})
	})

	$('.close-btn').on('click', function() {
		$('#price').val('')
		$('#orderDetails').find('tbody').empty()
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})

	setInterval(function() {
		getData()
	}, 30000)
})
</script>
<?php $this->endBlock();  ?>
