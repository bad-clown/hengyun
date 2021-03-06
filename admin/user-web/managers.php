
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;
$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
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
            <li><a href="<?= $Path;?>/admin/user-web/managers">用户管理</a></li>
            <li class="active">后台管理员</li>
        </ul>
        <a href="<?= $Path;?>/admin/user-web/create" class="btn btn-control">
            <span class="glyphicon glyphicon-plus"></span>
            创建用户
        </a>
    </div>

    <div class="listBox orderList">
        <?php if (Yii::$app->getSession()->hasFlash('admin_user')): ?>
            <div class="alert alert-success">
                <p><?= Yii::$app->getSession()->getFlash('admin_user') ?></p>
            </div>
        <?php endif; ?>
        <table class="table table-striped table-hover" id="listContent">
            <thead>
                <tr>
                    <th>手机号</th>
                    <th>姓名</th>
                    <th width="102">
                    <div class="navbar-collapse collapse navbar-inverse-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">类型<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="javascript:void(0)" data-type="">全部</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-type="admin">管理员</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-type="sched">交易员</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-type="finance">账务员</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    </th>
                    <th style="text-align:center;">注册时间</th>
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
    var actPage = 1, actType = "",actKey = "";
    actKey = $('.search-text').val();
    function getData() {
        var type = {
            admin : "管理员",
            sched : "交易员",
            finance : "财务员"
        };
        $.ajax({
            type : "GET",
            url : "<?= $Path;?>/admin/user/managers",
            data : {
                page : actPage,
                type : actType,
                key :  actKey
            },
            dataType : "json",
            success : function(d) {
                var data = d.data,
                    c = $('#listContent').find('tbody');

                if(data.list.length) {
                    PageTotal.init('.pagination', data, actPage)
                    c.empty();
                    $.each(data.list, function(i,o) {
                        var block = o.isBlocked ? '启用' : '禁用';
                        var t = _global.FormatTime(o.created_at);
                        var h = '<tr><td>'+ o.phone +'</td><td>'+ (o.name || "暂无") +'</td><td align="center">'+type[o.type]+'</td><td align="center">'+t+'</td><td width="250"><a class="btn-default" href="<?= $Path;?>/admin/user-web/update?id='+o._id+'">编辑</a><a class="btn-danger j-block" href="javascript:;" data-block="'+block+'" data-key="'+o._id+'">'+block+'</a></td></tr>';
                        c.append(h)
                    })
                }
                else {
                    $('.pagination').empty()
                    c.empty();
                    var h = '<tr><td align="center" colspan="4">暂无数据</td></tr>';
                    c.append(h)
                }
            }
        })
    }
//    <a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a>
    getData()

    $(document).on("click", '.pagination a', function() {
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

    $('.dropdown-menu a').on('click', function() {
        actPage = 1,actKey = '';;
        actType = $(this).data('type');
        getData()
    })

    $(document).on('click', '.j-delete', function() {
        var k = $(this).data('key');
        if(confirm("确定删除该用户吗？")) {
            $.ajax({
                type : "GET",
                url : "<?= $Path;?>/admin/user/delete?id="+k,
                dataType : "json",
                success : function(data) {
                    if(data.code == '0') {
                        alert('删除成功！')
                        getData()
                    }
                    else {
                        alert('删除失败，请重试！')
                    }
                },
                error : function() {
                    alert("删除失败，请检查网络后重试！");
                }
            })
        }
    })

    $(document).on('click', '.j-block', function() {
        var k = $(this).data('key');
        var b = $(this).data('block');

        if(confirm("确定"+b+"该用户吗？")) {
            $.ajax({
                type : "GET",
                url : "<?= $Path;?>/admin/user/block?id="+k,
                dataType : "json",
                success : function(data) {
                    if(data.code == '0') {
                        alert(b+'成功！')
                        getData()
                    }
                }
            })
        }
    })

    $('.search-text').on('keypress', function(e) {
        if(e.keyCode == 13) {
            actKey = $(this).val(), actPage = 1, actType = '' ;
            getData()
        }
    })
})
</script>
<?php $this->endBlock();  ?>
