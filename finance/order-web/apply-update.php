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
			<li class="active">订单修改申请</li>
		</ul>
        <a href="javascript:;" class="btn back-control" id="j-back-control">返回</a>
	</div>

	<div class="listBox orderList">
		<table class="table table-striped table-hover" id="listContent">
			<thead>
				<tr>
					<th>申请时间</th>
					<th>订单号</th>
					<th>申请人</th>
					<th>申请原因</th>
					<th>拒绝原因</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<ul class="pagination"></ul>
	</div>
</div>
<div class="shipper-pop popup" style="z-index: 9999">
    <a href="javascrip:void(0);" class="glyphicon glyphicon-remove close-btn"></a>
    <div class="popup-header"></div>
    <div class="popup-main">
        <div class="popup-breadcrumb">
            <div class="breadcrumbBox">
                <ul class="breadcrumb">
                    <li>审批信息</li>
                </ul>
                <span><a href="javascript:;" class="btn btn-primary consent"  data-key="2">同意</a></span>
                <span style="position: absolute;right: 100px"><a href="javascript:;" class="btn btn-danger consent"  data-key="-1">不同意</a></span>
            </div>
            <div class=" ">
                <div class="form-control-static">
                    <input tpye="text" class="form-control" name="reason" />
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">

	$(function() {

        var actPage = 1, actKey = '',approve = '', orderId = '';
        var approveKey = "<?= $approve;?>" || -2;
        actKey = $('.search-text').val() || '';
        approve = approveKey == -1 ? approveKey : (approveKey == -2 ? approveKey : (approveKey*1 + 1));

        function getData() {

            $.ajax({
                type: "post",
                url: "<?= $Path;?>/finance/order/list?page="+ actPage +"&approve="+ approve,
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
                            var t = _global.FormatTime(o.applicantList.applyTime);
                            var button = '';
                            <?php if ($manager) {;?>
                                button = '<a href="javascript:;" class="btn-default J-Consent" style="display: none;" data-key="'+ o._id+'" >审批</a>';
                            <?php } else {;?>
                                button = '<a href="javascript:;" class="btn-default J-Consent" style="display: none;">审批中</a>';
                            <?php };?>

                            var h = '<tr><td align="center">' + t + '</td><td>'+ o.orderNo +'</td><td>'+ o.applicantList.name +'</td><td>'+ (o.applicantList.reason || '') +'</td><td>'+ (o.applicantList.opinion || '') +'</td><td width="170" style="text-align:left"><a class="btn-default" href="<?= $Path;?>/finance/order-web/detail?id=' + o._id + '">查看详情</a>'+ button +'</td></tr>';
                            c.append(h);

                            switch ( approve ) {
                                case 1:
                                    <?php if (!$manager) {;?>
                                    $(document).off('click','.J-Consent')
                                    <?php };?>
                                    $('.J-Consent').show()
                                    break;
                                case 2:
                                    $(document).off('click','.J-Consent')
                                    $('.J-Consent').text('待修改').addClass('has-driver').show()
                                    break;
                                case 3:
                                    $(document).off('click','.J-Consent')
                                    $('.J-Consent').text('修改完成').show()
                                    break;
                                case "-1":
                                    $(document).off('click','.J-Consent')
                                    $('.J-Consent').text('不同意').addClass('has-driver').show()
                                    break;
                            }


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

        $('.close-btn').on('click',function(){
            $('.shipper-pop').hide()
            $('.consent-button').remove()
            $("#mask").hide()
        })



        $(document).on('click','.J-Consent',function() {
            orderId = $(this).data('key')
            $('.shipper-pop').show()
            var mainheight = $(document).height()+30
            $("#mask").css({height:mainheight + 'px',width:'100%',display:'block'})
            $("#mask").show()

        })


        $(document).on('click','.consent',function() {
            var apKey = $(this).data('key')
            var opinion = $('input[name="reason"]').val()
            $.ajax({
                type : "get",
                url : '<?= $Path;?>/finance/order/apply?id='+orderId,
                data : {
                    apKey : apKey,
                    opinion : opinion
                },
                dataType : 'json',
                success : function(data) {
                    if(data.code == 0) {
                        alert('操作成功!');
                        window.location.reload();
                    }
                    else {
                        alert('操作失败！请检查后重试！')
                    }
                },
                error : function() {
                    alert("操作失败，请检查网络后重试！");
                }

            })
        })

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

        $(document).on('click','.sou',function(){
            $('.username').toggle('show');
            $('.search').toggle('show');
            $('.gaoji').parent().toggle('show');

        })

        $(document).on('click','.closel',function() {
            $('input[type="reset"]').click();
            $('.username').toggle('show');
            $('.search').toggle('show');
            $('.gaoji').parent().toggle('show');
            actKey = '';
            getData()
        })


        $(document).on('keypress','.search-text', function(e) {
            if(e.keyCode == 13) {
                actKey = $(this).val(), actPage = 1, actStatus = -1;
                getData()
            }
        })

        $('#j-back-control').on('click',function(){
            window.history.go(-1);
        })
    })

</script>
<?php $this->endBlock();  ?>
