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
			<li><a href="javascript:;">调度中心</a></li>
			<li class="active">报价管理</li>
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
					<th>报价人数</th>
					<th>货主的价格</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
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
				<div class="form-group select-menu mr40">
					<select name="priceType" id="priceType" class="form-control">
						<option value="0">单价</option>
						<option value="1">一口价</option>
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
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="pricelist-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li class="active">司机报价</li>
				</ul>
			</div>
			<div class="driverBox clearfix" id="priceOrder">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>报价</th>
							<th>合计</th>
							<th>报价时间</th>
							<th>电话</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="overlay"></div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	function getData() {
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid-order-list",
			dataType : "json",
			success : function(data) {
				if(data.code == "0") {
					var c = $('#listContent').find('tbody');
					c.empty();
					$.each(data.data, function(i,o) {
						if(!o.bidCnt) {
							var bidCnt = '暂无司机报价';
						}
						else {
							var bidCnt = '<div class="form-group"><label><a href="javascript:;" class="j-price-list" data-key="'+o._id+'">'+o.bidCnt+'人</a></label></div>';
						}

						if(!o.bid["bidPrice"] || !o.bid["bidTime"]) {
							var bidPrice = '还未给货主报价';
							var bidCls = 'j-price';
						}
						else {
							var bidPrice = Sched.priceType[o.bid["bidPriceType"]]+"："+o.bid["bidPrice"]+'元<br>合计：'+o.realTotalMoney+'<br>'+_global.FormatTime(o.bid["bidTime"]);
							var bidCls = 'has-driver';
						}

						if((!o.bid["bidPrice"] || !o.bid["bidTime"]) || !o.bidCnt || o.status > 300 || o.dealt) {
							var driverCls = 'has-driver';
						}
						else {
							var driverCls = 'j-driver';
						}

						var t = _global.FormatTime(o.deliverTime);
						var h = '<tr><td><div class="form-group"><label>'+Sched.status[o.status]+'</label></div></td><td>'+o.orderNo+'</td><td>'+t+'</td><td class="from">'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td class="to">'+o.provinceTo+o.cityTo+o.districtTo+'</td><td class="cnt"><a href="javascript:;" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td class="weight">'+(o.realTotalWeight || 0)+'</td><td class="drop">'+o.pickupDrop+'</td><td>'+bidCnt+'</td><td>'+bidPrice+'</td><td width="250"><a class="btn-default" href="<?= $Path;?>/sched/order-web/detail-bid?id='+o._id+'">查看详情</a><a class="btn-default '+bidCls+'" href="javascript:;" data-key="'+o._id+'">报价</a><a href="javascript:;" class="btn-default '+driverCls+'" data-key="'+o._id+'">撮合</a></td></tr>';

						c.append(h)
					})
					_global.badge();
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
			},
			error : function() {
				alert("提交失败，请检查网络后重试！");
			}
		})
	})

	$(document).on('click', '.j-driver', function() {
		var k = $(this).data('key');
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid-list?orderId="+k,
			dataType : "json",
			success : function(data) {
				var c = $('#driverOrder').find('tbody');
				c.empty();
				$.each(data.data, function(i, o) {
					if(o.win) {
						var trCls = 'has';
						var aHtml = '<a href="javascript:void(0);" class="suc-driver-control">已撮合</a>';
					}
					else {
						var trCls = '';
						var aHtml = '<a href="javascript:void(0);" class="driver-control" data-key="'+o.driverId+'">撮合</a>';
					}
					var h = '<tr class="'+trCls+'"><td>'+Sched.priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+o.realTotalMoney+'元</td><td>'+_global.FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td><td>'+aHtml+'</td></tr>';

					c.append(h)
				})
				$('#j-submit-driver').data('key', k);
				$('.driver-pop:eq(0)').show();
				$('.overlay:eq(0)').show();
			}
		})
	})

	$(document).on('click', '.driver-control', function() {
		$('.driver-control').removeClass('has-driver-control');
		$('#driverOrder').find('tr').removeClass('has');
		$(this).parents('tr').addClass('has');
		$(this).addClass('has-driver-control');
		$('#j-submit-driver').data('driverId', $(this).data('key'));
	})


	$('#j-submit-driver').on('click', function() {
		var orderId = $(this).data('key'), driverId = $(this).data('driverId');
		if(!driverId) {alert('请先选择司机！');return false;};

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/driver",
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
			},
			error : function() {
				alert("提交失败，请检查网络后重试！");
			}
		})
	})

	$(document).on('click', '.j-price-list', function() {
		var k = $(this).data('key');
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/bid-list?orderId="+k,
			dataType : "json",
			success : function(data) {
				var c = $('#priceOrder').find('tbody');
				c.empty();
				$.each(data.data, function(i, o) {
					var h = '<tr><td>'+Sched.priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+o.realTotalMoney+'元</td><td>'+_global.FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td></tr>';

					c.append(h)
				})
				$('.pricelist-pop:eq(0)').show();
				$('.overlay:eq(0)').show();
			}
		})
	})

	$('#priceType').change(function() {
		if($(this).val() == 1) {
			$('.label-unit:eq(0)').html('元')
		}
		else {
			$('.label-unit:eq(0)').html('元 / 吨')
		}
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
