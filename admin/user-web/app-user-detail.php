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
			<li><a href="<?= $Path;?>/finance/bill-shipper-web/list">用户管理</a></li>
			<li><a href="<?= $Path;?>/finance/bill-shipper-web/list">后台管理员</a></li>
			<li class="active">APP用户详情</li>
		</ul>
		<!-- <a href="javascript:;" id="j-save-control" class="save-control" style="display: none;">保存</a>
		<a href="javascript:;" id="j-cancel-control" class="cancel-control" style="display: none;">取消</a>
		<a href="<?= $Path;?>/finance/bill-shipper-web/list" id="j-back-control" class="back-control">返回</a> -->
	</div>

	<div class="detail-box pb100">
		<div class="clearfix" id="J-user-detail"></div>
	</div>

	<div class="control-panel">
		<div class="control-btns">
			<a href="javascript:;" class="btn-pub">通过</a>
			<a href="javascript:;" class="btn-del">不通过</a>
		</div>
		<div class="panel-label"><span></span></div>
	</div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
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
		url : "<?= $Path;?>/admin/user/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			var $user = $('#J-user-detail');

			var avatar = data.avatar ? "<img src='<?= $Path;?>"+data.avatar+"' />" : '未上传';
			var idFront = data.idFront ? "<img src='<?= $Path;?>"+data.idFront+"' />" : '未上传';
			var idBack = data.idBack ? "<img src='<?= $Path;?>"+data.idBack+"' />" : '未上传';

			var userHTML = '<div class="form-group label-floating"><label for="status" class="control-label">状态</label><input class="form-control" readonly="readonly" name="status" value="'+ status[data.authStatus] +'" type="text"></div><div class="form-group label-floating"><label class="control-label">手机号</label><input class="form-control" readonly="readonly" name="phone" value="'+ data.phone +'" type="text"></div><div class="form-group label-floating"><label class="control-label">类型</label><input class="form-control" readonly="readonly" name="type" value="'+ type[data.type] +'"></div><div class="form-group label-floating"><label class="control-label">用户名</label><input class="form-control" readonly="readonly" name="username" value="'+ (data.username||"暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">真实姓名</label><input class="form-control" readonly="readonly" name="name" value="'+ (data.name||"暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" name="id" value="'+ (data.id||"暂无") +'" type="text"></div><div class="form-group pictureBox"><label class="control-label">头像</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+avatar+'</div></div><div class="form-group pictureBox"><label class="control-label">身份证正面</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+idFront+'</div></div><div class="form-group pictureBox"><label class="control-label">身份证反面</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+idBack+'</div></div>';

			$user.append(userHTML);

		}
	})

	$('#j-mod-bill').on('click', function() {
		$('#j-save-control').show()
		$('#j-cancel-control').show()
		$('#j-back-control').hide()

		$('select[name="status"]').removeAttr('disabled')
 		$('input[name="billTime"]').attr('id', 'billTime')
		$('input[name="title"]').removeAttr('readonly', 'readonly')
		$('input[name="tfn"]').removeAttr('readonly', 'readonly')
		$('input[name="address"]').removeAttr('readonly')
		$('input[name="tel"]').removeAttr('readonly')
		$('input[name="bank"]').removeAttr('readonly')
		$('input[name="bankId"]').removeAttr('readonly')
		$('input[name="mailAddress"]').removeAttr('readonly')
		$('input[name="mailTel"]').removeAttr('readonly')

		$('.control-panel').hide();
		$(window).scrollTop(0)
	})
	$('#j-cancel-control').on('click', function() {
		$('#j-save-control').hide()
		$('#j-cancel-control').hide()
		$('#j-back-control').show()

		$('select[name="status"]').attr('disabled', 'disabled')
 		$('input[name="billTime"]').removeAttr('id')
		$('input[name="title"]').attr('readonly', 'readonly')
		$('input[name="tfn"]').attr('readonly', 'readonly')
		$('input[name="address"]').attr('readonly', 'readonly')
		$('input[name="tel"]').attr('readonly', 'readonly')
		$('input[name="bank"]').attr('readonly', 'readonly')
		$('input[name="bankId"]').attr('readonly', 'readonly')
		$('input[name="mailAddress"]').attr('readonly', 'readonly')
		$('input[name="mailTel"]').attr('readonly', 'readonly')

		$('.control-panel').show();
	})

	$(document).on('click', '#billTime', function() {
		laydate({
			elem: '#billTime',
			format: 'YYYY-MM-DD hh:mm:ss',
			istime: true,
			istoday: false,
			choose: function(dates){
				// $('#billTime').change()
			}
		});
	})

	$('#j-save-control').on('click', function() {
		var $orderList = $('#orderList>tbody').find("tr");
		var orderList = [];
		for(var i=0;i<$orderList.length;i++) {
			orderList.push($orderList.eq(i).data('key'));
		}
		var billTime = Date.parse($('input[name="billTime"]').val()) /1000;

		var data = {
			status : $('select[name="status"]').val(),
			billNo : $('input[name="billNo"]').val(),
			billTime : billTime,
			totalMoney : $('input[name="totalMoney"]').val(),
			orderCnt : parseInt($('input[name="orderCnt"]').val()),
			title : $('input[name="title"]').val(),
			tfn : $('input[name="tfn"]').val(),
			address : $('input[name="address"]').val(),
			tel : $('input[name="tel"]').val(),
			bank : $('input[name="bank"]').val(),
			bankId : $('input[name="bankId"]').val(),
			mailAddress : $('input[name="mailAddress"]').val(),
			mailTel : $('input[name="mailTel"]').val(),
			orderList : orderList || []
		}

		// console.log(data)

		$.ajax({
			type : "post",
			url : "<?= $Path;?>/finance/bill-shipper/modify?id=<?= $_id;?>",
			data : data,
			dataType : 'json',
			success : function(data) {
				if(data.code == '0') {
					alert('保存成功！');
					window.location.reload();
				}
				else {
					alert('保存失败，请检查账单后重试！');
				}
			},
			erroe : function() {
				alert("保存失败，请检查网络后重试！");
			}
		})
	})


	$(document).on('click', '#create-order-detail', function() {
		$('.order-detail-pop').show();
		$('.overlay:eq(0)').show();
	})

	$('.close-btn').on('click', function() {
		$(this).parents('.popup').hide();
		$('.overlay:eq(0)').hide();
	})
})
</script>
<?php $this->endBlock();  ?>
