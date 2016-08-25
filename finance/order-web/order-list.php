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
			<li class="active">订单管理</li>
		</ul>
		<!-- <div class="btn-control">
			<span class="glyphicon glyphicon-plus"></span>
			<a href="javascript:;">新增订单</a>
		</div> -->
	</div>

	<div class="listBox orderList">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th width="102">
					<div class="navbar-collapse collapse navbar-inverse-collapse">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">状态<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="javascript:void(0)" data-status="-1">全部</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="100">新发布</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="200">待确认</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="300">待派车</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="400">待提货</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="500">在途中</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="600">已送达</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="700">已完成</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="800">已拒绝</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="900">已过期</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="1000">已失效</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					</th>
					<th>发布时间</th>
					<th>订单号</th>
					<th>起点</th>
					<th>终点</th>
					<th>司机报价</th>
					<th>后台报价</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<ul class="pagination"></ul>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function() {
	var actPage = 1, actStatus = -1;
	var PageTotal = {
		init : function(d) {
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
			$(".pagination").empty();
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
			$(".pagination").empty();
			var x = 4;
			this.getPages();

			console.log()

			if(this.total > x) {
				var index = this.current <= Math.ceil(x / 2) ? 1 : (this.current) >= this.total - Math.ceil(x / 2) ? this.total - x : (this.current - Math.ceil(x / 2));

				var end = this.current <= Math.ceil(x / 2) ? (x + 1) : (this.current + Math.ceil(x / 2)) >= this.total ? this.total : (this.current + Math.ceil(x / 2));
			}
			else {
				var index = 1;

				var end = this.total;
			}
			if (this.current > 1) {
				$(".pagination").append("<li class='prev'><a href='javascript:;' data-page='"+(this.current - 1)+"'>«</a></li>");
			}
			else if(this.current == 1){
				$(".pagination").append("<li class='prev disabled'><span>«</span></li>");
			}

			for (var i = index; i <= end; i++) {
				if (i == this.current) {
					$(".pagination").append("<li class='active'><a href='javascript:;' data-page='"+(this.current)+"'>"+i+"</a></li>");
				} else {
					$(".pagination").append("<li><a href='javascript:;' data-page='"+i+"'>"+i+"</a></li>");
				}
			}

			if (this.current < end) {
				$(".pagination").append("<li class='next'><a href='javascript:;' data-page='"+(this.current + 1)+"'>»</a></li>");
			}
			else if(this.current == end){
				$(".pagination").append("<li class='next disabled'><span>»</span></li>");
			}
		}
	};

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
			url : "<?= $Path;?>/finance/order/list",
			data : {
				page : actPage,
				status : actStatus
			},
			dataType : "json",
			success : function(d) {
				var data = d.data,
					c = $('#listContent').find('tbody');
				if(data.list.length) {
					PageTotal.init(data)
					c.empty();
					$.each(data.list, function(i,o) {
						var t = _global.FormatTime(o.publishTime);
						var driverTotal = o.driver ? o["driver"]["bid"]["realTotalMoney"]+'元' : "暂无报价";
						var bidTotal = o.realTotalMoney ? o.realTotalMoney+'元' : "暂无报价";
						var h = '<tr><td align="center"><div class="form-group"><label>'+status[o.status]+'</label></div></td><td>'+t+'</td><td>'+o.orderNo+'</td><td>'+o.provinceFrom+o.cityFrom+o.districtFrom+'</td><td>'+o.provinceTo+o.cityTo+o.districtTo+'</td><td>合计：'+driverTotal+'</td><td>合计：'+bidTotal+'</td><td width="170"><a class="btn-default" href="<?= $Path;?>/finance/order-web/detail?id='+o._id+'">查看详情</a><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
						c.append(h)
					})
				}
				else {
					$('.pagination').empty()
					c.empty();
					var h = '<tr><td align="center" colspan="8">暂无数据</td></tr>';
					c.append(h)

				}
			}
		})
	}
	getData()

	$(document).on("click", '.pagination a', function() {
		actPage = $(this).data("page");
		getData()
	})

	$('.dropdown-menu a').on('click', function() {
		actPage = 1;
		actStatus = $(this).data('status');
		getData()
	})

	$(document).on('click', '.j-delete', function() {
		var k = $(this).data('key');
		if(confirm("确定删除？")) {
			$.ajax({
				type : "GET",
				url : "<?= $Path;?>/finance/order/del-order?id="+k,
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

	setInterval(function() {
		getData()
	}, 30000)
})
</script>
<?php $this->endBlock();  ?>
