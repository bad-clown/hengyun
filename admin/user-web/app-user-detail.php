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
			<li><a href="<?= $Path;?>/admin/user-web/app-users">用户管理</a></li>
			<li><a href="<?= $Path;?>/admin/user-web/app-users">后台管理员</a></li>
			<li class="active">APP用户详情</li>
		</ul>
		<!-- <a href="javascript:;" id="j-save-control" class="btn save-control" style="display: none;">保存</a> -->
		<!-- <a href="javascript:;" id="j-cancel-control" class="btn cancel-control" style="display: none;">取消</a> -->
		<a href="<?= $Path;?>/admin/user-web/app-users" id="j-back-control" class="btn back-control">返回</a>
	</div>

	<div class="detail-box pb100">
		<div class="clearfix" id="J-user-detail"></div>
	</div>

	<div class="control-panel" style="display: none;">
		<div class="control-btns">
			<a href="javascript:;" class="btn-pub" id="j-pass">通过</a>
			<a href="javascript:;" class="btn-del" id="j-reject">不通过</a>
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

			var userHTML = '<div class="form-group label-floating"><label for="status" class="control-label">状态</label><input class="form-control" readonly="readonly" name="status" value="'+ status[data.authStatus] +'" type="text"></div><div class="form-group label-floating"><label class="control-label">手机号</label><input class="form-control" readonly="readonly" name="phone" value="'+ data.phone +'" type="text"></div><div class="form-group label-floating"><label class="control-label">类型</label><input class="form-control" readonly="readonly" name="type" value="'+ type[data.type] +'"></div><div class="form-group label-floating"><label class="control-label">用户名</label><input class="form-control" readonly="readonly" name="username" value="'+ (data.nickname ||"暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">真实姓名</label><input class="form-control" readonly="readonly" name="name" value="'+ (data.name||"暂无") +'" type="text"></div><div class="form-group label-floating"><label class="control-label">身份证号</label><input class="form-control" readonly="readonly" name="id" value="'+ (data.id||"暂无") +'" type="text"></div><div class="form-group pictureBox"><label class="control-label">头像</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+avatar+'</div></div><div class="form-group pictureBox"><label class="control-label">身份证正面</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+idFront+'</div></div><div class="form-group pictureBox"><label class="control-label">身份证反面</label><div class="input-group"><input type="file" id="inputFile4" multiple=""><span class="glyphicon glyphicon-repeat"></span><label class="control-label">更换</label></div><div class="picture">'+idBack+'</div></div>';

            $user.append(userHTML);
            if (data.authStatus == 1) {
                $('.control-panel').show();
            }

		}
	})

	$('#j-pass').on('click', function() {
		$.getJSON($_Path+'/admin/user/auth?id=<?= $_id;?>&status=2', function(data) {
			if(data.code == '0') {
				alert('认证通过！');
				window.location.reload();
			}
		})
	})
	$('#j-reject').on('click', function() {
		$.getJSON($_Path+'/admin/user/auth?id=<?= $_id;?>&status=3', function(data) {
			if(data.code == '0') {
				alert('认证不通过！');
				window.location.reload()
			}
		})
	})
})
</script>
<?php $this->endBlock();  ?>
