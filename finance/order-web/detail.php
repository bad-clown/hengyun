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
		<input type="text" class="search-text" name="search" value="" placeholder="搜索"  />
		<i class="glyphicon glyphicon-search sou" ></i>
	</div>
	<div class="username">
		<a href="#"><span><?= \Yii::$app->user->identity->type;?></span></a> | <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
	</div>
</div>

<div class="content">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="<?= $Path;?>/finance/order-web/order-list">订单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="javascript:;" class="btn back-control" id="j-back-control">返回</a>
	</div>

	<div class="detail-box bid-detail">
		<div class="clearfix" id="J-order-detail">
			<div class="form-group">
				<label class="control-label ">订单号</label>
				<input class="form-control " readonly="readonly" name="orderNo" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">状态</label>
				<input class="form-control" readonly="readonly" name="status" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">总件数</label>
				<input class="form-control" readonly="readonly" name="goodsCnt" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">总吨数</label>
				<input class="form-control" readonly="readonly" name="totalWeight" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">几装几卸</label>
				<input class="form-control" readonly="readonly" name="pickupDrop" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">货最长</label>
				<input class="form-control" readonly="readonly" name="goodsMaxLen" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">货最宽</label>
				<input class="form-control" readonly="readonly" name="goodsMaxWidth" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">起点</label>
				<input class="form-control" readonly="readonly" name="provinceFrom" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">终点</label>
				<input class="form-control" readonly="readonly" name="provinceTo" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">提货时间</label>
				<input class="form-control" readonly="readonly" name="deliverTime" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">预计到达时间</label>
				<input class="form-control" readonly="readonly" name="predictArriveTime" id="predictArriveTime" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">实际到达时间</label>
				<input class="form-control" readonly="readonly" name="realArriveTime" id="realArriveTime" value="" type="text">

            </div>
			<div class="form-group">
				<label class="control-label">计量单位</label>
                <select id="goodsUnit" name="goodsUnit" class="form-control" disabled="disabled" >
                    <option value="吨" selected >吨</option>
                    <option value="方" >方</option>
                </select>
			</div>
			<div class="form-group">
				<label class="control-label">实际重量</label>
				<input class="form-control" readonly="readonly" name="realTotalWeight" value="" type="text">


            </div>

		</div>
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
		<div class="detail-label"><span class="label label-default">账务信息</span></div>
		<div class="clearfix" id="J-order-detail">
			<div class="form-group">
				<label class="control-label">单价</label>
                <input class="form-control" readonly="readonly" name="bidPrice" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">营业额</label>
				<input class="form-control" readonly="readonly" name="bidPriceTotal" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">价格类型</label>
				<select  name="bidPriceType" class="form-control bidPriceType" disabled="disabled">
					<option value="0">单价</option>
					<option value="1">一口价</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label">代付费</label>
				<input class="form-control" name="daifu" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">司机报价</label>
				<input class="form-control" readonly="readonly" name="driverBidPrice" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">司机报价合计</label>
				<input class="form-control" readonly="readonly" name="driverBidPriceTotal" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">价格类型</label>
				<select name="driverBidPriceType" class="form-control driverBidPriceType" disabled="disabled">
					<option value="0" selected>单价</option>
					<option value="1">一口价</option>
				</select>
			</div>

		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">预期税点(%)</label>
				<input class="form-control" name="predictTax" readonly="readonly" value="5" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">预期毛利润率</label>
				<input class="form-control" name="predictProfit" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">预期利润率</label>
				<input class="form-control" name="predictProfitRate" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">实际税点(%)</label>
				<input class="form-control" name="tax" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">实际毛利润率</label>
				<input class="form-control" name="realProfit" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">实际利润率</label>
				<input class="form-control" name="realProfitRate" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group select-menu">
				<label for="billing" class="control-label">是否开票</label>
				<select id="billing" name="billing" class="form-control" disabled="disabled">
					<option value="0">否</option>
					<option value="1">是</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label">开票日期</label>
				<input class="form-control" name="bilingTime" id="bilingTime" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">回单时间</label>
				<input class="form-control" name="backTime" id="backTime" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group select-menu">
				<label for="backReceived" class="control-label">回单情况</label>
				<select id="backReceived" name="backReceived" class="form-control" disabled="disabled">
					<option value="0" selected >否</option>
					<option value="1">是</option>
					<option value="2">无需回单</option>
				</select>
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group select-menu">
				<label for="receiveMoneyTime" class="control-label">收款状态</label>
				<select id="receiveMoneyTime" name="receiveMoneyTime" class="form-control" disabled="disabled">
					<option value="0">未收款</option>
					<option value="1">收款中</option>
					<option value="2">已收款</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label">收款时间</label>
				<input class="form-control" name="prmTime" id="prmTime" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">货主账单号</label>
				<input class="form-control" name="billShipperNumber"  readonly="readonly" value="" type="text">
			</div>

		</div>

		<div class="clearfix">
			<div class="form-group select-menu">
				<label for="payedStatus" class="control-label">付款状态</label>
				<select name="payedStatus" class="form-control" disabled="disabled">
					<option value="-1" selected="selected" >未支付</option>
					<option value="0">审批中</option>
					<option value="1">待支付</option>
					<option value="2">已支付</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label">付款时间</label>
				<input class="form-control" name="payTime" id="payTime" readonly="readonly" value="" type="text" disabled="disabled">
			</div>
			<div class="form-group">
				<label class="control-label">司机账单号</label>
				<input class="form-control" name="billDriverNumber" readonly="readonly" value="" type="text" >
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group form-group-last">
				<label class="control-label">备注</label>
				<input class="form-control" readonly="readonly" name="remarks" value="" type="text">
			</div>
		</div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" class="btn btn-default" id="J_Republish" >重新发布</a>
			<a href="javascript:;" class="btn btn-default" id="J_Apply">申请修改</a>
			<a href="javascript:;" class="btn btn-default J-Wait"  style="display: none;">审批中</a>
			<a href="javascript:;" class="btn btn-default J-Consent" style="display: none;">审批</a>
			<a href="javascript:;" class="btn btn-default" id="J_Change" style="display: none;">修改订单</a>
			<a href="javascript:;" class="btn btn-default" id="J_Cancel" style="display: none;">取消修改</a>
			<a href="javascript:;" class="btn btn-default" id="J_Save" style="display: none;">保存</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>
<div class="shipper-pop popup" style="z-index: 9999">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li>申请订单修改</li>
				</ul>
				<span><a href="javascript:;" class="btn btn-primary">提交</a></span>
			</div>
			<div class=" ">
				<div class="form-control-static">
					<label for="name">申请原因</label>
					<input tpye="text" class="form-control" name="reason" />
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
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
			var bidPrice,bidPriceTotal,driverBid,bidPriceType,driverBidTotal,driverBidPriceType,hasBidWarning="",hasDriverWarning="",deliverTime,predictArriveTime,realArriveTime,bilingTime,backTime,payTime,prmTime,billShipperNumber,billDriverNumber;
			if(data.status == 700) {
				$('#J_Republish').data('dis', true)
			}
			driverBidPriceType = data.driver != null ? data['driver']['bid']['bidPriceType'] : '';
			/* 订单信息 */
			deliverTime = _global.FormatTime(data.deliverTime);
			predictArriveTime = _global.FormatTime(data.predictArriveTime,1);
			realArriveTime = _global.FormatTime(data.realArriveTime,1);
			$('input[name="orderNo"]').val( data.orderNo )
			$('input[name="status"]').val( Sched.status[data.status] )
			$('input[name="goodsCnt"]').val( data.goodsCnt +'件'  )
			$('input[name="totalWeight"]')	.val( data.totalWeight +'吨' )
			$('input[name="pickupDrop"]').val( data.pickupDrop )
			$('input[name="goodsMaxLen"]').val( data.goodsMaxLen )
			$('input[name="goodsMaxWidth"]').val( data.goodsMaxWidth )
			$('input[name="provinceFrom"]').val( data.provinceFrom+data.cityFrom+data.districtFrom )
			$('input[name="provinceTo"]').val( data.provinceTo+data.cityTo+data.districtTo )
			$('input[name="deliverTime"]').val( deliverTime )
			$('input[name="predictArriveTime"]').val( predictArriveTime )
			$('input[name="realArriveTime"]').val( realArriveTime )
			$('#goodsUnit').val( (data.goodsUnit || '吨') )
			$('input[name="realTotalWeight"]').val( (data.realTotalWeight || '') + ($('#goodsUnit').val() || '') )
			$('input[name="note"]').val( data.note )

			/* 货物明细 */
			$.each(data.goods, function(i, o) {
				var goodsHTML = '<div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 名称</label><input class="form-control" readonly="readonly" value="'+ o["category"].name +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 重量</label><input class="form-control" readonly="readonly" value="'+ o.count+o["category"].unit +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 提货详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressFrom +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货物'+(i+1)+' 送达详细地址</label><input class="form-control" readonly="readonly" value="'+ o.addressTo +'" type="text"></div>';
				$goods.append(goodsHTML)
			})

			/* 实际货主信息 */
			var contactHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">实际货主联系人</label><input class="form-control" name="contact" readonly="readonly" value="'+(data.contact || '')+'" type="text"></div><div class="form-group label-floating"><label class="control-label">实际车牌号和车类型信息</label><input class="form-control" name="realCarInfo" readonly="readonly" value="'+(data.realCarInfo || '')+'" type="text"></div></div>'
			$shipper.append(contactHTML);

			/* 货主信息 */
			if(data["shipper"]["info"]["role"] == "pernson") {
				var name = data["shipper"]["info"]["name"] || "暂无";
			}
			else {
				var name = data["shipper"]["info"]["company"] || "暂无";
			}
			var shipperHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">货主姓名</label><input class="form-control" readonly="readonly" value="'+ name +'" type="text"></div><div class="form-group label-floating"><label class="control-label">货主手机</label><input class="form-control" readonly="readonly" value="'+ (data["shipper"]["info"]["phone"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" value="'+ (data["shipper"]["info"]["id"] || "暂无") +'" type="text"></div></div>';
			$shipper.append(shipperHTML)

			/* 司机信息 */
			if(data.driver != null) {
				var driverHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">司机姓名</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["name"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">司机手机</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["phone"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" value="'+ (data["driver"]["info"]["id"] || "暂无") +'" type="text"></div></div>';
			}
			$shipper.append(driverHTML)

			/* 车辆信息 */
			if(data.truckList) {
				var truckDriver = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">驾驶员姓名</label><input class="form-control" readonly="readonly" value="'+ (data["truckList"]["driver"] || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">驾驶员手机</label><input class="form-control" readonly="readonly" value="'+ (data["truckList"]["phone"] || "暂无") +'" type="text"></div></div>';
				$trucklist.append(truckDriver);

				$.each(data.truckList["list"], function(i, o) {
					var truckInfo = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">车牌号</label><input class="form-control" readonly="readonly" value="'+ (o.plateNo || "暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">车辆信息</label><input class="form-control" readonly="readonly" value="'+ (o.info || "暂无") +'" type="text"></div></div>';
					$trucklist.append(truckInfo);
				})
			}

			/* 账务信息 */
			bilingTime = _global.FormatTime(data.bilingTime,1);
			backTime = _global.FormatTime(data.backTime,1);
			payTime = _global.FormatTime(data.payTime,1);
			prmTime = _global.FormatTime(data.prmTime,1);
			bidPrice = data.bidPrice ? data.bidPrice : '';
			bidPriceTotal = data.realTotalMoney ? data.realTotalMoney : '';
			driverBid = data.driver ? data["driver"]["bid"]["bidPrice"] : '';
			driverBidTotal = data.driver ? data["driver"]["bid"]["realTotalMoney"] : '';
			billShipperNumber = $('input[name="billShipperNumber"]').val( data.billShipperNumber )
			billDriverNumber = $('input[name="billDriverNumber"]').val( data.billDriverNumber )
			$('select[name="driverBidPriceType"]').val(driverBidPriceType)
			$('select[name="bidPriceType"]').val( data.bidPriceType)
			$('input[name="remarks"]').val( (data.remarks || '') )
			$('input[name="bidPrice"]').val(  bidPrice  ).parent('.form-group').addClass(hasBidWarning)
			$('input[name="bidPriceTotal"]').val(  bidPriceTotal  ).parent('.form-group').addClass(hasBidWarning)
			$('input[name="driverBidPrice"]').val(  driverBid  ).parent('.form-group').addClass(hasDriverWarning)
			$('input[name="driverBidPriceTotal"]').val(  driverBidTotal  ).parent('.form-group').addClass(hasDriverWarning)
			$('input[name="daifu"]').val( (data.daifu || '') )
			$('input[name="predictProfit"]').val( (data.predictProfit || '') )
			$('input[name="predictProfitRate"]').val( (data.predictProfitRate || '') )
			$('input[name="outPrice"]').val( (data.outPrice || '') )
			$('input[name="outMoney"]').val( (data.outMoney || '') )
			$('input[name="outBidPrice"]').val( (data.outBidPrice || '') )
			$('input[name="tax"]').val( (data.tax || '') )
			$('input[name="realProfit"]').val( (data.realProfit || '') )
			$('input[name="realProfitRate"]').val( (data.realProfitRate || '') )
			$('select[name="billing"]').val( (data.billing || 0) ).triggerHandler("change")
			$('input[name="bilingTime"]').val( bilingTime )
			$('input[name="backTime"]').val( backTime )
			$('select[name="backReceived"]').val( (data.backReceived || 0) ).triggerHandler("change")
			$('select[name="receiveMoneyTime"]').val( (data.receiveMoneyTime  || 0) ).triggerHandler("change")
			$('input[name="payTime"]').val( payTime )
			$('input[name="prmTime"]').val( prmTime )
			$('input[name="bidPriceType"]').val( data.bidPriceType );
			if (billDriverNumber.val()) {
				$('select[name="payedStatus"]').val( (data.payedStatus || 0) ).triggerHandler("change")
			}
			if (billDriverNumber.val()  || billShipperNumber.val() ) {
				$('#J_Republish').hide();
			}
			if (bilingTime) {
				$('#billing').val(1);
			}

			if ("<?= Yii::$app->user->identity->type ;?>" == 'manager') {
				if ( data.approve == 1 ) {
					$('#J_Apply').hide()
					$('#J_Change').hide()
					$('.J-Wait').hide()
					$('.J-Consent').show()
				}
			} else {
				if ( data.approve == 1) {
					$('.J-Wait').show()
					$('#J_Apply').hide()
				}
			}

			if ( "<?=$powers;?>" ) {
				if ( data.approve == 2 ) {
					$('#J_Apply').hide()
					$('#J_Change').show()
				}
			} else {
				if ( data.approve == 2 ) {
					$(document).off('click','#J_Apply')
					$('#J_Apply').text('待财务修改')
				}
			}

		}
	})



	function profit() {
		var totalWeight = parseFloat($('input[name="totalWeight"]').val()),
			bidPrice = parseFloat($('input[name="bidPrice"]').val()),
			bidPriceTotal = parseFloat($('input[name="bidPriceTotal"]').val()),
			driverBid = parseFloat($('input[name="driverBid"]').val()),
			driverBidTotal = parseFloat($('input[name="driverBidTotal"]').val()),
			predictProfit = parseFloat($('input[name="predictProfit"]').val()),
			predictProfitRate = parseFloat($('input[name="predictProfitRate"]').val()),
			outPrice = parseFloat($('input[name="outPrice"]').val()),
			outMoney = parseFloat($('input[name="outMoney"]').val()),
			outBidPrice = parseFloat($('input[name="outBidPrice"]').val()),
			tax = parseFloat($('input[name="tax"]').val()),
			realProfit = parseFloat($('input[name="realProfit"]').val()),
			realProfitRate = parseFloat($('input[name="realProfitRate"]').val());

		if(totalWeight && outPrice) {
			var iOutMoney = outPrice*totalWeight*0.925;
			$('input[name="outMoney"]').val( iOutMoney )
			$('input[name="outBidPrice"]').val( iOutMoney )
		}

		if(bidPriceTotal && driverBidTotal && iOutMoney) {
			var iPredictProfit = bidPriceTotal-driverBidTotal-iOutMoney-(bidPriceTotal*0.05)
			var iPredictProfitRate = iPredictProfit / (bidPriceTotal-(outPrice*totalWeight))
			$('input[name="predictProfit"]').val( iPredictProfit.toFixed(2) )
			$('input[name="predictProfitRate"]').val( (iPredictProfitRate.toFixed(2))+'%' )
		}

		if(bidPriceTotal && driverBidTotal && iOutMoney && tax) {
			var iRealProfit = bidPriceTotal-driverBidTotal-iOutMoney-(bidPriceTotal*(tax/100))
			var iRealProfitRate = iRealProfit / (bidPriceTotal-(outPrice*totalWeight))
			$('input[name="realProfit"]').val( iRealProfit.toFixed(2) )
			$('input[name="realProfitRate"]').val( (iRealProfitRate.toFixed(2))+'%' )
		}

	}

	$(document).on('change', 'input[name="outPrice"]', function() {
		profit()
	})

	$(document).on('change', 'input[name="tax"]', function() {
		profit()
	})


	$(document).on('click','#J_Apply',function() {
		$('.shipper-pop').show()
		var mainheight = $(document).height()+30
		$("#mask").css({height:mainheight + 'px',width:'100%',display:'block'})
		$("#mask").show()
	})

	$('.close-btn').on('click',function(){
		$('.shipper-pop').hide()
		$('.consent-button').remove()
		$("#mask").hide()
	})

	$(document).on('click','.consent',function() {

		var apKey = $(this).data('key')
		var opinion = $('input[name="reason"]').val()

		$.ajax({
			type : "get",
			url : '<?= $Path;?>/finance/order/apply?id=<?= $_id;?>',
			data : {
				apKey : apKey,
				opinion : opinion
			},
			dataType : 'json',
			success : function(data) {
				if(data.code == 0) {
					window.location.reload();
				}
				else {
					alert('操作失败！请检查后重试！')
				}
			},
			error : function() {
				alert("操作失败，请检查网络后重试！");
			}

		})
	})


	$(document).on('click','.J-Consent',function() {
		var mainheight = $(document).height()+30
		var button = '<ul class="consent-button"><span><a href="javascript:;" class="btn btn-primary consent"  data-key="2">同意</a></span><span style="position: absolute;right: 100px"><a href="javascript:;" class="btn btn-danger consent"  data-key="-1">不同意</a></span></ul>';
		$("#mask").css({height:mainheight + 'px',width:'100%',display:'block'})
		$('.shipper-pop').show()
		$("#mask").show()
		$('.breadcrumb li').html('审批信息')
		$('.form-control-static label').remove()
		$('.breadcrumbBox span a').remove()
		$('.breadcrumbBox').append(button)

	})

	$('.popup-breadcrumb .breadcrumbBox span a').on('click',function() {

		var reason = $('input[name="reason"]').val();
		if ( !reason ) {
			alert('申请原因不能为空！')
			return false;
		}
		$.ajax({
			type : "get",
			url : '<?= $Path;?>/finance/order/apply?id=<?= $_id;?>',
			data : {
				reason : reason
			},
			dataType : 'json',
			success : function(data) {
				if(data.code == 0) {
					alert('申请成功，等待审批！')
					$('.shipper-pop').hide();
					window.location.reload();
				}
				else {
					alert('申请失败！请检查后重试！')
				}
			},
			error : function() {
				alert("申请失败，请检查网络后重试！");
			}
		})

	})

	$('#J_Change').on('click', function() {

		/* 管理员 、 账务员权限 */
		<?php if($powers){ ?>
		$('input[name="backTime"]').removeAttr('readonly').data('hasChange', true).parent('.form-group').addClass('has-warning');
		$('select[name="backReceived"]').removeAttr('disabled').parent('.form-group').addClass('has-warning');
        $('input[name="remarks"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		<?php }; ?>

		$('input[name="contact"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="realCarInfo"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="predictArriveTime"]').removeAttr('readonly').data('hasChange', true).parent('.form-group').addClass('has-warning');
		$('#goodsUnit').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		$('input[name="realTotalWeight"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="daifu"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="outPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="tax"]').attr('disabled',true).parent('.form-group');
		console.log()
		if ($('select[name="bidPriceType"]').val() == 0) {
			$('input[name="bidPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		} else {
			$('input[name="bidPriceTotal"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		}
		if ($('select[name="driverBidPriceType"]').val() == 0) {
			$('input[name="driverBidPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		} else {
			$('select[name="driverBidPriceTotal"]').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		}
		$('select[name="driverBidPriceType"]').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		$('select[name="bidPriceType"]').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		$('input[name="billing"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="realTotalWeight"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="predictArriveTime"]').focus();
		$('#J_Change').hide();
		$('#J_Republish').hide();
		$('#J_Cancel').show()
		$('#J_Save').show()
		$(window).scrollTop(0)

	})

	$(document).on('change','.bidPriceType',function () {
		if ($(this).val() == 1) {
			$('input[name="bidPriceTotal"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
			$('input[name="bidPrice"]').attr('readonly',true).parent('.form-group').removeClass('has-warning');
		} else {
			$('input[name="bidPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
			$('input[name="bidPriceTotal"]').attr('readonly',true).parent('.form-group').removeClass('has-warning');
		}
	})

	$(document).on('change','.driverBidPriceType',function () {
		if ($(this).val() == 1) {
			$('input[name="driverBidPriceTotal"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
			$('input[name="driverBidPrice"]').attr('readonly',true).parent('.form-group').removeClass('has-warning');
		} else {
			$('input[name="driverBidPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
			$('input[name="driverBidPriceTotal"]').attr('readonly',true).parent('.form-group').removeClass('has-warning');
		}
	})


    $('#J_Republish').on('click', function() {
    	if($(this).data('dis')){return false;}
        if(confirm('确定重新发布？')) {
        	$.ajax({
	            type : "post",
	                url : '<?= $Path;?>/finance/order/republish?id=<?= $_id;?>',
	                dataType : 'json',
	                success : function(data) {
	                    if(data.code == 0) {
	                        alert('重新发布成功！')
	                            window.location.reload();
	                    }
	                    else {
	                        alert('重新发布失败！请检查后重试！')
	                    }
	                },
                    error : function() {
                        alert("重新发布失败，请检查网络后重试！");
                    }
	        })
        }
    })

	$('#J_Cancel').on('click', function() {
		window.location.reload();
	})

	$(document).on('click', '#predictArriveTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#predictArriveTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates) {
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
				choose: function(dates) {
//					$('#billing').val(1);
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
				choose: function(dates) {
					if (dates) {
						$('select[name="backReceived"]').val(1);
					}
				}
			});
		}
	})

	$(document).on('blur', '#backTime', function() {
		if (!($('#backTime').val())) {
			$('select[name="backReceived"]').val(0);
		}

	})



	$(document).on('click', '#prmTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#prmTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates) {
					// $('#billTime').change()
				}
			});
		}
	})


	$('#J_Save').on('click', function() {
		var predictArriveTime = $('input[name="predictArriveTime"]').val() == '' ? '' : Date.parse($('input[name="predictArriveTime"]').val()) /1000;
		var bilingTime = $('input[name="bilingTime"]').val() == '' ? '' : Date.parse($('input[name="bilingTime"]').val()) /1000;
		var backTime = $('input[name="backTime"]').val() == '' ? '' : Date.parse($('input[name="backTime"]').val()) /1000;
		var payTime = $('input[name="payTime"]').val() == '' ? '' : Date.parse($('input[name="payTime"]').val()) /1000;
		var orderNo = $('input[name="orderNo"]').val();
        var realTotalweight = $('input[name="realTotalWeight"]').val();
        var bidPrice = $('input[name="bidPrice"]').val();
        var bidPriceTotal = $('input[name="bidPriceTotal"]').val();
        var driverBidPrice = $('input[name="driverBidPrice"]').val() ;
		var driverBidPriceTotal = $('input[name="driverBidPriceTotal"]').val();
		var driverBidPriceType = $('select[name="driverBidPriceType"]').val();
		var bidPriceType = $('select[name="bidPriceType"]').val();
		var billing = $('#billing').val();
		var data = {
				contact: $('input[name="contact"]').val(),
				realCarInfo: $('input[name="realCarInfo"]').val(),
				predictArriveTime: predictArriveTime,
				goodsUnit: $('#goodsUnit').val(),
				daifu: $('input[name="daifu"]').val(),
				predictProfit: $('input[name="predictProfit"]').val(),
				predictProfitRate: $('input[name="predictProfitRate"]').val(),
				outPrice: $('input[name="outPrice"]').val(),
				outMoney: $('input[name="outMoney"]').val(),
				outBidPrice: $('input[name="outBidPrice"]').val(),
				bidPrice: bidPrice,
				bidPriceType: bidPriceType,
				realTotalMoney : bidPriceTotal,
				orderNo: orderNo,
				billing: billing,
				tax: $('input[name="tax"]').val(),
				realProfit: $('input[name="realProfit"]').val(),
				realProfitRate: $('input[name="realProfitRate"]').val(),
				billing: $('select[name="billing"]').val(),
				bilingTime: bilingTime,
				backTime: backTime,
				backReceived: $('select[name="backReceived"]').val(),
				payTime: payTime,
				realTotalWeight: realTotalweight,
				remarks: $('input[name="remarks"]').val(),
				driverBidPrice: driverBidPrice,
				driverBidPriceTotal: driverBidPriceTotal,
				driverBidPriceType: driverBidPriceType
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
					alert('修改失败！请检查后重试！')
				}
			},
			error : function() {
				alert("修改失败，请检查网络后重试！");
			}
		})
	})

	$('#j-back-control').on('click',function(){
		window.history.go(-1);
	})

})
</script>
<?php $this->endBlock();  ?>
