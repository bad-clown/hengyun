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
			<li><a href="<?= $Path;?>/finance/bill-driver-web/list">账单管理</a></li>
			<li class="active">查看详情</li>
		</ul>
		<a href="javascript:;" id="j-save-control" class="btn save-control" style="display: none;">保存</a>
		<a href="javascript:;" id="j-cancel-control" class="btn cancel-control" style="display: none;">取消</a>
		<a href="<?= $Path;?>/finance/bill-driver-web/list" id="j-back-control" class="btn back-control">返回</a>
	</div>

	<div class="detail-box pb100">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>

		<div class="clearfix" id="J-bill-detail">

		</div>
		<div class="detail-label" ><a href="javascript:;" id="J-show" ><span class="label label-default" >更多信息</span></a></div>
		<br>
		<div class="clearfix" id="J-bill-detail1" style="display: none" >
		</div>

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
						<th>代付款</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="detail-label"><span class="label label-default">审批流程</span></div>
		<div class="clearfix">
			<table class="table table-striped table-hover" id="approveList">
				<thead>
				<tr>
					<th>审批时间</th>
					<th>审批结果</th>
					<th>审批人</th>
					<th>备注</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" id="j-approve" style="display: none">审批</a>
			<a href="javascript:;" id="j-mod-bill" style="display: none">修改账单</a>
		</div>

		<div class="panel-label"><span></span></div>
	</div>
</div>


<div class="shipper-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li>审批</li>
					<li>流程</li>
				</ul>
				<span><a href="javascript:;" class="btn btn-primary"  title="同意" data-key="1">同意</a></span>
				<span style="position: absolute;right: 100px"><a href="javascript:;" class="btn btn-danger"  data-key="0">不同意</a></span>
			</div>
			<div class=" ">
				<div class="form-control-static">
					<label for="name">备注说明</label>
					<input tpye="text" class="form-control" name="remarks"></input>
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

	<?php if ($powers) {?>
	$('#j-approve').show();
	$('#j-approve').on('click', function() {
		$('.shipper-pop').show();
		$('.overlay').show();
		var mainheight = $(document).height()+30;
		$("#mask").css({height:mainheight + 'px',width:'100%',display:'block'});
		$("#mask").show();
	})
	<?php };?>

	var _bidUrl = "", _driverUrl = "";
	var status = {
		  '-1' : "拒绝",
			0 : "审批中",
			1 : "待支付",
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
			var $approve = $('#approveList').find('tbody');
			var $totalMoney = data.amount;
			var billTime = _global.FormatTime(data.billTime, 1);
			var billHTML = '<div class="form-group label-floating select-menu"><label for="status" class="control-label">账单状态</label><select id="status" name="status" class="form-control" disabled="disabled"><option value="-1">拒绝</option><option value="0" selected>审批中</option><option value="1">待支付</option><option value="2">已支付</option></select></div><div class="form-group label-floating"><label class="control-label">账单编号</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.billNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">司机姓名</label><input class="form-control" readonly="readonly" name="billNo" value="'+ (data.driver.name || '') +'" type="text"></div><div class="form-group label-floating"><label class="control-label">联系电话</label><input class="form-control" readonly="readonly" name="billNo" value="'+ data.driver.phone +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单个数</label><input class="form-control" readonly="readonly" name="orderCnt" value="'+ data.orderCnt +'" type="text"></div><div class="form-group label-floating"><label class="control-label">总金额</label><input class="form-control" readonly="readonly" name="totalMoney" value="'+ $totalMoney +'" type="text"></div><div class="form-group"><label class="control-label">运费</label><input class="form-control" name="realTotalMoney" readonly="readonly" value="'+ data.totalMoney +'" type="text"></div><div class="form-group"><label class="control-label">代付费</label><input class="form-control" name="daifu" readonly="readonly" value="'+ (data.daifu || '') +'" type="text" placeholder="0"></div><div class="form-group"><label class="control-label">付款时间</label><input class="form-control" name="payTime" readonly="readonly" value="'+ ( billTime || '') +'" id="payTime" type="text"></div><div class="form-group form-group-last"><label class="control-label">备注</label><input class="form-control" readonly="readonly" name="explain" value="'+ (data.explain || '') +'" type="text"></div>';
			$bill.append(billHTML);
			var $bill1 = $('#J-bill-detail1');
			var billHTML = '</div><div class="form-group"><label class="control-label">收款姓名</label><input class="form-control" name="payeeName" readonly="readonly" value="'+ (data.payeeName || '') +'" type="text"></div><div class="form-group"><label class="control-label">收款账号</label><input class="form-control" name="bankId" readonly="readonly" value="'+ (data.bankId || '') +'" type="text"></div><div class="form-group"><label class="control-label">开户行</label><input class="form-control" name="bank" readonly="readonly" value="'+ (data.bank || '') +'" type="text"></div><div class="form-group"><label class="control-label">网银金额</label><input class="form-control" name="payNetAmount" readonly="readonly" value="'+ (data.payNetAmount || '') +'" type="text" placeholder="0"></div><div class="form-group"><label class="control-label">油卡金额</label><input class="form-control" name="payOilAmount" readonly="readonly" value="'+ (data.payOilAmount || '') +'" type="text" placeholder="0"></div><div class="form-group"><label class="control-label">油卡卡号</label><input class="form-control" name="oilCardNumber" readonly="readonly" value="'+ (data.oilCardNumber || '') +'" type="text"></div><br>';
			$bill1.append(billHTML);
			$('select[name="status"]').val(data.status).triggerHandler("change");
			$.each(data.orderList, function(i, o) {
				var deliverTime = _global.FormatTime(o.deliverTime);
				var orderHTML = '<tr data-key="'+o._id+'" data-order="'+ o.orderNo +'"><td>'+o.orderNo+'</td><td>'+deliverTime+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>'+o.goodsCnt+'件</td><td>'+(o.realTotalWeight || 0)+'</td><td>'+o.pickupDrop+'</td><td>'+o["driverBid"]["realTotalMoney"]+'</td><td>'+o.realTotalMoney+'</td><td> '+ (o.daifu || '') +' </td></tr>';
				$order.append(orderHTML);
			})

			$.each(data.approveList, function(i, o) {
				var approveTime = _global.FormatTime(o.approveTime);
				var approve = o.approve == true ? '同意' : '不同意';
				var approveHTML = '<tr><td>'+ approveTime +'</td><td>'+ approve +'</td><td>'+ o.name +'</td><td>'+ o.remarks +'</td></tr>';
				$approve.append(approveHTML);
			})

			if (data.status >= 2) {
				$('#j-approve').hide();
			}

			if ( (data.status == -1 || data.approve) && data.status < 2){
				$('#j-mod-bill').show();
			}
		}
	})

	$('#J-show').on('click',function(){
		$('#J-bill-detail1').toggle('show');
	})

	$('#j-mod-bill').on('click', function() {
		$('#j-save-control').show()
		$('#j-cancel-control').show()
		$('#j-back-control').hide()
		$('#J-bill-detail1').show()
		$('#status').removeAttr('disabled')
		$('input[name="payNetAmount"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="payeeName"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="bankId"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="bank"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="payOilAmount"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="explain"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="oilCardNumber"]').removeAttr('readonly').parent('.form-group').addClass('has-warning')
		$('input[name="payTime"]').removeAttr('readonly').data('hasChange',true).parent('.form-group').addClass('has-warning')
		$('#status option').each(function(){
			if ( $('#status').val() == -1 ) {
				$(this).val() > 0 ? $(this).prop('disabled',true) : '';
			} else if ($('#status').val() > $(this).val()) {
				$(this).prop('disabled',true);
			}
		})
		$('.control-panel').hide();
		$(window).scrollTop(0)
	})
	$('#j-cancel-control').on('click', function() {
		window.location.reload();
	})

	$('#j-save-control').on('click', function() {
		var $orderList = $('#orderList>tbody').find("tr");
		var orderList = [];
		var orderNo = [];
		for(var i=0;i<$orderList.length;i++) {
			orderList.push($orderList.eq(i).data('key'));
			orderNo.push($orderList.eq(i).data('order'));
		}
		var payTime = Date.parse($('input[name="payTime"]').val()) /1000 || '';
		var data = {
			status : $('select[name="status"]').val(),
			billNo : $('input[name="billNo"]').val(),
			payNetAmount : $('input[name="payNetAmount"]').val(),
			payOilAmount : $('input[name="payOilAmount"]').val(),
			bankId : $('input[name="bankId"]').val(),
			bank : $('input[name="bank"]').val(),
			explain : $('input[name="explain"]').val(),
			oilCardNumber : $('input[name="oilCardNumber"]').val(),
			payeeName : $('input[name="payeeName"]').val(),
			billTime : payTime,
			orderCnt : parseInt($('input[name="orderCnt"]').val()),
			orderList : orderList || [],
			orderNo : orderNo || []
		}

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

	$(document).on('click', '#create-order-detail', function() {
		$('.order-detail-pop').show();
		$('.overlay:eq(0)').show();
	})

	$('.close-btn').on('click', function() {
		$(this).parents('.popup').hide();
		$("#mask").hide();
		$('.overlay:eq(0)').hide();
	})

	$(document).on('click','.breadcrumbBox span a',function() {
		var $orderList = $('#orderList>tbody').find("tr");
		var orderNo = [];
		for(var i=0;i<$orderList.length;i++) {
			orderNo.push($orderList.eq(i).data('order'));
		}
		var key = $(this).data('key');
		var name = "<?= $name;?>";
		var remarks = $('input[name="remarks"]').val();
		$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-driver/approval?id=<?= $_id;?>",
			data : {
				key : key,
				name : name,
				remarks : remarks,
				orderNo : orderNo
			},
			dataType : 'json',
			success : function(data) {
				if(data.code == '0') {
					alert('提交成功！');
					window.location.reload();
				}
				else {
					alert('提交失败，请检查账单后重试！');
				}
			},
			erroe : function() {
				alert("提交失败，请检查网络后重试！");
			}
		})

	})

	$('#j-back-control').on('click',function(){
		window.history.go(-1);
	})


})


</script>
<?php $this->endBlock();  ?>
