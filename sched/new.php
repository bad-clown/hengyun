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
		<input type="text" class="search-text" name="search" value="" placeholder="搜索" />
		<i class="glyphicon glyphicon-search"></i>
	</div>
	<div class="username">
		<a href="#"><span><?= \Yii::$app->user->identity->type;?></span></a> | <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
	</div>
</div>

<div class="content mb60">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="javascript:;">调度中心</a></li>
			<li class="active">发布管理</li>
		</ul>
		<a href="javascript:;" class="btn btn-control" id="batch-control">批量操作</a>
		<a href="javascript:;" class="btn btn-cancel" id="batch-cancel">取消</a>
	</div>

	<div class="listBox">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th>状态</th>
					<th>订单号</th>
					<th>提货时间</th>
					<th>起点</th>
					<th>终点</th>
					<th>总件数</th>
					<th>总吨数</th>
					<th>几装几卸</th>
					<th>公司名称</th>
					<th>联系人</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div class="control-panel batch-control">
		<div class="form-group">
			<div class="checkbox">
				<label><input type="checkbox" id="checkAll">全选</label>
			</div>
		</div>
		<div class="control-btns">
			<a href="javascript:;" class="btn-pub" id="J_Pubbatch">发布</a>
			<a href="javascript:;" class="btn-del" id="J_Delbatch">删除</a>
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
					<li>交易员列表</li>
					<li>转发</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary" id="shipper-complete" title="确认">确认</a>
			</div>
			<div class="shipperBox clearfix">
				<table class="table table-striped table-hover" id="schedData">
					<thead>
					<tr>
						<th>姓名</th>
						<th>手机号</th>
						<th>出发</th>
						<th>到达</th>
						<th>操作</th>
					</tr>
					</thead>
					<tbody></tbody>
				</table>
<!--				<div class="shipperPages"><ul class="pagination" id="popupPages"></ul></div>-->
			</div>
		</div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
$(function() {
	var actKey = '';
	actKey = $('.search-text').val();
	function getData() {

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/new",
            data : {
				actKey : actKey
			},
			dataType : "json",
			success : function(data) {
				if(data.code == "0") {
					var $listContent = $('#listContent').find('tbody');
					var htmlCont = '';
					$listContent.empty();
					$.each(data.data, function(i,o) {
						var shipperName = (o.shipper.nickname || '暂无');
						var shipperCompany = (o.shipper.company || '暂无');
						var t = _global.FormatTime(o.deliverTime);
						htmlCont += '<tr><td><div class="form-group"><div class="checkbox"><label><input type="checkbox" data-num="'+o._id+'"><span class="checkbox-material"><span class="check"></span></span>'+ Sched.status[o.status]+'</label></div></div></td><td>'+o.orderNo+'</td><td>'+t+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td><a href="javascript:;" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td>'+o.totalWeight+'</td><td>'+o.pickupDrop+'</td><td>'+ shipperCompany +'</td><td>'+ shipperName +'</td><td width="250"><a class="btn-default" href="<?= $Path;?>/sched/order-web/detail?id='+o._id+'">查看详情</a><a class="btn-default j-publish" href="javascript:;" data-key="'+o._id+'">发布</a><a class="btn-default j-relay" href="javascript:;" data-key="'+o.orderNo+'">转发</a></td></tr>';
					})
					$listContent.append(htmlCont)
					_global.badge();
				}
			}
		})
	}
	getData()

	var actPage = 1;

	function getSchedRouter(orderNo) {
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/route-list",
			data : {
				page : actPage
			},
			dataType : "json",
			success : function(d) {
			var c = $('#schedData').find('tbody');

			if(d.length) {
				// PageTotal.init('.pagination', data, actPage)
				PageTotal.init('#popupPages', d)
				c.empty();
				$.each(d, function(i,o) {
					var name = o.name || "暂无";
					var h = '<tr><td>'+ name +'</td><td>'+ o.phone +'</td><td>'+ o.provinceTo  +'</td><td>'+ o.provinceFrom +'</td><td width="100"><a href="javascript:void(0);" class="btn-option" data-key="'+ orderNo +'" data-uid="'+ o.userId +'" >选择</a></td></tr>';
					c.append(h)
				})
			}
			else {
				// $('.pagination').empty()
				c.empty();
				var h = '<tr><td align="center" colspan="5">暂无数据</td></tr>';
				c.append(h)
			}
			}
		})
	}

	$(document).on('click', '.j-publish',function() {
		var k = $(this).data('key');
		if(confirm("确定发布吗？")) {
			$.ajax({
				type: "GET",
				url: "<?= $Path;?>/sched/order/publish?orderId=" + k,
				success: function (data) {
					// console.log(data);
					if (data.code == "0") {
						alert('发布成功！');
						getData()
						_global.badge();
					}
					else {
						alert('发布失败，请重试！');
						getData()
						_global.badge();
					}
				},
				error: function () {
					alert("发布失败，请检查网络后重试！");
				}
			})
		}
	})

	$(document).on('click', '.j-relay', function() {
		var orderNo = $(this).data('key');
		var mainheight = $(document).height()+30;
		$("#mask").css({height:mainheight + 'px',width:'100%',display:'block'});
		$("#mask").show();
		$('.shipper-pop').show();
		getSchedRouter(orderNo);
	})

	$('.close-btn').on('click', function() {
		$('#orderDetails').find('tbody').empty()
		$("#mask").hide();
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})

	$(document).on('click', '.btn-option', function() {
		$('.btn-option').removeClass('has-btn-option');
		$('#schedData').find('tr').removeClass('has');
		$(this).parents('tr').addClass('has');
		$(this).addClass('has-btn-option');
		var scheduler = $(this).data('uid');
		$('#shipper-complete').prop('href', '<?= $Path;?>/sched/order-web/relay?$schedulerid='+ scheduler +'&orderNo='+ $(this).data('key') );
	})

//	$(document).on('click', '.j-delete', function() {
//		var k = $(this).data('key');
//		if(confirm("确定删除？")) {
//			$.ajax({
//				type : "GET",
//				url : "<?//= $Path;?>///sched/order-web/del-order?id="+k,
//				dataType : "json",
//				success : function(data) {
//					if(data.code == '0') {
//						alert('删除成功！')
//						getData()
//						_global.badge();
//					}
//					else {
//						alert('删除失败，请重试！')
//						getData()
//						_global.badge();
//					}
//				},
//				error : function() {
//					alert("删除失败，请检查网络后重试！");
//				}
//			})
//		}
//	})

	$('#J_Pubbatch').on('click', function() {
		var dataId = [];
		$('.form-group .checkbox input[type="checkbox"]').each(function(i, o) {
			if($(o).is(':checked')){
				dataId.push($(o).data('num'));
			}
		})


		if(dataId.length) {
			$.ajax({
				type : "POST",
				url : "<?= $Path;?>/sched/order/publish-batch",
				data : {
					id : dataId
				},
				dataType : "json",
				success : function(data) {
					if(data.code == "0") {
						alert('发布成功！');
						getData()
						_global.badge();
					}
					else {
						alert('发布失败，请重试！');
						getData()
						_global.badge();
					}
				},
				error : function() {
					alert("发布失败，请检查网络后重试！");
				}
			})
		}
		else {
			alert('未选中订单！')
		}
	})

	$('#J_Delbatch').on('click', function() {
		var dataId = [];
		$('.form-group .checkbox input[type="checkbox"]').each(function(i, o) {
			if($(o).is(':checked')){
				dataId.push($(o).data('num'));
			}
		})

		if(dataId.length) {
			$.ajax({
				type : "POST",
				url : "<?= $Path;?>/sched/order/del-batch",
				data : {
					id : dataId
				},
				dataType : "json",
				success : function(data) {
					if(data.code == "0") {
						alert('删除成功！');
						getData()
						_global.badge();
					}
					else {
						alert('删除失败，请重试！')
						getData()
						_global.badge();
					}
				},
				error : function() {
					alert("删除失败，请检查网络后重试！");
				}
			})
		}
		else {
			alert('未选中订单！')
		}
	})

	$('#checkAll').on('change', function() {
		if($(this).is(':checked')) {
			$('#listContent .checkbox').find('input[type="checkbox"]').prop('checked', true)
		}
		else {
			$('#listContent .checkbox').find('input[type="checkbox"]').prop('checked', false)
		}
	})

	$('#batch-control').on('click', function() {
		clearInterval(listTimer)
		$('.breadcrumb').html('<li><a href="javascript:;">调度中心</a></li><li><a href="<?= $Path;?>/sched/order-web/new?sort=-time">发布管理</a></li><li class="active">批量操作</li>');
		$('.checkbox-material').show();
		$('#batch-cancel').show();
		$('.batch-control').show();
	})

	$('#batch-cancel').on('click', function() {
		listTimer = setInterval(function() {
			getData()
		}, 30000)
		$('.breadcrumb').html('<li><a href="javascript:;">调度中心</a></li><li class="active">发布管理</li>');
		$('.checkbox-material').hide();
		$('#batch-cancel').hide();
		$('.batch-control').hide();
	})


	var listTimer = setInterval(function() {
		getData()
	}, 30000)

	$(document).on('keypress','.search-text', function(e) {
		if(e.keyCode == 13) {
			actKey = $(this).val(), actPage = 1;
			getData()
		}
	})


})
</script>
<?php $this->endBlock();  ?>
