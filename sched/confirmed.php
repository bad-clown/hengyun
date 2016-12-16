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

<div class="grid-view">
	<table class="table table-striped table-bordered" id="newOrder">
		<thead>
			<tr>
				<th>订单号</th>
				<th>提货时间</th>
				<th>状态</th>
				<th>起点</th>
				<th>终点</th>
				<th>总件数</th>
				<th>总吨数</th>
				<th>几装几卸</th>
				<th>司机的报价</th>
				<th>货主的报价</th>
				<th>撮合状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<style type="text/css">
.overlay{position: fixed;top: 0;left: 0;width: 100%;height: 100%;background: #000;opacity: .5;z-index: 9;display: none;}
.popup{position: fixed;top: 50px;left: 50%;width: 700px;height: 350px;background: #fff;z-index: 10;padding: 30px;margin-left: -350px;display: none;}
/*.popup .table{margin-bottom: 0;}*/
.popup .close-btn{position: absolute;right: 10px;top: 10px;}
.price-pop{height: 222px;}
</style>
<div class="details-pop popup">
	<a href="javascrip:;" class="glyphicon glyphicon-remove close-btn"></a>
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


<div class="driver-pop popup">
	<a href="javascrip:;" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="grid-view">
		<div style="height: 264px;overflow-y: auto;overflow-x:hidden;">
			<table class="table table-striped table-bordered" id="driverOrder">
				<thead>
					<tr>
						<th>撮合</th>
						<th>报价</th>
						<th>报价时间</th>
						<th>电话</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<a href="javascript:;" class="btn btn-primary" id="driverSubmit">提交</a>
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
	function Digit(n) {
		return n < 10 ? "0"+n : n;
	}
	function FormatTime(n) {
		var nS= new Date(parseInt(n) * 1000),
			year=Digit(nS.getFullYear()),
			month=Digit(nS.getMonth()+1),
			date=Digit(nS.getDate()),
			hour=Digit(nS.getHours()),
			minute=Digit(nS.getMinutes());
		return year+"年"+month+"月"+date+"日 "+hour+":"+minute;
	}

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
			url : "<?= $Path;?>/sched/order/confirmed-list",
			dataType : "json",
			success : function(data) {
				// console.log(data)
				if(data.code == "0") {
					var c = $('#newOrder').find('tbody');
					c.empty();
					$.each(data.data, function(i,o) {
						if(!o.bid["bidPrice"] || !o.bid["bidTime"]) {
							var bidPrice = '还未给货主报价';
						}
						else {
							var bidPrice = priceType[o.bid["bidPriceType"]]+"："+o.bid["bidPrice"]+'元<br>报价时间：'+FormatTime(o.bid["bidTime"]);
						}
						if(o.driverBid.length == 0) {
							var driverBid = '未选择司机';
						}
						else {
							var driverBid = priceType[o.driverBid["bidPriceType"]]+"："+o.driverBid["bidPrice"]+'元<br>报价时间：'+FormatTime(o.driverBid["bidTime"]);
						}
						var dealt = o.dealt ? '已撮合' : '未撮合';
						var t = FormatTime(o.deliverTime);
						var h = '<tr><td>'+o.orderNo+'</td><td>'+t+'</td><td>'+status[o.status]+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td><a href="javascript:;" class="orderDetails" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td>'+o.totalWeight+'</td><td>'+o.pickupDrop+'</td><td>'+driverBid+'</td><td>'+bidPrice+'</td><td>'+dealt+'</td><td><a class="btn btn-xs btn-primary btn-block j-driver" href="javascript:;" data-key="'+o._id+'" title="">撮合</a></td></tr>';
						c.append(h)
					})
				}
			}
		})
	}
	getData()

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
					var h = '<tr><td><input type="radio" name="driverId" data-key="'+o.driverId+'" /></td><td>'+priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td></tr>'

					c.append(h)
					$('#driverSubmit').data('key', k);
					$('.driver-pop:eq(0)').show();
					$('.overlay:eq(0)').show();
				})
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
						var h = '<tr><td>'+priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td></tr>'

						c.append(h)
						$('.pricelist-pop:eq(0)').show();
						$('.overlay:eq(0)').show();
					})
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
