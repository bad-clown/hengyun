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

    <div class="content">
        <div class="gaoji">
            <form class="submit">
            <li style="margin-left:10px "><i class="glyphicon glyphicon-remove closel" ></i></li>
            <li >
                <span>时间段：<input type="text" name="startTime" placeholder="开始时间" id="startTime"/><i class="glyphicon glyphicon-calendar"></i></span>
                <i class="glyphicon glyphicon-minus" ></i>
                <span><input type="text" name="endTime" placeholder="结束时间" id="endTime" /><i class="glyphicon glyphicon-calendar"></i></span>
            </li>
            <li >
                <span>
                    交易员：<input type="text" name="dealer" placeholder="交易员" />
                </span>
                <span>
                    维护人：<input type="text" name="reference" placeholder="维护人" />
                </span>
            </li>
            <li >
                <span>
                    账单号：<input type="text" name="billShipperNumber" placeholder="货主账单号"/>
                </span>
                <span>
                    账单号：<input type="text" name="billDriverNumber" placeholder="司机账单号" />
                </span>
            </li>

            <li class="text-right" style="width: 80%;font-size: 14px;">
                <input type="reset" value="" style="display: none"  />
                <a class="btn-default a-search" href="javascript:;" >搜索</a>
            </li>
            </form>
        </div>
    </div>
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
			<li class="active">订单列表</li>
		</ul>
		<?php if($powers){?>
		<div class="btn-control" style="position: absolute;top: 10px;right: 10px;z-index: 999;">
			<span class="glyphicon glyphicon-download-alt"></span>
			<a href="javascript:;" id="j-export">导出</a>
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
										<a href="javascript:void(0)" data-status="700">确认送到</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="800">已拒绝</a>
									</li>
									<li>
										<a href="javascript:void(0)" data-status="900">已过期</a>
									</li>
                                    <li>
                                        <a href="javascript:void(0)" data-status="1100">已取消</a>
                                    </li>
									<li>
										<a href="javascript:void(0)" data-status="1000">已失效</a>
									</li>
                                    <input type="hidden" name="status" value=""/>
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
					<th>维护人</th>
					<th>
                        <ul class="nav navbar-nav">
                            <li class="dropdown text-center" style="width: 60px;">
                                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">收付状态<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu" >
                                    <li>
                                        <a href="javascript:void(0)" data-situation="-1">全部</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-situation="0">未收付</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-situation="1">已付款</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-situation="2">已收款</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-situation="3">已完成</a>
                                    </li>
                                    <input type="hidden" name="situation" value=""/>
                                </ul>
                            </li>
                        </ul>

                    </th>
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
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">

	$(function() {

        var actPage = 1,actStatus = -1, situaTion = -1, actKey = '';
         actKey = $('.search-text').val() || '';
         actStatus = $('input[name="status"]').val()  || -1;
         situaTion = $('input[name="situation"]').val() || -1;

         if (!actKey) {
            actKey = {};
            actKey['startTime'] = $('#startTime').val();
            actKey['endTime'] = $('#endTime').val();
            actKey['billShipperNumber'] = $('input[name="billShipperNumber"]').val();
            actKey['billDriverNumber'] = $('input[name="billDriverNumber"]').val();
            actKey['dealer'] = $('input[name="dealer"]').val();
            actKey['reference'] = $('input[name="reference"]').val();
        }

        function getData() {

            $.ajax({
                type: "POST",
                url: "<?= $Path;?>/finance/order/list?page="+ actPage +"&status="+ actStatus +"&situation="+situaTion,
                data: {
                    actKey: actKey
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
                            var payment = (o.payedStatus == 2 && o.receiveMoneyTime == 2) ? '已完成' : (o.payedStatus == 2 ? '已付款' : (o.receiveMoneyTime == 2 ? '已收款' : '未收付')) ;
                            var driverTotal = o.driver ? o["driver"]["bid"]["realTotalMoney"] + '元<br>' + _global.FormatTime(o["driver"]["bid"]["bidTime"]) : "暂无报价";
                            var bidTotal = o.realTotalMoney ? o.realTotalMoney + '元<br>' + _global.FormatTime(o["bid"]["bidTime"]) : "暂无报价";
                            var delBtn = '';
                            var shipperName = (o.shipper.nickname || '暂无');
                            var shipperCompany = (o.shipper.company || '暂无');
                            if(o.status != 700) {
                            	delBtn = '<a class="btn-danger j-delete" href="javascript:;" data-key="' + o._id + '">删除</a>';
                            }
                            var h = '<tr><td align="center"><div class="form-group"><label>' + Sched.status[o.status] + '</label></div></td><td>' + t + '</td><td>' + o.orderNo + '</td><td>' + o.provinceFrom + o.cityFrom + o.districtFrom + '</td><td>' + o.provinceTo + o.cityTo + o.districtTo + '</td><td>'+ shipperCompany +'</td><td>'+ shipperName +'</td><td>合计：' + driverTotal + '</td><td>合计：' + bidTotal + '</td><td> ' + o.backReceived + ' </td><td>'+ o.scheduler +'</td><td>'+ (o.referee || '暂无')  +'</td><td width="50">'+ payment +'</td><td width="170" style="text-align:left"><a class="btn-default" href="<?= $Path;?>/finance/order-web/detail?id=' + o._id + '">查看详情</a>'+ delBtn +'</td></tr>';
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
            getData();
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
            situaTion = $(this).data('situation') || -1;
            actStatus = $(this).data('status') || -1;
            $('input[name="situation"]').val(situaTion);
            $('input[name="status"]').val(actStatus);
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

        function post(url, params) {
            var temp = document.createElement("form");
            temp.action = url;
            temp.method = "post";
            temp.style.display = "none";
            for (var x in params) {
                var opt = document.createElement("input");
                opt.name = x;
                opt.value = params[x];
                temp.appendChild(opt);
            }
            document.body.appendChild(temp);
            temp.submit();
            return temp;
        }

        $(document).on('click','#j-export',function(){

            if (!(actKey.length)) {
                post("<?= $Path;?>/finance/order/daochu?status="+ actStatus +"&situation=" + situaTion, actKey);
            }else{
                post("<?= $Path;?>/finance/order/daochu?status="+ actStatus +"&situation=" + situaTion, {actKey});
            }

        })

        $(document).on('focus', '.search-text,.gaoji .form', function () {
            clearInterval(timer);
        });
        $(document).on('blur', '.search-text,.gaoji .form', function () {
            timer = setInterval(function () {
                getData()
            }, 30000);

        });
        var timer =  setInterval(function () {
            getData()
        }, 30000);

        $(document).on('click','.sou',function(){
            $('.username').toggle('show');
            $('.search').toggle('show');
            $('.gaoji').parent().toggle('show');

        })

        $(document).on('click','.closel',function(){
            $('input[type="reset"]').click();
            $('.username').toggle('show');
            $('.search').toggle('show');
            $('.gaoji').parent().toggle('show');
            actKey = '';
            getData()
        })

        $(document).on('click', '#endTime', function() {
            $('#endTime').data('hasChange',true);
            if($(this).data('hasChange')) {
                laydate({
                    elem: '#endTime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    istoday: false,
                    choose: function(dates){
                        // $('#billTime').change()
                    }
                });
            }
        })

        $(document).on('click', '#startTime', function() {
            $('#startTime').data('hasChange',true);
            if($(this).data('hasChange')) {
                laydate({
                    elem: '#startTime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    istoday: false,
                    choose: function(dates){
                        // $('#billTime').change()
                    }
                });
            }
        })

        $(document).on('keypress','.search-text', function(e) {
            if(e.keyCode == 13) {
                actKey = $(this).val(), actPage = 1, actStatus = -1;
                getData()
            }
        })



        $(document).on('click','.a-search',function(){
            actKey = {
                startTime : $('#startTime').val(),
                endTime   : $('#endTime').val(),
                billShipperNumber   : $('input[name="billShipperNumber"]').val(),
                billDriverNumber  : $('input[name="billDriverNumber"]').val(),
                dealer    : $('input[name="dealer"]').val(),
                reference : $('input[name="reference"]').val()
            };
            getData()
        })


    })



</script>
<?php $this->endBlock();  ?>
