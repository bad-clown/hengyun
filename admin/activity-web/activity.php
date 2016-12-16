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
			<li class="active">活动中心</li>
		</ul>

        <div class="btn btn-control">
            <span class="glyphicon glyphicon-plus"></span>
            <a href="<?= $Path;?>/admin/activity-web/new-activity">新增活动</a>
        </div>

	</div>
    <div class="detail-label"><span class="label label-default">活动列表</span></div>
	<div class="listBox orderList">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th>活动标题</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>活动内容</th>
					<th>活动状态</th>
					<th>发布时间</th>
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
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">


	$(function() {

        var actPage = 1, actStatus = 1, actKey = '';
        // 获取搜索栏内容
        actKey = $('.search-text').val();

        function getData() {

            $.ajax({
                type: "GET",
                url: "<?= $Path;?>/admin/activity/list",
                data: {
                    key: actKey,
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
                            var startTime = _global.FormatTime(o.startTime);
                            var endTime = _global.FormatTime(o.endTime);
                            var publisheTime = _global.FormatTime(o.publisheTime);
                            var status = o.status ? '发布中':'未发布';
                            var delBtn = '';
                            if(o.status >=0) {
                            	delBtn = '<a class="btn-danger j-delete" href="javascript:;" data-key="' + o._id + '">删除</a>';
                            }
                            var h = '<tr><td align="center">'+ o.title +'</td><td>' + startTime + '</td><td>' + endTime + '</td><td>' + o.content + '</td><td>'+ status +'</td><td>'+ publisheTime +'</td><td width="175"><a class="btn-default" href="<?= $Path;?>/admin/activity-web/detail-activity?id=' + o._id + '">查看详情</a>'+ delBtn +'</td></tr>';
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
            actStatus = $(this).data('status');
            getData()
        })

        $(document).on('click', '.j-delete', function () {
            var k = $(this).data('key');
            if (confirm("确定要删除活动吗？")) {
                $.ajax({
                    type: "GET",
                    url: "<?= $Path;?>/admin/activity/delete?id=" + k,
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

        })

        $('#open-create').on('click', function() {

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
                actKey = $(this).val(), actPage = 1, actStatus = -1;
                getData()
            }
        })

    });

</script>
<?php $this->endBlock();  ?>
