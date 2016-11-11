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
<!--    <div class="search">-->
<!--        <input type="text" class="search-text" name="search" value="" placeholder="搜索订单" />-->
<!--        <i class="glyphicon glyphicon-search"></i>-->
<!--    </div>-->

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
		<div class="clearfix" id="J-order-detail">
			<div class="form-group">
				<label class="control-label">订单号</label>
				<input class="form-control" readonly="readonly" name="orderNo" value="" type="text">
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
<!--				<input class="form-control" readonly="readonly" name="goodsUnit" value="" type="text">-->
                <select id="goodsUnit" name="goodsUnit" class="form-control" disabled="disabled" >
                    <option value="吨" >吨</option>
                    <option value="方" >方</option>
                </select>
			</div>
			<div class="form-group">
				<label class="control-label">实际重量</label>
				<input class="form-control" readonly="readonly" name="realTotalWeight" value="" type="text">
			</div>
			<div class="form-group form-group-last">
				<label class="control-label">简介</label>
				<input class="form-control" readonly="readonly" name="note" value="" type="text">
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
				<label class="control-label">后台报价</label>
				<input class="form-control" readonly="readonly" name="bidPrice" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">后台报价合计</label>
				<input class="form-control" readonly="readonly" name="bidPriceTotal" value="" type="text">
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
				<label class="control-label">代付费</label>
				<input class="form-control" name="daifu" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">预期税点(%)</label>
				<input class="form-control" name="predictTax" readonly="readonly" value="5" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">预期毛利润</label>
				<input class="form-control" name="predictProfit" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">预期利润率</label>
				<input class="form-control" name="predictProfitRate" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">分出单价</label>
				<input class="form-control" name="outPrice" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">分出额</label>
				<input class="form-control" name="outMoney" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">分出一口价</label>
				<input class="form-control" name="outBidPrice" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">实际税点(%)</label>
				<input class="form-control" name="tax" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">实际毛利润</label>
				<input class="form-control" name="realProfit" readonly="readonly" value="" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">实际毛利润率</label>
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
				<label for="backReceived" class="control-label">回单是否收到</label>
				<select id="backReceived" name="backReceived" class="form-control" disabled="disabled">
					<option value="0">否</option>
					<option value="1">是</option>
					<option value="2">无需回单</option>
				</select>
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group select-menu">
				<label for="receiveMoneyTime" class="control-label">收款状态</label>
				<select id="receiveMoneyTime" name="receiveMoneyTime" class="form-control" disabled="disabled">
					<option value="0">未支付</option>
					<option value="1">支付中</option>
					<option value="1">已付中</option>
				</select>
			</div>
            <div class="form-group">
                <label class="control-label">对公金额</label>
                <input class="form-control" name="publicMoney"  readonly="readonly" value="" type="text">
            </div>
            <div class="form-group">
                <label class="control-label">对私金额</label>
                <input class="form-control" name="privateMoney" readonly="readonly" value="" type="text">
            </div>
		</div>

		<div class="clearfix">
			<div class="form-group select-menu">
				<label for="payedStatus" class="control-label">付款状态</label>
				<select id="payedStatus" name="payedStatus" class="form-control" disabled="disabled">
					<option value="0">未支付</option>
					<option value="1">支付中</option>
					<option value="1">已付中</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label">付款时间</label>
				<input class="form-control" name="payTime" id="payTime" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">付款方式：油卡金额</label>
				<input class="form-control" name="payOilAmount" readonly="readonly" value="" type="text">
			</div>
		</div>
		<div class="clearfix">
			<div class="form-group">
				<label class="control-label">付款方式：网银金额</label>
				<input class="form-control" name="payNetAmount" readonly="readonly" value="" type="text">
			</div>
		</div>

	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" class="btn btn-default" id="J_Republish" >重新发布</a>
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
			var bidPrice,bidPriceTotal,driverBid,driverBidTotal,hasBidWarning="",hasDriverWarning="",deliverTime,predictArriveTime,realArriveTime,bilingTime,backTime,payTime,privateMoney,publicMoney;
			if(data.status == 700) {
				$('#J_Republish').data('dis', true)
			}
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
			$('#goodsUnit').val( (data.goodsUnit || '') )
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
			bidPrice = data.bidPrice ? data.bidPrice : '暂无';
			bidPriceTotal = data.realTotalMoney ? data.realTotalMoney : '暂无';
			driverBid = data.driver ? data["driver"]["bid"]["bidPrice"] : '暂无';
			driverBidTotal = data.driver ? data["driver"]["bid"]["realTotalMoney"] : '暂无';

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
			$('input[name="publicMoney"]').val( (data.publicMoney || '') )
			$('input[name="privateMoney"]').val( (data.privateMoney || '') )
			$('input[name="realProfitRate"]').val( (data.realProfitRate || '') )
			$('select[name="billing"]').val( (data.billing || 0) ).triggerHandler("change")
			$('input[name="bilingTime"]').val( bilingTime )
			$('input[name="backTime"]').val( backTime )
			$('select[name="backReceived"]').val( (data.backReceived || 0) ).triggerHandler("change")
			$('select[name="receiveMoneyTime"]').val( (data.receiveMoneyTime || 0) ).triggerHandler("change")

            $('select[name="payedStatus"]').val( (data.payedStatus || 0) ).triggerHandler("change")
			$('input[name="payTime"]').val( payTime )
			$('input[name="payOilAmount"]').val( (data.payOilAmount || '') )
			$('input[name="payNetAmount"]').val( (data.payNetAmount || '') )
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

	$('#J_Change').on('click', function() {

		$('input[name="contact"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="realCarInfo"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="predictArriveTime"]').removeAttr('readonly').data('hasChange', true).parent('.form-group').addClass('has-warning');
		$('#goodsUnit').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		$('input[name="realTotalWeight"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="daifu"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="outPrice"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="tax"]').attr('disabled',true).parent('.form-group');
		$('select[name="billing"]').removeAttr('disabled').parent('.form-group').addClass('has-warning');
		$('input[name="bilingTime"]').attr('disabled',true).parent('.form-group');
		$('input[name="backTime"]').attr('disabled',true).parent('.form-group');
		$('select[name="backReceived"]').attr('disabled',true).parent('.form-group');
		$('input[name="payTime"]').attr('disabled',true).parent('.form-group');
		$('input[name="billing"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="payOilAmount"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="payNetAmount"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('input[name="realTotalWeight"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
        $('input[name="publicMoney"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
        $('input[name="privateMoney"]').removeAttr('readonly').parent('.form-group').addClass('has-warning');
		$('#J_Change').hide();
		$('#J_Republish').hide();
		$('#J_Cancel').show()
		$('#J_Save').show()
		$(window).scrollTop(0)
		$('input[name="predictArriveTime"]').focus();

	})

    $('#J_Republish').on('click', function() {
    	if($(this).data('dis')){return false;}
        if(confirm('确实重新发布？')) {
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
		var predictArriveTime = $('input[name="predictArriveTime"]').val() == '' ? '' : Date.parse($('input[name="predictArriveTime"]').val()) /1000;
		var bilingTime = $('input[name="bilingTime"]').val() == '' ? '' : Date.parse($('input[name="bilingTime"]').val()) /1000;
		var backTime = $('input[name="backTime"]').val() == '' ? '' : Date.parse($('input[name="backTime"]').val()) /1000;
		var payTime = $('input[name="payTime"]').val() == '' ? '' : Date.parse($('input[name="payTime"]').val()) /1000;
        var realTotalweight = parseInt($('input[name="realTotalWeight"]').val());
        var realtotalmoney = realTotalweight * parseInt($('input[name="bidPrice"]').val());

		var data = {
			contact : $('input[name="contact"]').val(),
			realCarInfo : $('input[name="realCarInfo"]').val(),
			predictArriveTime : predictArriveTime,
			goodsUnit : $('#goodsUnit').val(),
			daifu : $('input[name="daifu"]').val(),
			predictProfit : $('input[name="predictProfit"]').val(),
			predictProfitRate : $('input[name="predictProfitRate"]').val(),
			outPrice : $('input[name="outPrice"]').val(),
			outMoney : $('input[name="outMoney"]').val(),
			outBidPrice : $('input[name="outBidPrice"]').val(),
            realTotalMoney : realtotalmoney,
			tax : $('input[name="tax"]').val(),
			realProfit : $('input[name="realProfit"]').val(),
			realProfitRate : $('input[name="realProfitRate"]').val(),
			billing : $('select[name="billing"]').val(),
			bilingTime : bilingTime,
			backTime : backTime,
			backReceived : $('select[name="backReceived"]').val(),
			receiveMoneyTime : $('select[name="receiveMoneyTime"]').val(),
			payedStatus : $('select[name="payedStatus"]').val(),
			payTime : payTime,
			payOilAmount : $('input[name="payOilAmount"]').val(),
			payNetAmount : $('input[name="payNetAmount"]').val(),
			realTotalWeight : realTotalweight,
            privateMoney: $('input[name="privateMoney"]').val(),
            publicMoney: $('input[name="publicMoney"]').val()
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


})
</script>
<?php $this->endBlock();  ?>
