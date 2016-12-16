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
			<li class="active">推荐列表</li>
		</ul>
		<?php if($powers){?>
		<div class="btn-control">
			<span class="glyphicon glyphicon-download-alt"></span>
<!--			<a href="--><?//= $Path;?><!--/finance/order/daochu">导出</a>-->
		</div>
		<?php };?>
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
										<a href="javascript:void(0)" data-status="700">确认送达</a>
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
					<th>交易员</th>
                    <th>推荐人</th>
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
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">


	$(function() {

        var actPage = 1, actStatus = -1,actSched = 1, actKey = '';
        actKey = $('.search-text').val();
        function getData() {

            $.ajax({
                type: "GET",
                url: "<?= $Path;?>/finance/order/trusteeship",
                data: {
                    actKey: actKey,
                    page: actPage,
                    status: actStatus,
                    sched : actSched
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
                            var h = '<tr><td align="center"><div class="form-group"><label>' + Sched.status[o.status] + '</label></div></td><td>' + t + '</td><td>' + o.orderNo + '</td><td>' + o.provinceFrom + o.cityFrom + o.districtFrom + '</td><td>' + o.provinceTo + o.cityTo + o.districtTo + '</td><td>'+ shipperCompany +'</td><td>'+ shipperName +'</td><td>合计：' + driverTotal + '</td><td>合计：' + bidTotal + '</td><td> ' + o.backReceived + ' </td><td>'+ o.scheduler +'</td><td>'+ (o.referee || '暂无') +'</td></tr>';
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

        $(document).on('click', '#toPage', function() {
            var pageNum = parseInt($('#pageNum').val());

            if(!(pageNum > $(this).data('max'))) {
                actPage = pageNum
                getData()
            }
            else {
                alert('前往页数大与总页数！')
            }
        })

        $('.dropdown-menu a').on('click', function () {
            actPage = 1;
            actStatus = $(this).data('status');
            getData()
        })

        $(document).on('click', '.j-delete', function () {
        	<?php if($powers) {?>
            var k = $(this).data('key');
            if (confirm("确定要删除订单吗？")) {
                $.ajax({
                    type: "GET",
                    url: "<?= $Path;?>/finance/order/del?id=" + k,
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

        $(document).on('focus', '.search-text', function () {
            clearInterval(timer);
        });

        $(document).on('blur', '.search-text', function () {
            timer = setInterval(function () {
                getData()
            }, 30000);

        });
        var timer =  setInterval(function () {
            getData()
        }, 30000);

        $(document).on('keypress','.search-text', function(e) {
            if(e.keyCode == 13) {
                actKey = $(this).val(),actPage = 1, actStatus = -1 , actSched = 1;
                getData()
            }
        })
    });

</script>
<?php $this->endBlock();  ?>
