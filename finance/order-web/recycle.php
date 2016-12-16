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
			<li class="active">回收站</li>
		</ul>
	</div>
	<div class="listBox orderList">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
			<tr>
				<th>发布时间</th>
				<th>订单号</th>
				<th>起点</th>
				<th>终点</th>
				<th>公司名称</th>
				<th>联系人</th>
				<th>司机报价</th>
				<th>后台报价</th>
				<th>回单状态</th>
				<th>交易员</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<ul class="pagination"></ul>
	</div>
</div>

</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
	$(function() {

		var actPage = 1,actKey = '';
		actKey = $('.search-text').val();

		function getData() {
			$.ajax({
				type: "GET",
				url: "<?= $Path;?>/finance/order/del-list",
				data: {
					actKey: actKey,
					page: actPage
				},
				dataType: "json",
				success: function (d) {
					var data = d.data,
						c = $('#listContent').find('tbody');

					if (data.list.length) {
						PageTotal.init('.pagination', data, actPage)
						c.empty();
						$.each(data.list, function (i, o) {
							var t = _global.FormatTime(o.publishTime);
							var driverTotal = o.driver ? o["driver"]["bid"]["realTotalMoney"] + '元<br>' + _global.FormatTime(o["driver"]["bid"]["bidTime"]) : "暂无报价";
							var bidTotal = o.realTotalMoney ? o.realTotalMoney + '元<br>' + _global.FormatTime(o["bid"]["bidTime"]) : "暂无报价";
							var delBtn = '';
							var shipperName = (o.shipper.nickname || '暂无');
							var shipperCompany = (o.shipper.company || '暂无');
							var h = '<tr><td>' + t + '</td><td>' + o.orderNo + '</td><td>' + o.provinceFrom + o.cityFrom + o.districtFrom + '</td><td>' + o.provinceTo + o.cityTo + o.districtTo + '</td><td>'+ shipperCompany +'</td><td>'+ shipperName +'</td><td>合计：' + driverTotal + '</td><td>合计：' + bidTotal + '</td><td> ' + o.backReceived + ' </td><td>'+ o.scheduler +'</td><td width="170"><a class="btn-default" id="j-restore" href="javasctipt:;" data-key="'+ o._id +'">恢复订单</a><a class="btn-danger has-driver" href="javascript:;" data-key="' + o._id + '">删除</a></td></tr>';
							c.append(h);

						})
					}
					else {
						$('.pagination').empty()
						c.empty();
						var h = '<tr><td align="center" colspan="11">暂无数据</td></tr>';
						c.append(h)

					}
				}
			})
		}

		getData()

		$(document).on("click", '.pagination a', function () {
			actPage = $(this).data("page");
			getData()
		})

		$('.dropdown-menu a').on('click', function () {
			actPage = 1;
			actStatus = $(this).data('status');
			getData()
		})

		$(document).on('click', '#j-restore', function () {
			var k = $(this).data('key');
			if (confirm("确定要恢复订单吗？")) {
				$.ajax({
					type: "GET",
					url: "<?= $Path;?>/finance/order/restore",
					data:{
						type:1,
						id : k
					},
					dataType: "json",
					success: function (data) {
						if (data.code == '0') {
							alert('恢复成功！')
							getData()
						}
						else {
							alert('恢复失败，请重试！')
							getData()
						}
					},
					error: function () {
						alert("恢复失败，请检查网络后重试！");
					}
				})
			}
		})

//		$(document).on('click', '.j-delete', function () {
//			var k = $(this).data('key');
//			if (confirm("确定要删除订单吗？")) {
//				$.ajax({
//					type: "GET",
//					url: "<?//= $Path;?>///finance/order/del-order?id=" + k,
//					dataType: "json",
//					success: function (data) {
//						if (data.code == '0') {
//							alert('删除成功！')
//							getData()
//						}
//						else {
//							alert('删除失败，请重试！')
//							getData()
//						}
//					},
//					error: function () {
//						alert("删除失败，请检查网络后重试！");
//					}
//				})
//			}
//
//		})
		setInterval(function () {
			getData()
		}, 30000);

		$(document).on('keypress','.search-text', function(e) {
			if(e.keyCode == 13) {
				actKey = $(this).val(), actPage = 1;
				getData()
			}
		})

	});
</script>
<?php $this->endBlock();  ?>
