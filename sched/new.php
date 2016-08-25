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

<div class="content mb60">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li><a href="javascript:;">调度中心</a></li>
			<li class="active">发布管理</li>
		</ul>
		<a href="javascript:;" class="btn-control" id="batch-control">批量操作</a>
		<a href="javascript:;" class="btn-cancel" id="batch-cancel">取消</a>
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


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	function getData() {
		var status = {
			100 : "新发布",
			200 : "待确认",
			300 : "待派车",
			400 : "待提货",
			500 : "在途中",
			600 : "已送达",
			700 : "已完成",
			800 : "已拒绝",
			900 : "已过期",
			1000 : "已失效"
		};
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/new",
			dataType : "json",
			success : function(data) {
				if(data.code == "0") {
					var c = $('#listContent').find('tbody');
					c.empty();
					$.each(data.data, function(i,o) {
						var t = _global.FormatTime(o.deliverTime);
						var h = '<tr><td><div class="form-group"><div class="checkbox"><label><input type="checkbox" data-num="'+o._id+'"><span class="checkbox-material"><span class="check"></span></span>'+status[o.status]+'</label></div></div></td><td>'+o.orderNo+'</td><td>'+t+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td><a href="javascript:;" data-key="'+o.orderNo+'">'+o.goodsCnt+'件</a></td><td>'+o.totalWeight+'</td><td>'+o.pickupDrop+'</td><td width="250"><a class="btn-default" href="<?= $Path;?>/sched/order-web/detail?id='+o._id+'">查看详情</a><a class="btn-default j-publish" href="javascript:;" data-key="'+o._id+'">发布</a><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
						c.append(h)
					})
					_global.badge();
				}
			}
		})
	}
	getData()

	$(document).on('click', '.j-publish',function() {
		var k = $(this).data('key');

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/sched/order/publish?orderId="+k,
			success : function(data) {
				// console.log(data);
				if(data.code == "0") {
					alert('发布成功！');
					getData()
					_global.badge();
				}
			}
		})
	})

	$(document).on('click', '.j-delete', function() {
		var k = $(this).data('key');
		if(confirm("确定删除？")) {
			$.ajax({
				type : "GET",
				url : "<?= $Path;?>/sched/order-web/del-order?id="+k,
				dataType : "json",
				success : function(data) {
					if(data.code == '0') {
						alert('删除成功！')
						getData()
						_global.badge();
					}
				}
			})
		}
	})

	$('#J_Pubbatch').on('click', function() {
		var dataId = [];
		$('#order .checkbox input[type="checkbox"]').each(function(i, o) {
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
					// console.log(data);
					if(data.code == "0") {
						alert('发布成功！');
						getData()
						_global.badge();
					}
				}
			})
		}
		else {
			alert('未选中订单！')
		}
	})

	$('#J_Delbatch').on('click', function() {
		var dataId = [];
		$('#order .checkbox input[type="checkbox"]').each(function(i, o) {
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
					// console.log(data);
					if(data.code == "0") {
						alert('删除成功！');
						getData()
						_global.badge();
					}
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
		$('.breadcrumb').html('<li><a href="javascript:;">调度中心</a></li><li><a href="<?= $Path;?>/sched/order-web/new?sort=-time">发布管理</a></li><li class="active">批量操作</li>');
		$('.checkbox-material').show();
		$('#batch-cancel').show();
		$('.batch-control').show();
	})

	$('#batch-cancel').on('click', function() {
		$('.breadcrumb').html('<li><a href="javascript:;">调度中心</a></li><li class="active">发布管理</li>');
		$('.checkbox-material').hide();
		$('#batch-cancel').hide();
		$('.batch-control').hide();
	})

	$('.close-btn').on('click', function() {
		$('#orderDetails').find('tbody').empty()
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})

	setInterval(function() {
		getData()
	}, 30000)
})
</script>
<?php $this->endBlock();  ?>
