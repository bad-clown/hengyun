
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
            <li class="active">APP用户</li>
        </ul>
    </div>

    <div class="listBox orderList">
        <div class="summary">注册用户共<b id="userCnt"></b>位</div>
        <table class="table table-striped table-hover" id="listContent">
            <thead>
                <tr>
                    <th width="102">
                    <div class="navbar-collapse collapse navbar-inverse-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">状态<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu" id="StatusFilter">
                                    <li>
                                        <a href="javascript:void(0)" data-status="">全部</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-status="0">未认证</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-status="1">待认证</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-status="2">认证通过</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-status="3">认证不通过</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    </th>
                    <th>用户名</th>
                    <th style="text-align:center;">姓名</th>
                    <th style="text-align:center;">昵称</th>
                    <th width="130">
                    <div class="navbar-collapse collapse navbar-inverse-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">类型<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu" id="TypeFilter" style="min-width: 114px;">
                                    <li>
                                        <a href="javascript:void(0)" data-type="">全部</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-type="driver">司机</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-type="shipper">货主</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    </th>
                    <th style="text-align:center;">注册时间</th>
                    <th>公司名称</th>
                    <th>联系人</th>
                    <th>推荐码</th>
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
    var actPage = 1, actStatus = "", actType = "", actKey = "";
    actKey = $('.search-text').val();
    function getData() {
        var type = {
            driver : "司机",
            shipper : "货主"
        };
        var status = {
            0 : "未认证",
            1 : "待认证",
            2 : "认证通过",
            3 : "认证不通过"
        };
        var category = {
            0 : "（公司）",
            1 : "（个人/车队）"
        }

        $.ajax({
            type : "GET",
            url : "<?= $Path;?>/admin/user/app-users",
            data : {
                page : actPage,
                status : actStatus,
                type : actType,
                key : actKey
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
                        var company = (o.company || '暂无');
                        var name = (o.name || '暂无');
                        var t = _global.FormatTime(o.created_at);
                        var h = '<tr><td align="center">'+status[o.authStatus]+'</td><td>'+ o.phone +'</td><td align="center">'+(o.name || "暂无")+'</td><td align="center">'+(o.nickname || "暂无")+'</td><td align="center">'+type[o.type]+(category[o.category] || "")+'</td><td align="center">'+t+'</td><td>'+ company  +'</td><td>'+ name +'</td><td>'+ (o.refCode || '暂无') +'</td><td width="180"><a class="btn-default" href="<?= $Path;?>/admin/user-web/app-user-detail?id='+o._id+'">查看详情</a><a class="btn-danger j-block" href="javascript:;" data-block="'+block+'" data-key="'+o._id+'">'+block+'</a></td></tr>';
                        c.append(h)
                    })
                }
                else {
                    $('.pagination').empty()
                    c.empty();
                    var h = '<tr><td align="center" colspan="6">暂无数据</td></tr>';
                    c.append(h)
                }

                $('#userCnt').html(data.cnt);

            }
        })
    }
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

    $('#StatusFilter a').on('click', function() {
        actPage = 1, actKey = '';
        actStatus = $(this).data('status');
        getData()
    })

    $('#TypeFilter a').on('click', function() {
        actPage = 1, actKey = '';
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
                        getData()
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
            actKey = $(this).val(), actPage = 1, actStatus = '';
            getData()
        }
    })
})
</script>
<?php $this->endBlock();  ?>