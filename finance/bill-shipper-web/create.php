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
		<a href="<?= $Path;?>/finance/bill-shipper-web/list" class="back-control">返回</a>
	</div>

	<div class="detail-box">
		<div class="detail-label"><span class="label label-default">账单信息</span></div>
		<div class="clearfix" id="J-bill-detail"></div>
		<div class="detail-label"><span class="label label-default">订单明细</span><span class="glyphicon glyphicon-plus"></span><a href="#">添加明细</a></div>
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
			<a href="javascript:;" class="btn-price j-price">修改报价</a>
			<a href="javascript:;" class="btn-driver j-driver">撮合</a>
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
				<div class="form-group">
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
			var billTime = _global.FormatTime(data.billTime);

			var billHTML = '<div class="form-group label-floating"><label class="control-label">账单状态</label><input class="form-control" readonly="readonly" value="'+ status[data.status] +'" type="text"></div><div class="form-group label-floating"><label class="control-label">账单编号</label><input class="form-control" readonly="readonly" value="'+ data.billNo +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票时间</label><input class="form-control" readonly="readonly" value="'+ billTime +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开票总金额</label><input class="form-control" readonly="readonly" value="'+ data.totalMoney +'" type="text"></div><div class="form-group label-floating"><label class="control-label">订单个数</label><input class="form-control" readonly="readonly" value="'+ data.orderCnt +'个" type="text"></div>';

			$bill.append(billHTML);

			$.each(data.orderList, function(i, o) {
				var deliverTime = _global.FormatTime(o.deliverTime);
				var orderHTML = '<tr><td>'+o.orderNo+'</td><td>'+deliverTime+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>'+o.goodsCnt+'件</td><td>'+(o.realTotalWeight || 0)+'</td><td>'+o.pickupDrop+'</td><td>'+o["driverBid"]["realTotalMoney"]+'</td><td>'+o.realTotalMoney+'</td><td width="100"><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
				$order.append(orderHTML);
			})

			var noteHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">开票抬头</label><input class="form-control" readonly="readonly" value="'+ data.title +'" type="text"></div><div class="form-group label-floating"><label class="control-label">税号</label><input class="form-control" readonly="readonly" value="'+ data.tfn +'" type="text"></div><div class="form-group label-floating"><label class="control-label">地址</label><input class="form-control" readonly="readonly" value="'+ data.address +'" type="text"></div><div class="form-group label-floating"><label class="control-label">电话</label><input class="form-control" readonly="readonly" value="'+ data.tel +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开户行</label><input class="form-control" readonly="readonly" value="'+ data.bank +'" type="text"></div><div class="form-group label-floating"><label class="control-label">开户行账号</label><input class="form-control" readonly="readonly" value="'+ data.bankId +'个" type="text"></div></div>';

			$note.append(noteHTML);

			var mailHTML = '<div class="clearfix"><div class="form-group label-floating"><label class="control-label">邮寄地址</label><input class="form-control" readonly="readonly" value="'+ data.mailAddress +'" type="text"></div><div class="form-group label-floating"><label class="control-label">联系电话</label><input class="form-control" readonly="readonly" value="'+ data.mailTel +'" type="text"></div></div>';

			$mail.append(mailHTML);

		}
	})

	$(document).on('click', '.j-price', function() {
		var tr = $(this).parents('tr:eq(0)');
		$('.price-pop > .popup-header').html('<table><tbody><tr><td>起点：'+ $.trim(tr.find('.from').text()) +'</td><td>终点：'+ $.trim(tr.find('.to').text()) +'</td><td>件数：'+ $.trim(tr.find('.cnt').text()) +'</td><td>总吨数：'+ $.trim(tr.find('.weight').text()) +'吨</td><td>几装几卸：'+ $.trim(tr.find('.drop').text()) +'</td></tr></tbody></table>')
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
		var priceType = {0 : '单价', 1 : '一口价'}
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
					var h = '<tr class="'+trCls+'"><td>'+priceType[o.bidPriceType]+'：'+o.bidPrice+'元</td><td>'+o.realTotalMoney+'元</td><td>'+_global.FormatTime(o.bidTime)+'</td><td>'+o.phone+'</td><td>'+aHtml+'</td></tr>';

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
			_driverUrl = "<?= $Path;?>/sched/order/mod-driver";
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

	$('.close-btn').on('click', function() {
		$('#price').val('')
		$('#orderDetails').find('tbody').empty()
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
