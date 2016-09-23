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
			<li><a href="<?= $Path;?>/finance/bill-driver-web/list">账单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="javascript:;" id="j-save-control" class="btn save-control" style="display: none;">保存</a>
		<a href="javascript:;" id="j-cancel-control" class="btn cancel-control" style="display: none;">取消</a>
		<a href="<?= $Path;?>/finance/bill-driver-web/list" id="j-back-control" class="btn back-control">返回</a>
	</div>

	<div class="detail-box pb100">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>
		<div class="clearfix" id="J-bill-detail"></div>
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
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" id="j-mod-bill">修改账单</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript">
$(function() {
	var _bidUrl = "", _driverUrl = "";
	var status = {
			0 : "未支付",
			1 : "支付中",
			2 : "已支付"
		};
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/finance/bill-driver/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(d) {
			var data = d.data;
			var $bill = $('#J-bill-detail');
			var $order = $('#orderList').find('tbody');
			var billTime = _global.FormatTime(data.billTime, 1);

			var billHTML = '<div class="form-group label-floating select-menu"><label for="status" class="control-label">账单状态</label><select id="status" name="status" class="form-control" disabled="disabled"><option value="0">未支付</option><option value="1">支付中</option><option value="2">已支付</option></select></div><div class="form-group label-floating"><label class="control-label">账单编号</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.billNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">司机姓名</label><input class="form-control" readonly="readonly" name="billNo" value="'+ (data.driver.name || '暂无') +'" type="text"></div><div class="form-group label-floating"><label class="control-label">联系电话</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.driver.phone +'" type="text"></div><div class="form-group label-floating"><label class="control-label">总金额</label><input class="form-control" readonly="readonly" name="totalMoney" value="'+ data.totalMoney +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单个数</label><input class="form-control" readonly="readonly" name="orderCnt" value="'+ data.orderCnt +'" type="text"></div>';

			$bill.append(billHTML);

			$('select[name="status"]').val(data.status).triggerHandler("change");

			$.each(data.orderList, function(i, o) {
				var deliverTime = _global.FormatTime(o.deliverTime);
				var orderHTML = '<tr data-key="'+o._id+'"><td>'+o.orderNo+'</td><td>'+deliverTime+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>'+o.goodsCnt+'件</td><td>'+(o.realTotalWeight || 0)+'</td><td>'+o.pickupDrop+'</td><td>'+o["driverBid"]["realTotalMoney"]+'</td><td>'+o.realTotalMoney+'</td></tr>';
				$order.append(orderHTML);
			})
		}
	})

	$('#j-mod-bill').on('click', function() {
		$('#j-save-control').show()
		$('#j-cancel-control').show()
		$('#j-back-control').hide()

		$('select[name="status"]').removeAttr('disabled')

		$('.control-panel').hide();
		$(window).scrollTop(0)
	})
	$('#j-cancel-control').on('click', function() {
		$('#j-save-control').hide()
		$('#j-cancel-control').hide()
		$('#j-back-control').show()

		$('select[name="status"]').attr('disabled', 'disabled')

		$('.control-panel').show();
	})


	$('#j-save-control').on('click', function() {
		var $orderList = $('#orderList>tbody').find("tr");
		var orderList = [];
		for(var i=0;i<$orderList.length;i++) {
			orderList.push($orderList.eq(i).data('key'));
		}

		var data = {
			status : $('select[name="status"]').val(),
			billNo : $('input[name="billNo"]').val(),
			totalMoney : $('input[name="totalMoney"]').val(),
			orderCnt : parseInt($('input[name="orderCnt"]').val()),
			orderList : orderList || []
		}

		// console.log(data)

		$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-driver/modify?id=<?= $_id;?>",
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
