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
			<li><a href="<?= $Path;?>/sched/order-web/bid-list">报价管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="<?= $Path;?>/sched/order-web/bid-list" class="back-control">返回</a>
	</div>

	<div class="detail-box bid-detail">
		<div class="clearfix" id="J-order-detail"></div>
		<div class="detail-label"><span class="label label-default">货物明细</span></div>
		<div class="goods-detail clearfix" id="J-goods-detail"></div>
	</div>
</div>

<div class="content">
	<div class="detail-box bid-detail pt15" id="J-shipper-detail">
		<div class="detail-label"><span class="label label-default">用户信息</span></div>
	</div>
	<div class="detail-box bid-detail pt15 pb100" id="J-trucklist-detail">
		<div class="detail-label"><span class="label label-default">车辆信息</span></div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" class="btn-price j-price">修改报价</a>
			<a href="javascript:;" class="btn-driver j-driver">撮合</a>
			<a href="javascript:;" class="btn-driver j-cancel">取消订单</a>
		</div>
		<div class="panel-label"><span></span></div>
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

<div class="overlay"></div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	var _bidUrl = "", _driverUrl = "";

	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/sched/order/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			var $order = $('#J-order-detail');
			var $goods = $('#J-goods-detail');
			var $shipper = $('#J-shipper-detail');
			var $trucklist = $('#J-trucklist-detail');
			var t = _global.FormatTime(data.deliverTime);
			var bidPrice,bidPriceTotal,driverBid,driverBidTotal,hasBidWarning="",hasDriverWarning="";
			$('.panel-label>span').html('状态：'+Sched.status[data.status]);


			if((!data["bidPrice"] || !data["bidTime"]) || data.status != 300) {
				$('.j-driver').data('status', true);
			}

			if(!data.bidPrice || !data.realTotalMoney){
				bidPrice = '暂无';
				bidPriceTotal = '暂无';
				hasBidWarning = 'has-warning';
				$('.j-price').data({
					'mod' : false,
					'key' : '<?= $_id;?>'
				}).html('报价');
			}
			else {
				bidPrice = data.bidPrice;
				bidPriceTotal = data.realTotalMoney;
				$('.j-price').data({
					'mod' : true,
					'key' : '<?= $_id;?>'
				}).html('修改报价');
			}

			if(!data.driver) {
				driverBid = '暂无';
				driverBidTotal = '暂无';
				hasDriverWarning = 'has-warning';
				var driverHTML = "";
				$('.j-driver').data({
					'mod' : false,
					'key' : '<?= $_id;?>'
				}).html('撮合');
			}
			else {
				driverBid = data["driver"]["bid"]["bidPrice"]
				driverBidTotal = data["driver"]["bid"]["realTotalMoney"]
				$('.j-driver').data({
					'mod' : true,
					'key' : '<?= $_id;?>'
				}).html('修改撮合');
				var driverHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">司机姓名</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["name"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">司机手机</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["phone"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["id"] || "暂无") +'" type="text"></div></div>';
			}

			var orderHTML = '<div class="form-group label-floating"><label class="control-label">状态</label><input class="form-control" readonly="readonly" value="'+ Sched.status[data.status] +'" type="text"></div><div class="form-group label-floating '+hasBidWarning+'"><label class="control-label">后台报价</label><input class="form-control" readonly="readonly" value="'+ bidPrice +'" type="text"></div><div class="form-group label-floating '+hasBidWarning+'"><label class="control-label">后台报价合计</label><input class="form-control" readonly="readonly" value="'+ bidPriceTotal +'" type="text"></div><div class="form-group label-floating '+hasDriverWarning+'"><label class="control-label">司机报价</label><input class="form-control" readonly="readonly" value="'+ driverBid +'" type="text"></div><div class="form-group label-floating '+hasDriverWarning+'"><label class="control-label">司机报价合计</label><input class="form-control" readonly="readonly" value="'+ driverBidTotal +'" type="text"></div><div class="form-group label-floating"><label class="control-label">总件数</label><input class="form-control" readonly="readonly" value="'+ data.goodsCnt +'件" type="text"></div><div class="form-group label-floating"><label class="control-label">总吨数</label><input class="form-control" readonly="readonly" value="'+ data.totalWeight +'吨" type="text"></div><div class="form-group label-floating"><label class="control-label">几装几卸</label><input class="form-control" readonly="readonly" value="'+ data.pickupDrop +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货最长</label><input class="form-control" readonly="readonly" value="'+ data.goodsMaxLen +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货最宽</label><input class="form-control" readonly="readonly" value="'+ data.goodsMaxWidth +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单号</label><input class="form-control" readonly="readonly" value="'+ data.orderNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">起点</label><input class="form-control" readonly="readonly" value="'+ data.provinceFrom+data.cityFrom+data.districtFrom +'" type="text"></div><div class="form-group label-floating"><label class="control-label">终点</label><input class="form-control" readonly="readonly" value="'+ data.provinceTo+data.cityTo+data.districtTo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">提货时间</label><input class="form-control" readonly="readonly" value="'+ t +'" type="text"></div><div class="form-group label-floating form-group-last"><label class="control-label">简介</label><input class="form-control" readonly="readonly" value="'+ data.note +'" type="text"></div>';

			$.each(data.goods, function(i, o) {
				var goodsHTML = '<div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 名称</label><input class="form-control" readonly="readonly" value="'+ o["category"].name +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 重量</label><input class="form-control" readonly="readonly" value="'+ o.count+o["category"].unit +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 提货详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressFrom +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 送达详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressTo +'" type="text"></div>';
				$goods.append(goodsHTML)
			})

			if(data["shipper"]["info"]["role"] == "pernson") {
				var name = data["shipper"]["info"]["name"] || "暂无";
			}
			else {
				var name = data["shipper"]["info"]["company"] || "暂无";
			}
			var shipperHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">货主姓名</label><input class="form-control" readonly="readonly" value="'+ name +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货主手机</label><input class="form-control" readonly="readonly" value="'+ (data["shipper"]["info"]["phone"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" value="'+ (data["shipper"]["info"]["id"] || "暂无") +'" type="text"></div></div>';

			$order.html(orderHTML)
			$shipper.append(shipperHTML)
			$shipper.append(driverHTML)

			if(data.truckList) {
				var truckDriver = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">驾驶员姓名</label><input class="form-control" readonly="readonly" value="'+ (data["truckList"]["driver"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">驾驶员手机</label><input class="form-control" readonly="readonly" value="'+ (data["truckList"]["phone"] || "暂无") +'" type="text"></div></div>';
				$trucklist.append(truckDriver);

				$.each(data.truckList["list"], function(i, o) {
					var truckInfo = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">车牌号</label><input class="form-control" readonly="readonly" value="'+ (o.plateNo || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">车辆信息</label><input class="form-control" readonly="readonly" value="'+ (o.info || "暂无") +'" type="text"></div></div>';
					$trucklist.append(truckInfo);
				})
			}
		}
	})

	$(document).on('click', '.j-price', function() {
		$('#j-submit-price').data('key', $(this).data('key'));
		$('.price-pop:eq(0)').show()
		$('.overlay:eq(0)').show()
		if($(this).data('mod')) {
			_bidUrl = "<?= $Path;?>/sched/order/mod-bid";
		}
		else {
			_bidUrl = "<?= $Path;?>/sched/order/bid";
		}
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
			url : _bidUrl,
			data : {
				orderId : k,
				price : p,
				priceType : t
			},
			success : function(data) {
				if(data.code == "0") {
					alert('提交成功！')
					$('.close-btn').click()
					window.location.reload()
				}
				else {
					alert('提交失败！')
				}
			}
		})
	})

	$(document).on('click', '.j-driver', function() {
		if(!$(this).data('status')){return}

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
		if($(this).data('mod')) {
			_driverUrl = "<?= $Path;?>/sched/order/mod-driver";
		}
		else {
			_driverUrl = "<?= $Path;?>/sched/order/driver";
		}
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
			url : _driverUrl,
			data : {
				orderId : orderId,
				driverId : driverId
			},
			success : function(data) {
				if(data.code == '0') {
					alert('提交成功！');
					$('.close-btn').click();
					window.location.reload()
				}
				else {
					alert('提交失败！');
				}
			}
		})
	})

	$('.j-cancel').on('click', function() {
		if(confirm("确定取消订单吗？")) {
			$.ajax({
				type : 'get',
				url : '<?= $Path;?>/sched/order/cancel?id=<?= $_id;?>',
				cache : false,
				async : true,
				timeout : 20000,
				success : function(data) {
					_global.badge();
					alert('订单取消成功！')
					window.location.href = '<?= $Path;?>/sched/order-web/bid-list';
				},
				error : function() {
					alert("取消订单失败，请检查网络后重试！");
				}
			})
		}
	})

	$('.close-btn').on('click', function() {
		$('#price').val('')
		$('#orderDetails').find('tbody').empty()
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
