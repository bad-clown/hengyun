<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;
$this->title = Yii::t('app', 'Sched Routers');
$this->params['breadcrumbs'][] = $this->title;

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
            <li class="active">路线管理</li>
        </ul>
        <a href="<?= $Path;?>/admin/sched-router/create-web" class="btn btn-control">
            <span class="glyphicon glyphicon-plus"></span>
            新增路线
        </a>
    </div>

    <div class="sched-router">
        <div class="detail-label"><span class="label label-default">其他路线</span></div>
        <div class="clearfix" id="">
            <div class="form-group">
                <label for="name" class="control-label">调度员</label>
                <select id="name" name="name" class="form-control" disabled="disabled"></select>
            </div>
            <div class="form-group">
                <label class="control-label">联系电话</label>
                <input class="form-control" name="phone" id="phone" value="" type="text" readonly="readonly">
            </div>
            <a href="javascript:;" class="btn btn-update">修改</a>
            <a href="javascript:;" class="btn btn-save btn-disabled">保存</a>
        </div>
    </div>
</div>

<div class="content">
    <div class="listBox pt15">
        <div class="detail-label"><span class="label label-default">特定路线</span></div>
        <table class="table table-striped table-hover" id="listContent">
            <thead>
                <tr>
                    <th>出发省份</th>
                    <th>到达省份</th>
                    <th>调度员</th>
                    <th>联系电话</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- <ul class="pagination"></ul> -->
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    var phoneData=[], actPage = 1;

    function getData() {
        $.ajax({
            type : "GET",
            url : "<?= $Path;?>/admin/sched-router/route-list",
            data : {
                page : actPage
            },
            dataType : "json",
            success : function(data) {
                var c = $('#listContent').find('tbody');
                if(data.length) {
                    // PageTotal.init('.pagination', data, actPage)
                    c.empty();
                    $.each(data, function(i,o) {
                        var name = o.name || "暂无";
                        var h = '<tr><td>'+o.provinceFrom+'</td><td>'+o.provinceTo+'</td><td>'+name+'</td><td>'+o.phone+'</td><td width="170"><a class="btn-default" href="<?= $Path;?>/admin/sched-router/update-web?id='+o._id+'">修改</a><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
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
    getData()

    $(document).on('click', '.j-delete', function() {
        var k = $(this).data('key');
        if(confirm("确定删除？")) {
            $.ajax({
                type : "GET",
                url : "<?= $Path;?>/admin/sched-router/delete?id="+k,
                dataType : "json",
                success : function(data) {
                    if(data.code == '0') {
                        alert('删除成功！')
                        getData()
                    }
                    else {
                        alert('删除失败，请重试！')
                        getData()
                    }
                },
                error : function() {
                    alert("删除失败，请检查网络后重试！");
                }
            })
        }
    })

    $.ajax({
        type : 'get',
        url : '<?= $Path;?>/admin/sched-router/default-sched',
        dataType : 'json',
        success : function(data) {
            var name = data.name || '暂无';
            var phone = data.phone || '暂无';
            $('select[name="name"]').get(0).options.add(new Option(name,data._id));
            $('input[name="phone"]').val(phone);
        }
    })

    $('.btn-update:eq(0)').on('click', function() {
        if(!$(this).data('disabled')) {
            $(this).addClass('btn-disabled')
            $('.btn-save:eq(0)').removeClass('btn-disabled').data('disabled', false);
            var $select = $('select[name="name"]')
            $select.removeAttr('disabled');
            $select.parent('.form-group').addClass('select-menu');

            $.ajax({
                type : 'get',
                url : '<?= $Path;?>/admin/sched-router/sched-list',
                dataType : 'json',
                success : function(data) {
                    var n = $select.val();
                    $select.empty();
                    $.each(data, function(i, o) {
                        var name = o.name || '暂无';
                        var phone = o.phone || '暂无';
                        $select[0].options.add(new Option(name,o._id));
                        phoneData.push({
                            _id : o._id,
                            phone : phone
                        })
                    })
                    $select.val(n).triggerHandler("change");
                }
            })

            $(this).data('disabled', true)
        }
    })

    $('.btn-save:eq(0)').on('click', function() {
        if(!$(this).data('disabled')) {
            var k = $('select[name="name"]').val();
            $.ajax({
                type : 'get',
                url : '<?= $Path;?>/admin/sched-router/update-default?id='+k,
                dataType : 'json',
                success : function(data) {
                    if(data.code == 0) {
                        alert('保存成功！')
                        $('.btn-save:eq(0)').addClass('btn-disabled').data('disabled', true);
                        $('.btn-update:eq(0)').removeClass('btn-disabled').data('disabled', false);
                    }
                    else {
                        alert('保存失败，请重试！')
                    }
                },
                error : function() {
                    alert("保存失败，请检查网络后重试！");
                }
            })
        }
    })

    $('select[name="name"]').on('change', function() {
        var _this = $(this);
        $.each(phoneData, function(i, o) {
            if(_this.val() == o._id) {
                $('input[name="phone"]').val(o.phone);
            }
        })
    })
})
</script>
<?php $this->endBlock();  ?>
