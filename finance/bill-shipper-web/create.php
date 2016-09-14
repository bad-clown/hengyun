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
			<li class="active">新增账单</li>
		</ul>
		<a href="javascript:;" id="j-save-control" class="btn save-control">保存</a>
		<a href="<?= $Path;?>/finance/bill-shipper-web/list" class="btn back-control">返回</a>
	</div>

	<div class="detail-box">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>
		<div class="clearfix" id="J-bill-detail">
			<div class="form-group label-floating select-menu">
				<label for="status" class="control-label">账单状态</label>
				<select id="status" name="status" class="form-control">
					<option value="0">未支付</option>
					<option value="1">支付中</option>
					<option value="2">已支付</option>
				</select>
			</div>
			<div class="form-group label-floating">
				<label class="control-label">开票时间</label>
				<input class="form-control" readonly="readonly" name="billTime" id="billTime" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">开票总金额</label>
				<input class="form-control" readonly="readonly" name="totalMoney" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">订单个数</label>
				<input class="form-control" readonly="readonly" name="orderCnt" value="" type="text">
			</div>
		</div>
		<div class="detail-label">
			<span class="label label-default">订单明细</span>
			<div class="btn-create-detail">
				<span class="glyphicon glyphicon-plus"></span>
				<a href="javascript:;" id="create-order-detail">添加明细</a>
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
		<div class="clearfix">
			<div class="form-group label-floating">
				<label class="control-label">开票抬头</label>
				<input class="form-control" name="title" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">税号</label>
				<input class="form-control" name="tfn" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">地址</label>
				<input class="form-control" name="address" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">电话</label>
				<input class="form-control" name="tel" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">开户行</label>
				<input class="form-control" name="bank" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">开户行账号</label>
				<input class="form-control" name="bankId" value="" type="text">
			</div>
		</div>
	</div>
	<div class="detail-box pt15" id="J-mail-detail">
		<div class="detail-label"><span class="label label-default">邮寄信息</span></div>
		<div class="clearfix">
			<div class="form-group label-floating">
				<label class="control-label">邮寄地址</label>
				<input class="form-control" name="mailAddress" value="" type="text">
			</div>
			<div class="form-group label-floating">
				<label class="control-label">联系电话</label>
				<input class="form-control" name="mailTel" value="" type="text">
			</div>
		</div>
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
					<li>新增账单</li>
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
					<?php foreach($orderList as $key => $value) { ?>

						<tr class="key<?=$value["_id"];?>" data-key="<?=$value["_id"];?>">
							<td><?=$value["orderNo"];?></td>
							<td><?=$value["deliverTime"];?></td>
							<td><?=$value["provinceFrom"];?><?=$value["cityFrom"];?><?=$value["districtFrom"];?></td>
							<td><?=$value["provinceTo"];?><?=$value["cityTo"];?><?=$value["districtTo"];?></td>
							<td><?=$value["goodsCnt"];?>件</td>
							<td><?=$value["totalWeight"] || 0;?></td>
							<td><?=$value["pickupDrop"];?></td>
							<td><?=$value["driverBid"]["realTotalMoney"];?></td>
							<td><?=$value["realTotalMoney"];?></td>
							<td width="100">
								<a class="btn-option" href="javascript:;" data-key="<?=$value["_id"];?>">未选择</a>
							</td>
						</tr>
					<?php } ?>
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
	laydate({
	    elem: '#billTime',
	    event: 'click',
	    format: 'YYYY-MM-DD hh:mm:ss',
	    istime: true,
	    istoday: false,
	    choose: function(dates){
	    	$('#billTime').change()
        }
	});
	$(document).on('click', '.btn-option', function() {
		if(!$(this).data('has')) {
			$(this).data('has', true);
			$(this).parents('tr').addClass('has');
			$(this).addClass('has-btn-option');
			$(this).html('已选中')
		}
		else {
			$(this).data('has', false);
			$(this).parents('tr').removeClass('has');
			$(this).removeClass('has-btn-option');
			$(this).html('未选中')
		}
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
		$('input[name="orderCnt"]').val($orderList.find("tr").length)
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

	$('#create-order-detail').on('click', function() {
		$('.order-detail-pop').show();
		$('.overlay:eq(0)').show();
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
			shipperId : '<?= $shipperId;?>',
			status : $('select[name="status"]').val(),
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

		$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-shipper/create",
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
		})
	})

	$('.close-btn').on('click', function() {
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
