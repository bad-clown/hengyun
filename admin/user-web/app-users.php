
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
            <li><a href="<?= $Path;?>/admin/user-web/managers">用户管理</a></li>
            <li class="active">后台管理员</li>
        </ul>
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
                    <th>手机号</th>
                    <th style="text-align:center;">姓名</th>
                    <th style="text-align:center;">用户名</th>
                    <th width="102">
                    <div class="navbar-collapse collapse navbar-inverse-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">类型<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu" id="TypeFilter">
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
    var actPage = 1, actStatus = "", actType = "";
    var PageTotal = {
        init : function(d) {
            this.current = actPage,     //当前页
            this.pageCount = 10,        //每页显示的数据量
            this.total = d.pageCnt,     //总共的页码
            this.first = 1,             //首页
            this.last = 0,              //尾页
            this.pre = 0,               //上一页
            this.next = 0,              //下一页
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
        var role = {
            company : "（公司）",
            pernson : "（个人）"
        }
        $.ajax({
            type : "GET",
            url : "<?= $Path;?>/admin/user/app-users",
            data : {
                page : actPage,
                status : actStatus,
                type : actType
            },
            dataType : "json",
            success : function(d) {
                var data = d.data,
                    c = $('#listContent').find('tbody');
                if(data.list.length) {
                    PageTotal.init(data)
                    c.empty();
                    $.each(data.list, function(i,o) {
                        var block = o.isBlocked ? '启用' : '禁用';
                        var t = _global.FormatTime(o.created_at);
                        var h = '<tr><td align="center">'+status[o.authStatus]+'</td><td>'+ o.phone +'</td><td align="center">'+(o.name || "暂无")+'</td><td align="center">'+(o.nickname || "暂无")+'</td><td align="center">'+type[o.type]+(role[o.role] || "")+'</td><td align="center">'+t+'</td><td width="250"><a class="btn-default" href="<?= $Path;?>/admin/user-web/app-user-detail?id='+o._id+'">查看详情</a><a class="btn-danger j-block" href="javascript:;" data-block="'+block+'" data-key="'+o._id+'">'+block+'</a><a class="btn-danger j-delete" href="javascript:;" data-key="'+o._id+'">删除</a></td></tr>';
                        c.append(h)
                    })
                }
                else {
                    $('.pagination').empty()
                    c.empty();
                    var h = '<tr><td align="center" colspan="6">暂无数据</td></tr>';
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

    $('#StatusFilter a').on('click', function() {
        actPage = 1;
        actStatus = $(this).data('status');
        getData()
    })

    $('#TypeFilter a').on('click', function() {
        actPage = 1;
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
})
</script>
<?php $this->endBlock();  ?>
