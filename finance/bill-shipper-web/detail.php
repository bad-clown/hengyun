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
			<li><a href="<?= $Path;?>/finance/bill-shipper-web/list">账单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="javascript:;" id="j-save-control" class="btn save-control" style="display: none;">保存</a>
		<a href="javascript:;" id="j-cancel-control" class="btn cancel-control" style="display: none;">取消</a>
		<a href="javascript:;" id="j-back-control" class="btn back-control">返回</a>
	</div>

	<div class="detail-box">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>
		<div class="clearfix" id="J-bill-detail"></div>

		<div class="detail-label" ><a href="javascript:;" id="J-show" ><span class="label label-default" >更多信息</span></a></div>
		<br>
		<div class="clearfix" id="J-bill-detail1" style="display: none" ></div>
		<div class="detail-label">
			<span class="label label-default">订单明细</span>
		</div>
		<div class="clearfix">
			<table class="table table-striped table-hover" id="orderList">
				<thead>
					<tr>
						<th>订单号</th>
						<th>提货时间</th>
						<th>起点</th>
						<th>终点</th>
						<th>总件数</th>
						<th>总吨数</th>
						<th>几装几卸</th>
						<th>司机报价</th>
						<th>后台报价</th>
						<th>回单状态</th>
						<th>代付款</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="content">

<!--	<div class="detail-box pt15" id="J-note-detail">-->
<!--		<div class="detail-label"><span class="label label-default">开票信息</span></div>-->
<!--	</div>-->
<!--	<div class="detail-box pt15 pb100" id="J-mail-detail">-->
<!--		<div class="detail-label"><span class="label label-default">邮寄信息</span></div>-->
<!--	</div>-->
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" id="j-mod-bill" style="display: none;">修改账单</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
$(function() {
	var _bidUrl = "", _driverUrl = "";
	var status = {
			0 : "未收款",
			1 : "收款中",
			2 : "已收款"
		};
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/finance/bill-shipper/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(d) {
			var data = d.data;
			var $bill = $('#J-bill-detail');
			var $note = $('#J-note-detail');
			var $mail = $('#J-mail-detail');
			var $order = $('#orderList').find('tbody');
			var billTime = _global.FormatTime(data.billTime, 1);
			var prmTime = _global.FormatTime(data.prmTime, 1);
			var billHTML = '<div class="form-group label-floating select-menu"><label for="status" class="control-label">账单状态</label><select id="status" name="status" class="form-control" disabled="disabled"><option value="0">未收款</option><option value="1">收款中</option><option value="2">已收款</option></select></div><div class="form-group label-floating"><label class="control-label">账单编号</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.billNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票时间</label><input class="form-control" readonly="readonly" name="billTime" value="'+ (billTime || '') +'"></div><div class="form-group label-floating"><label class="control-label">货主姓名</label><input class="form-control" readonly="readonly" name="billNo" value="'+ (data.shipper.name || '') +'" type="text"></div><div class="form-group label-floating"><label class="control-label">联系电话</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.shipper.phone +'" type="text"></div><div class="form-group label-floating"><label class="control-label">总金额</label><input class="form-control" readonly="readonly" name="totalMoney" value="'+ data.amount +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单个数</label><input class="form-control" readonly="readonly" name="orderCnt" value="'+ data.orderCnt +'" type="text"></div><div class="form-group label-floating"><label class="control-label">运费</label><input class="form-control" name="realTotalMoney" readonly="readonly" value="'+ data.totalMoney +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票金额</label><input class="form-control" readonly="readonly" name="invoiceMoney" value="'+ (data.invoiceMoney || '') +'" type="text" placeholder="0"></div><div class="form-group label-floating"><label class="control-label">代付费</label><input class="form-control" name="daifu" readonly="readonly" value="'+ (data.daifu || '') +'" type="text" placeholder="0"></div><div class="form-group form-group-last"><label class="control-label">备注</label><input class="form-control" readonly="readonly" name="remarks" type="text" placeholder="无" value="'+ (data.remarks || '') +'" ></div>';

			$bill.append(billHTML);

			var $bill1 = $('#J-bill-detail1');
			var billHTML = '<div class="form-group "><label class="control-label">开票抬头</label><input class="form-control" readonly="readonly" name="title" value="'+ data.title +'" type="text"></div><div class="form-group"><label class="control-label">对公金额</label><input class="form-control" name="publicMoney" readonly="readonly" value="'+ ( data.publicMoney || '') +'" type="text" placeholder="0"></div><div class="form-group"><label class="control-label">对私金额</label><input class="form-control" name="privateMoney" readonly="readonly" value="'+ ( data.privateMoney || '') +'" type="text" placeholder="0"></div><div class="form-group"><label class="control-label">收款时间</label><input class="form-control" name="prmTime" id="prmTime" readonly="readonly" value="'+ prmTime +'" type="text"></div>';
			$bill1.append(billHTML);

			$('select[name="status"]').val(data.status).triggerHandler("change");

			$.each(data.orderList, function(i, o) {
				var deliverTime = _global.FormatTime(o.deliverTime);
				var orderHTML = '<tr data-key="'+o._id+'" data-order="'+ o.orderNo +'"><td>'+o.orderNo+'</td><td>'+deliverTime+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>'+o.goodsCnt+'件</td><td>'+(o.realTotalWeight || 0)+'</td><td>'+o.pickupDrop+'</td><td>'+ (o["driverBid"]["realTotalMoney"] || '') +'</td><td>'+o.realTotalMoney+'</td><td>'+o.backReceived+'</td><td> '+ (o.daifu || '') +' </td></tr>';
				$order.append(orderHTML);
			})

			data.status != 2 ? $('#j-mod-bill').show() : '';
		}

	})



	$('#j-mod-bill').on('click', function() {
		$('#j-save-control').show()
		$('#j-cancel-control').show()
		$('#j-back-control').hide()
		$('#J-bill-detail1').show();
		$('select[name="status"]').val() == 2 || $('select[name="status"]').removeAttr('disabled')
 		$('input[name="billTime"]').attr('id', 'billTime').parent('.form-group').addClass('has-warning')
		$('input[name="title"]').removeAttr('readonly', 'readonly').parent('.form-group').addClass('has-warning')
		$('input[name="invoiceMoney"]').removeAttr('readonly', 'readonly').parent('.form-group').addClass('has-warning')
		$('input[name="privateMoney"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="publicMoney"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="remarks"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="prmTime"]').removeAttr('readonly').data('hasChange',true).parent('.form-group')
		$('#status option').each(function(){
			if ($('#status').val() > $(this).val()) {
				$(this).prop('disabled',true);
			}
		})
		$('.control-panel').hide();
		$(window).scrollTop(0)
	})

	$('#j-cancel-control').on('click', function() {
		window.location.reload();
	})

	$(document).on('click', '#billTime', function() {
		laydate({
			elem: '#billTime',
			format: 'YYYY-MM-DD hh:mm:ss',
			istime: true,
			istoday: false,
			choose: function(dates){
				// $('#billTime').change()
			}
		});
	})

	$('#j-save-control').on('click', function() {
		var $orderList = $('#orderList>tbody').find("tr");
		var orderList = [];
		var orderNo = [];
		for(var i=0;i<$orderList.length;i++) {
			orderList.push($orderList.eq(i).data('key'));
			orderNo.push($orderList.eq(i).data('order'));
		}
		var billTime = Date.parse($('input[name="billTime"]').val()) /1000 || '';
		var prmTime =  Date.parse($('input[name="prmTime"]').val()) /1000 || '';
		var remarks = $('input[name="remarks"]').val() || '';
		var invoiceMoney = $('input[name="invoiceMoney"]').val() || '';
		var data = {
			status : $('select[name="status"]').val(),
			billNo : $('input[name="billNo"]').val(),
			billTime : billTime,
			prmTime : prmTime,
			remarks : remarks,
			invoiceMoney : invoiceMoney,
			orderCnt : parseInt($('input[name="orderCnt"]').val()),
			title : $('input[name="title"]').val(),
			privateMoney : $('input[name="privateMoney"]').val(),
			publicMoney : $('input[name="publicMoney"]').val(),
			orderList : orderList || [],
			orderNo : orderNo || []
		}

		$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-shipper/modify?id=<?= $_id;?>",
			data : data,
			dataType : 'json',
			success : function(data) {
				if(data.code == '0') {
					alert('保存成功！');
					window.location.reload();
				}
				else {
					alert('保存失败，请检查账单后重试！');
				}
			},
			erroe : function() {
				alert("保存失败，请检查网络后重试！");
			}
		})
	})

	$(document).on('click', '#prmTime', function() {
		if($(this).data('hasChange')) {
			laydate({
				elem: '#prmTime',
				format: 'YYYY-MM-DD hh:mm:ss',
				istime: true,
				istoday: false,
				choose: function(dates){
					// $('#billTime').change()
				}
			});
		}
	})

	$('#j-back-control').on('click',function(){
		window.history.go(-1);
	})

	$('#J-show').on('click',function(){
		$('#J-bill-detail1').toggle('show');
	})

	$(document).on('click', '#create-order-detail', function() {
		$('.order-detail-pop').show();
		$('.overlay:eq(0)').show();
	})

	$('.close-btn').on('click', function() {
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
