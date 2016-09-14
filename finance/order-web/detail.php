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
			<li><a href="<?= $Path;?>/finance/order-web/order-list">订单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="<?= $Path;?>/finance/order-web/order-list" class="btn back-control">返回</a>
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
	<div class="detail-box bid-detail pt15" id="J-trucklist-detail">
		<div class="detail-label"><span class="label label-default">车辆信息</span></div>
	</div>
</div>

<div class="content">
	<div class="detail-box bid-detail pt15 pb100" id="J-extra-detail">
		<div class="detail-label"><span class="label label-default">额外信息</span></div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" class="btn btn-default" id="J_Change">修改订单</a>
			<a href="javascript:;" class="btn btn-default" id="J_Cancel" style="display: none;">取消修改</a>
			<a href="javascript:;" class="btn btn-default" id="J_Save" style="display: none;">保存</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript">
$(function() {
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/finance/order/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			var $order = $('#J-order-detail');
			var $goods = $('#J-goods-detail');
			var $shipper = $('#J-shipper-detail');
			var $trucklist = $('#J-trucklist-detail');
			var $extra = $('#J-extra-detail');
			var t = _global.FormatTime(data.deliverTime);
			var bidPrice,bidPriceTotal,driverBid,driverBidTotal,hasBidWarning="",hasDriverWarning="";
			$('.bid-label>span').html('状态：'+Sched.status[data.status]);


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


			var predictArriveTime = _global.FormatTime(data.predictArriveTime,1);
			var bilingTime = _global.FormatTime(data.bilingTime,1);
			var backTime = _global.FormatTime(data.backTime,1);
			var prePayTime = _global.FormatTime(data.prePayTime,1);
			var payTime = _global.FormatTime(data.payTime,1);
			var extraHTML = '<div class="clearfix" id="J-order-detail"><div class="form-group label-floating"><label class="control-label">实际货主联系人</label><input class="form-control" name="contact" readonly="readonly" value="'+(data.contact || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际车牌号和车类型信息</label><input class="form-control" name="realCarInfo" readonly="readonly" value="'+(data.realCarInfo || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预计到达时间</label><input class="form-control" name="predictArriveTime" id="predictArriveTime" readonly="readonly" value="'+predictArriveTime+'" type="text"></div><div class="form-group label-floating"><label class="control-label">计量单位</label><input class="form-control" name="unit" readonly="readonly" value="'+(data.unit || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际报价</label><input class="form-control" name="realBidPrice" readonly="readonly" value="'+(data.realBidPrice || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际报价类型</label><input class="form-control" name="realBidPriceType" readonly="readonly" value="'+(data.realBidPriceType || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">是否开票</label><input class="form-control" name="billing" readonly="readonly" value="'+(data.billing || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票日期</label><input class="form-control" name="bilingTime" id="bilingTime" readonly="readonly" value="'+bilingTime+'" type="text"></div><div class="form-group label-floating"><label class="control-label">分出单价</label><input class="form-control" name="outPrice" readonly="readonly" value="'+(data.outPrice || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">分出额</label><input class="form-control" name="outMoney" readonly="readonly" value="'+(data.outMoney || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">分出一口价</label><input class="form-control" name="outBidPrice" readonly="readonly" value="'+(data.outBidPrice || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际税点</label><input class="form-control" name="tax" readonly="readonly" value="'+(data.tax || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预期税点</label><input class="form-control" name="predictTax" readonly="readonly" value="'+(data.predictTax || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预期毛利润</label><input class="form-control" name="predictProfit" readonly="readonly" value="'+(data.predictProfit || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预期利润率</label><input class="form-control" name="predictProfitRate" readonly="readonly" value="'+(data.predictProfitRate || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际毛利润</label><input class="form-control" name="realProfit" readonly="readonly" value="'+(data.realProfit || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际毛利润率</label><input class="form-control" name="realProfitRate" readonly="readonly" value="'+(data.realProfitRate || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">回单时间</label><input class="form-control" name="backTime" id="backTime" readonly="readonly" value="'+backTime+'" type="text"></div><div class="form-group label-floating"><label class="control-label">付款方式</label><input class="form-control" name="payType" readonly="readonly" value="'+(data.payType || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预计付款时间</label><input class="form-control" name="prePayTime" id="prePayTime" readonly="readonly" value="'+prePayTime+'" type="text"></div><div class="form-group label-floating"><label class="control-label">预计付款金额</label><input class="form-control" name="prePayMoney" readonly="readonly" value="'+(data.prePayMoney || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">付款时间</label><input class="form-control" name="payTime" id="payTime" readonly="readonly" value="'+payTime+'" type="text"></div><div class="form-group label-floating"><label class="control-label">付款说明</label><input class="form-control" name="payInfo" readonly="readonly" value="'+(data.payInfo || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">收款状态</label><input class="form-control" name="receiveMoneyTime" readonly="readonly" value="'+(data.receiveMoneyTime || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">获取类型</label><input class="form-control" name="goodsType" readonly="readonly" value="'+(data.goodsType || '')+'" type="text"></div></div>';
			$extra.append(extraHTML)
		}
	})

	$('#J_Change').on('click', function() {
		$('input[name="contact"]').removeAttr('readonly');
		$('input[name="realCarInfo"]').removeAttr('readonly');
		$('input[name="predictArriveTime"]').removeAttr('readonly').data('hasChange', true);
		$('input[name="unit"]').removeAttr('readonly');
		$('input[name="realBidPrice"]').removeAttr('readonly');
		$('input[name="realBidPriceType"]').removeAttr('readonly');
		$('input[name="billing"]').removeAttr('readonly');
		$('input[name="bilingTime"]').removeAttr('readonly').data('hasChange', true);
		$('input[name="outPrice"]').removeAttr('readonly');
		$('input[name="outMoney"]').removeAttr('readonly');
		$('input[name="outBidPrice"]').removeAttr('readonly');
		$('input[name="tax"]').removeAttr('readonly');
		$('input[name="predictTax"]').removeAttr('readonly');
		$('input[name="predictProfit"]').removeAttr('readonly');
		$('input[name="predictProfitRate"]').removeAttr('readonly');
		$('input[name="realProfit"]').removeAttr('readonly');
		$('input[name="realProfitRate"]').removeAttr('readonly');
		$('input[name="backTime"]').removeAttr('readonly').data('hasChange', true);
		$('input[name="payType"]').removeAttr('readonly');
		$('input[name="prePayTime"]').removeAttr('readonly').data('hasChange', true);
		$('input[name="prePayMoney"]').removeAttr('readonly');
		$('input[name="payTime"]').removeAttr('readonly').data('hasChange', true);
		$('input[name="payInfo"]').removeAttr('readonly');
		$('input[name="receiveMoneyTime"]').removeAttr('readonly');
		$('input[name="goodsType"]').removeAttr('readonly');
		$('#J_Change').hide();
		$('#J_Cancel').show()
		$('#J_Save').show()
		$('input[name="contact"]').focus();
	})

	$('#J_Cancel').on('click', function() {
		$('input[name="contact"]').attr('readonly', 'readonly');
		$('input[name="realCarInfo"]').attr('readonly', 'readonly');
		$('input[name="predictArriveTime"]').attr('readonly', 'readonly').data('hasChange', false);
		$('input[name="unit"]').attr('readonly', 'readonly');
		$('input[name="realBidPrice"]').attr('readonly', 'readonly');
		$('input[name="realBidPriceType"]').attr('readonly', 'readonly');
		$('input[name="billing"]').attr('readonly', 'readonly');
		$('input[name="bilingTime"]').attr('readonly', 'readonly').data('hasChange', false);
		$('input[name="outPrice"]').attr('readonly', 'readonly');
		$('input[name="outMoney"]').attr('readonly', 'readonly');
		$('input[name="outBidPrice"]').attr('readonly', 'readonly');
		$('input[name="tax"]').attr('readonly', 'readonly');
		$('input[name="predictTax"]').attr('readonly', 'readonly');
		$('input[name="predictProfit"]').attr('readonly', 'readonly');
		$('input[name="predictProfitRate"]').attr('readonly', 'readonly');
		$('input[name="realProfit"]').attr('readonly', 'readonly');
		$('input[name="realProfitRate"]').attr('readonly', 'readonly');
		$('input[name="backTime"]').attr('readonly', 'readonly').data('hasChange', false);
		$('input[name="payType"]').attr('readonly', 'readonly');
		$('input[name="prePayTime"]').attr('readonly', 'readonly').data('hasChange', false);
		$('input[name="prePayMoney"]').attr('readonly', 'readonly');
		$('input[name="payTime"]').attr('readonly', 'readonly').data('hasChange', false);
		$('input[name="payInfo"]').attr('readonly', 'readonly');
		$('input[name="receiveMoneyTime"]').attr('readonly', 'readonly');
		$('input[name="goodsType"]').attr('readonly', 'readonly');
		$('#J_Change').show();
		$('#J_Cancel').hide()
		$('#J_Save').hide()
	})

	$(document).on('click', '#predictArriveTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#predictArriveTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})
	$(document).on('click', '#bilingTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#bilingTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})
	$(document).on('click', '#backTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#backTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})
	$(document).on('click', '#prePayTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#prePayTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})
	$(document).on('click', '#payTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#payTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})

	$('#J_Save').on('click', function() {
		var data = {
			contact : $('input[name="contact"]').val(),
			realCarInfo : $('input[name="realCarInfo"]').val(),
			predictArriveTime : Date.parse($('input[name="predictArriveTime"]').val()) /1000,
			unit : $('input[name="unit"]').val(),
			realBidPrice : $('input[name="realBidPrice"]').val(),
			realBidPriceType : $('input[name="realBidPriceType"]').val(),
			billing : $('input[name="billing"]').val(),
			bilingTime : Date.parse($('input[name="bilingTime"]').val()) /1000,
			outPrice : $('input[name="outPrice"]').val(),
			outMoney : $('input[name="outMoney"]').val(),
			outBidPrice : $('input[name="outBidPrice"]').val(),
			tax : $('input[name="tax"]').val(),
			predictTax : $('input[name="predictTax"]').val(),
			predictProfit : $('input[name="predictProfit"]').val(),
			predictProfitRate : $('input[name="predictProfitRate"]').val(),
			realProfit : $('input[name="realProfit"]').val(),
			realProfitRate : $('input[name="realProfitRate"]').val(),
			backTime : Date.parse($('input[name="backTime"]').val()) /1000,
			payType : $('input[name="payType"]').val(),
			prePayTime : Date.parse($('input[name="prePayTime"]').val()) /1000,
			prePayMoney : $('input[name="prePayMoney"]').val(),
			payTime : Date.parse($('input[name="payTime"]').val()) /1000,
			payInfo : $('input[name="payInfo"]').val(),
			receiveMoneyTime : $('input[name="receiveMoneyTime"]').val(),
			goodsType : $('input[name="goodsType"]').val()
		}

		$.ajax({
			type : "post",
			url : '<?= $Path;?>/finance/order/update?id=<?= $_id;?>',
			dataType : 'json',
			data : data,
			success : function(data) {
				if(data.code == 0) {
					alert('修改成功！')
					window.location.reload();
				}
				else {
					alert('修改失败！')
				}
			},
			error : function() {
				alert("修改失败，请检查网络后重试！");
			}
		})
	})


})
</script>
<?php $this->endBlock();  ?>
