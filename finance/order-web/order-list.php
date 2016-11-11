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
		<input type="text" class="search-text" name="search" value="" placeholder="搜索订单" id="search_submit" />
		<i class="glyphicon glyphicon-search" ></i>
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
		<?php if($export):?>
		<div class="btn-control">
			<span class="glyphicon glyphicon-download-alt"></span>
			<a href="<?= $Path;?>/finance/order/daochu">导出</a>
		</div>
		<?php endif;?>
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
                    <th>公司名称</th>
                    <th>联系人</th>
					<th>司机报价</th>
					<th>后台报价</th>
					<th>回单状态</th>
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
        var search_val = '';
        // 获取搜索栏内容
        $(document).bind('change', '#search_submit', function () {
            var search_val = $('#search_submit').val();
            getData(search_val);

        })

        function getData(search_val) {
            if (!search_val) {
                search_val = '';
            }
            $.ajax({
                type: "GET",
                url: "<?= $Path;?>/finance/order/list",
                data: {
                    search_val: search_val,
                    page: actPage,
                    status: actStatus
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
                            var driverTotal = o.driver ? o["driver"]["bid"]["realTotalMoney"] + '元' : "暂无报价";
                            var bidTotal = o.realTotalMoney ? o.realTotalMoney + '元' : "暂无报价";
                            var delBtn = '';

                            if(o.status != 700) {
                            	delBtn = '<a class="btn-danger j-delete" href="javascript:;" data-key="' + o._id + '">删除</a>';
                            }

                            var h = '<tr><td align="center"><div class="form-group"><label>' + Sched.status[o.status] + '</label></div></td><td>' + t + '</td><td>' + o.orderNo + '</td><td>' + o.provinceFrom + o.cityFrom + o.districtFrom + '</td><td>' + o.provinceTo + o.cityTo + o.districtTo + '</td><td>1</td><td>1</td><td>合计：' + driverTotal + '</td><td>合计：' + bidTotal + '</td><td> ' + o.backReceived + ' </td><td width="170"><a class="btn-default" href="<?= $Path;?>/finance/order-web/detail?id=' + o._id + '">查看详情</a>'+ delBtn +'</td></tr>';
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

        $(document).on('click', '.j-delete', function () {
        	<?php if($export) {?>
            var k = $(this).data('key');
            if (confirm("确定要删除订单吗？")) {
                $.ajax({
                    type: "GET",
                    url: "<?= $Path;?>/finance/order/del-order?id=" + k,
                    dataType: "json",
                    success: function (data) {
                        if (data.code == '0') {
                            alert('删除成功！')
                            getData()
                        }
                        else {
                            alert('删除失败，请重试！')
                            getData()
                        }
                    },
                    error: function () {
                        alert("删除失败，请检查网络后重试！");
                    }
                })
            }
            <?php }else { ?>
            alert("您的账单已生成无法删除，如需删除订单请通知财务！");
            <?php } ?>
        })
        setInterval(function () {
            getData()
        }, 30000);



    });

</script>
<?php $this->endBlock();  ?>
