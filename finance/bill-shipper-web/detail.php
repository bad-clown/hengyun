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
			<li><a href="<?= $Path;?>/finance/bill-shipper-web/list">账单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="javascript:;" id="j-save-control" class="save-control" style="display: none;">保存</a>
		<a href="javascript:;" id="j-cancel-control" class="cancel-control" style="display: none;">取消</a>
		<a href="<?= $Path;?>/finance/bill-shipper-web/list" id="j-back-control" class="back-control">返回</a>
	</div>

	<div class="detail-box">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>
		<div class="clearfix" id="J-bill-detail"></div>
		<div class="detail-label">
			<span class="label label-default">订单明细</span>
			<div class="btn-create-detail btn-disabled">
				<span class="glyphicon glyphicon-plus"></span>
				<a href="javascript:;">添加明细</a>
			</div>
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
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="content">
	<div class="detail-box pt15" id="J-note-detail">
		<div class="detail-label"><span class="label label-default">开票信息</span></div>
	</div>
	<div class="detail-box pt15 pb100" id="J-mail-detail">
		<div class="detail-label"><span class="label label-default">邮寄信息</span></div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" id="j-mod-bill">修改账单</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>

<div class="order-detail-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li>账单管理</li>
					<li>查看详情</li>
					<li class="active">添加明细</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary" id="order-complete" title="确认">确认</a>
			</div>
			<div class="orderDetailBox clearfix">
				<table class="table table-striped table-hover" id="orderDetailList">
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
<div class="overlay"></div>

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
		url : "<?= $Path;?>/finance/bill-shipper/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(d) {
			var data = d.data;
			var $bill = $('#J-bill-detail');
			var $note = $('#J-note-detail');
			var $mail = $('#J-mail-detail');
			var $order = $('#orderList').find('tbody');
			var billTime = _global.FormatTime(data.billTime, 1);

			var billHTML = '<div class="form-group label-floating select-menu"><label for="status" class="control-label">账单状态</label><select id="status" name="status" class="form-control" disabled="disabled"><option value="0">未支付</option><option value="1">支付中</option><option value="2">已支付</option></select></div><div class="form-group label-floating"><label class="control-label">账单编号</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.billNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票时间</label><input class="form-control" readonly="readonly" name="billTime" value="'+ billTime +'"></div><div class="form-group label-floating"><label class="control-label">开票总金额</label><input class="form-control" readonly="readonly" name="totalMoney" value="'+ data.totalMoney +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单个数</label><input class="form-control" readonly="readonly" name="orderCnt" value="'+ data.orderCnt +'个" type="text"></div>';

			$bill.append(billHTML);

			$('select[name="status"]').val(data.status).triggerHandler("change");

			$.each(data.orderList, function(i, o) {
				var deliverTime = _global.FormatTime(o.deliverTime);
				var orderHTML = '<tr data-key="'+o._id+'"><td>'+o.orderNo+'</td><td>'+deliverTime+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>'+o.goodsCnt+'件</td><td>'+(o.realTotalWeight || 0)+'</td><td>'+o.pickupDrop+'</td><td>'+o["driverBid"]["realTotalMoney"]+'</td><td>'+o.realTotalMoney+'</td><td width="100"><a class="btn-danger btn-disabled" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
				$order.append(orderHTML);
			})

			var noteHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">开票抬头</label><input class="form-control" readonly="readonly" name="title" value="'+ data.title +'" type="text"></div><div class="form-group label-floating"><label class="control-label">税号</label><input class="form-control" readonly="readonly" name="tfn" value="'+ data.tfn +'" type="text"></div><div class="form-group label-floating"><label class="control-label">地址</label><input class="form-control" readonly="readonly" name="address" value="'+ data.address +'" type="text"></div><div class="form-group label-floating"><label class="control-label">电话</label><input class="form-control" readonly="readonly" name="tel" value="'+ data.tel +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开户行</label><input class="form-control" readonly="readonly" name="bank" value="'+ data.bank +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开户行账号</label><input class="form-control" readonly="readonly" name="bankId" value="'+ data.bankId +'" type="text"></div></div>';

			$note.append(noteHTML);

			var mailHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">邮寄地址</label><input class="form-control" readonly="readonly" name="mailAddress" value="'+ data.mailAddress +'" type="text"></div><div class="form-group label-floating"><label class="control-label">联系电话</label><input class="form-control" readonly="readonly" name="mailTel" value="'+ data.mailTel +'" type="text"></div></div>';

			$mail.append(mailHTML);

		}
	})

	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/finance/bill-shipper/unpayed-orders?userId=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			console.log(data)
		}
	})

	$('#j-mod-bill').on('click', function() {
		$('#j-save-control').show()
		$('#j-cancel-control').show()
		$('#j-back-control').hide()
		$('.btn-create-detail').removeClass('btn-disabled')
		$('.btn-create-detail>a').attr('id', 'create-order-detail')
		$('.btn-danger').removeClass('btn-disabled')

		$('select[name="status"]').removeAttr('disabled')
 		$('input[name="billTime"]').attr('id', 'billTime')
		$('input[name="title"]').attr('readonly', 'readonly')
		$('input[name="tfn"]').attr('readonly', 'readonly')
		$('input[name="address"]').removeAttr('readonly')
		$('input[name="tel"]').removeAttr('readonly')
		$('input[name="bank"]').removeAttr('readonly')
		$('input[name="bankId"]').removeAttr('readonly')
		$('input[name="mailAddress"]').removeAttr('readonly')
		$('input[name="mailTel"]').removeAttr('readonly')

		$('.control-panel').hide();
		$(window).scrollTop(0)
	})
	$('#j-cancel-control').on('click', function() {
		$('#j-save-control').hide()
		$('#j-cancel-control').hide()
		$('#j-back-control').show()
		$('.btn-create-detail').addClass('btn-disabled')
		$('.btn-create-detail>a').removeAttr('id')
		$('.btn-danger').addClass('btn-disabled')

		$('select[name="status"]').attr('disabled', 'disabled')
 		$('input[name="billTime"]').removeAttr('id')
		$('input[name="title"]').attr('readonly', 'readonly')
		$('input[name="tfn"]').attr('readonly', 'readonly')
		$('input[name="address"]').attr('readonly', 'readonly')
		$('input[name="tel"]').attr('readonly', 'readonly')
		$('input[name="bank"]').attr('readonly', 'readonly')
		$('input[name="bankId"]').attr('readonly', 'readonly')
		$('input[name="mailAddress"]').attr('readonly', 'readonly')
		$('input[name="mailTel"]').attr('readonly', 'readonly')

		$('.control-panel').show();
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
		for(var i=0;i<$orderList.length;i++) {
			orderList.push($orderList.eq(i).data('key'));
		}

		if(orderList.length<1) {alert('未选订单明细');return;}
		var billTime = Date.parse($('input[name="billTime"]').val()) /1000;

		var data = {
			id : '<?= $_id;?>',
			status : $('select[name="status"]').val(),
			billNo : $('input[name="billNo"]').val(),
			billTime : billTime,
			totalMoney : $('input[name="totalMoney"]').val(),
			orderCnt : $('input[name="orderCnt"]').val(),
			title : $('input[name="title"]').val(),
			tfn : $('input[name="tfn"]').val(),
			address : $('input[name="address"]').val(),
			tel : $('input[name="tel"]').val(),
			bank : $('input[name="bank"]').val(),
			bankId : $('input[name="bankId"]').val(),
			mailAddress : $('input[name="mailAddress"]').val(),
			mailTel : $('input[name="mailTel"]').val(),
			orderList : orderList || []
		}

		console.log(data)

		/*$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-shipper/modify",
			data : data,
			dataType : 'json',
			success : function(data) {
				if(data.code == '0') {
					alert('保存成功！');
					window.location.href="<?= $Path;?>/finance/bill-shipper-web/list";
				}
				else {
					alert('保存失败，请检查账单后重试！');
				}
			},
			erroe : function() {
				alert("保存失败，请检查网络后重试！");
			}
		})*/
	})

	$('#order-complete').on('click', function() {
		var $orderList = $('#orderList').find('tbody');
		var $orderDetailList = $('#orderDetailList').find('tr');

		$orderDetailList.each(function(i, o) {
			if($(o).hasClass('has')) {
				var $td = $(o).find('td');
				var html = '<tr data-key="'+$(o).data("key")+'"><td>'+$td.eq(0).text()+'</td><td>'+$td.eq(1).text()+'</td><td>'+$td.eq(2).text()+'</td><td>'+$td.eq(3).text()+'</td><td>'+$td.eq(4).text()+'</td><td>'+$td.eq(5).text()+'</td><td>'+$td.eq(6).text()+'</td><td class="totalMoney">'+$td.eq(7).text()+'</td><td>'+$td.eq(8).text()+'</td><td width="100"><a class="btn-danger j-delete" href="javascript:;" data-key="'+$(o).data("key")+'">删除</a></td></tr>';
				$orderList.append(html);
				$(o).removeClass('has');
				$(o).find('a').removeClass('has-btn-option btn-option').addClass('suc-btn-option').html('已添加');
			}
		})

		var $td = $orderList.find(".totalMoney");
		var totalMoney = 0;
		for(var n=0;n<$td.length;n++) {
			totalMoney += parseFloat($td.eq(n).text());
		}
		$('input[name="totalMoney"]').val(totalMoney)
		$('input[name="orderCnt"]').val($orderList.find("tr").length+'个')
		$('input[name="totalMoney"]').change()
		$('input[name="orderCnt"]').change()
		$('.close-btn').click();
	})

	$(document).on('click', '.j-delete', function() {
		if(confirm("确定删除？")) {
			var k = $(this).data('key');
			var tr = $('#orderDetailList').find('.key'+k);
			$(this).parents('tr').remove();
			tr.find('a').data('has', false).removeClass('has-btn-option suc-btn-option').addClass('btn-option').html('未选中');
		}
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
