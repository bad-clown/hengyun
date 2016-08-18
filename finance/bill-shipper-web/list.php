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
			<li class="active">账单管理</li>
		</ul>
		<div class="btn-control">
			<span class="glyphicon glyphicon-plus"></span>
			<a href="javascript:;" id="open-create">新增账单</a>
		</div>
	</div>

	<div class="listBox orderList">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th width="102">
					<div class="navbar-collapse collapse navbar-inverse-collapse">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="javascript:;" data-target="#" class="dropdown-toggle" data-toggle="dropdown">状态<b class="caret"></b>
								</a>
								<ul class="dropdown-menu" id="J_StatusFilter">
									<li>
										<a href="javascript:void(0)" data-status="-1">全部</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="0">未支付</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="1">支付中</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="2">已支付</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					</th>
					<th>账单号</th>
					<th>开票时间</th>
					<th width="144"><div class="navbar-collapse collapse navbar-inverse-collapse">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="javascript:;" data-target="#" class="dropdown-toggle" data-toggle="dropdown">总金额<b class="caret"></b>
								</a>
								<ul class="dropdown-menu" id="J_MoneyFilter">
									<li>
										<a href="javascript:void(0)" data-min="-1" data-max="-1">全部</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="-1" data-max="1000">小于1000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="1000" data-max="3000">1000-3000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="3000" data-max="7000">3000-6000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="6000" data-max="9000">6000-9000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="9000" data-max="12000">9000-12000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="12000" data-max="15000">12000-15000</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-min="15000" data-max="-1">大于15000</a>
									</li>
								</ul>
							</li>
						</ul>
					</div></th>
					<th>订单数</th>
					<th>开票抬头</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<ul class="pagination" id="listPages"></ul>
	</div>
</div>

<div class="shipper-pop popup">
	<a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
	<div class="popup-header"></div>
	<div class="popup-main">
		<div class="popup-breadcrumb">
			<div class="breadcrumbBox">
				<ul class="breadcrumb">
					<li>账单管理</li>
					<li>新增账单</li>
					<li class="active">货主选择</li>
				</ul>
				<a href="javascript:;" class="btn btn-primary" id="shipper-complete" title="确认">确认</a>
			</div>
			<div class="shipperBox clearfix">
				<table class="table table-striped table-hover" id="shipperData">
					<thead>
						<tr>
							<th>用户名</th>
							<th>手机号</th>
							<th>订单数</th>
							<th>类型</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div class="shipperPages"><ul class="pagination" id="popupPages"></ul></div>
			</div>
		</div>
	</div>
</div>
<div class="overlay"></div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function() {
	var actPage = 1, actStatus = -1, minMoney = -1, maxMoney = -1;
	var PageTotal = {
		init : function(obj,d) {
			this.obj = $(obj),
			this.current = actPage, 	//当前页
			this.pageCount = 10, 		//每页显示的数据量
			this.total = d.pageCnt, 	//总共的页码
			this.first = 1, 			//首页
			this.last = 0, 				//尾页
			this.pre = 0, 				//上一页
			this.next = 0, 				//下一页
			this.getData(this.current, this.total)
		},
		getData: function(n, t) {
			this.obj.empty();
			if (n == null) {n = 1;}
			this.current = n;
			this.page();
		},
		getPages: function() {
			this.last = this.total;
			this.pre = this.current - 1 <= 0 ? 1 : (this.current - 1);
			this.next = this.current + 1 >= this.total ? this.total : (this.current + 1);
		},
		page: function() {
			this.obj.empty();
			var x = 4;
			this.getPages();

			if(this.total > x) {
				var index = this.current <= Math.ceil(x / 2) ? 1 : (this.current) >= this.total - Math.ceil(x / 2) ? this.total - x : (this.current - Math.ceil(x / 2));

				var end = this.current <= Math.ceil(x / 2) ? (x + 1) : (this.current + Math.ceil(x / 2)) >= this.total ? this.total : (this.current + Math.ceil(x / 2));
			}
			else {
				var index = 1;

				var end = this.total;
			}
			if (this.current > 1) {
				this.obj.append("<li class='prev'><a href='javascript:;' data-page='"+(this.current - 1)+"'>«</a></li>");
			}
			else if(this.current == 1){
				this.obj.append("<li class='prev disabled'><span>«</span></li>");
			}

			for (var i = index; i <= end; i++) {
				if (i == this.current) {
					this.obj.append("<li class='active'><a href='javascript:;' data-page='"+(this.current)+"'>"+i+"</a></li>");
				} else {
					this.obj.append("<li><a href='javascript:;' data-page='"+i+"'>"+i+"</a></li>");
				}
			}

			if (this.current < end) {
				this.obj.append("<li class='next'><a href='javascript:;' data-page='"+(this.current + 1)+"'>»</a></li>");
			}
			else if(this.current == end){
				this.obj.append("<li class='next disabled'><span>»</span></li>");
			}
		}
	};

	function getData() {
		var status = {
			0 : "未支付",
			1 : "支付中",
			2 : "已支付"
		};

		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/finance/bill-shipper/list",
			data : {
				page : actPage,
				status : actStatus,
				minMoney : minMoney,
				maxMoney : maxMoney
			},
			dataType : "json",
			success : function(d) {
				var data = d.data,
					c = $('#listContent').find('tbody');
				if(data.list.length) {
					PageTotal.init('#listPages', data)
					c.empty();
					$.each(data.list, function(i,o) {
						var t = _global.FormatTime(o.billTime);
						var h = '<tr><td align="center"><div class="form-group"><label>'+status[o.status]+'</label></div></td><td>'+o.billNo+'</td><td>'+t+'</td><td><span class="pl24">'+o.totalMoney+'元</span></td><td>'+o.orderCnt+'单</td><td>'+o.title+'</td><td width="170"><a class="btn-info" href="<?= $Path;?>/finance/bill-shipper-web/detail?id='+o._id+'">查看详情</a><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
						c.append(h)
					})
				}
				else {
					$('.pagination').empty()
					c.empty();
					var h = '<tr><td align="center" colspan="7">暂无数据</td></tr>';
					c.append(h)

				}
			}
		})
	}
	getData()

	function getShipperData() {
		$.ajax({
			type : "GET",
			url : "<?= $Path;?>/finance/bill-shipper/unpayed-users",
			data : {
				page : actPage
			},
			dataType : "json",
			success : function(d) {
				var data = d.data,
					c = $('#shipperData').find('tbody');
				if(data.list.length) {
					PageTotal.init('#popupPages', data)
					c.empty();
					$.each(data.list, function(i,o) {
						var h = '<tr><td>'+(o.name || "暂无")+'</td><td>'+o.phone+'</td><td>'+o.orderCnt+'单</td><td><span class="pl24">货主（个人）</span></td><td width="100"><a href="javascript:void(0);" class="btn-option" data-key="'+o._id+'">选择</a></td></tr>';
						c.append(h)
					})
				}
				else {
					$('#popupPages').empty()
					c.empty();
					var h = '<tr><td align="center" colspan="5">暂无数据</td></tr>';
					c.append(h)

				}
			}
		})
	}

	$(document).on("click", '#listPages a', function() {
		actPage = $(this).data("page");
		getData()
	})

	$(document).on("click", '#popupPages a', function() {
		actPage = $(this).data("page");
		getShipperData()
	})

	$('#J_StatusFilter a').on('click', function() {
		actPage = 1;
		actStatus = $(this).data('status');
		getData()
	})

	$('#J_MoneyFilter a').on('click', function() {
		actPage = 1;
		minMoney = $(this).data('min');
		maxMoney = $(this).data('max');
		getData()
	})

	$(document).on('click', '.btn-option', function() {
		$('.btn-option').removeClass('has-btn-option');
		$('#shipperData').find('tr').removeClass('has');
		$(this).parents('tr').addClass('has');
		$(this).addClass('has-btn-option');
		$('#shipper-complete').prop('href', '<?= $Path;?>/finance/bill-shipper-web/create?shipperId='+$(this).data('key'));
	})

	$('#open-create').on('click', function() {
		$('.shipper-pop').show();
		$('.overlay').show();
		getShipperData()
	})

	$(document).on('click', '.j-delete', function() {
		var k = $(this).data('key');
		if(confirm("确定删除？")) {
			$.ajax({
				type : "GET",
				url : "<?= $Path;?>/finance/bill-shipper/del?id="+k,
				dataType : "json",
				success : function(data) {
					if(data.code == '0') {
						alert('删除成功！')
						getData()
					}
				}
			})
		}
	})

	$('.close-btn').on('click', function() {
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
