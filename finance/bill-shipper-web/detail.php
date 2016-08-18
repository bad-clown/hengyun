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
		<div class="detail-label"><span class="label label-default">订单明细</span></div>
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
			<a href="javascript:;" class="btn-price j-price">修改账单</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>



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
})
</script>
<?php $this->endBlock();  ?>
