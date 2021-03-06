<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = \Yii::$app->request->hostInfo;
$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
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
            <li><a href="<?= $Path;?>/admin/user-web/managers">后台管理员</a></li>
            <li class="active">修改后台管理员</li>
        </ul>
        <a href="javascript:;" id="j-save-control" class="btn save-control">保存</a>
        <a href="javascript:;" class="btn back-control" id="j-back-control">返回</a>
    </div>

    <div class="create-box clearfix" id="J-user-detail">
    </div>
</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
$(function() {
	$.ajax({
		type : "GET",
		url : "<?= $Path;?>/admin/user/detail?id=<?= $_id;?>",
		dataType : "json",
		success : function(data) {
			var $user = $('#J-user-detail');
			var userHTML = '<div class="form-group"><label class="control-label">手机号</label><input class="form-control" name="phone" value="'+data.phone+'" type="text"></div><div class="form-group"><label class="control-label">姓名</label><input class="form-control" name="name" value="'+(data.name||"")+'" type="text"></div><div class="form-group"><label class="control-label">密码</label><input class="form-control" name="password" value="" type="password"></div><div class="form-group"><label class="control-label">工号</label><input type="text" class="form-control" name="reference" value="'+ (data.reference || '') +'" /></div><div class="form-group"><label class="control-label">类型</label><select name="type" class="form-control"><option value="admin">管理员</option><option value="sched">交易员</option><option value="finance">账务员</option></select></div>';
            $user.append(userHTML);
            $('select[name="type"]').val(data.type).triggerHandler("change");
		}
	})


    $('#j-save-control').on('click', function() {
        var phone = $("input[name='phone']").val();
        var name = $("input[name='name']").val();
        var password = $("input[name='password']").val();
        var reference = $("input[name='reference']").val();
        var type = $("select[name='type']").val();
        var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
        if(!reg.test(phone)) {
            alert('请输入正确手机号码！');
            $("input[name='phone']").focus();
            return;
        }
        if (!name) {
            alert('姓名不能为空！');
            $("input[name='name']").focus();
            return;
        }
        if(!password) {
            alert('密码不能为空！');
            $("input[name='password']").focus();
            return;
        }
        var data = {
            phone : phone,
            name : name,
            password : password,
            reference : reference,
            type : type
        }
        $.ajax({
            type : "post",
            url : '<?= $Path;?>/admin/user/update?id=<?= $_id;?>',
            data : data,
            success : function(data) {
                if(data.code == '0') {
                    alert('更新成功！')
                    window.location.href = "<?= $Path;?>/admin/user-web/managers";
                }
                else {
                    alert('更新失败！')
                }
            },
            error : function() {
                alert("更新失败，请检查网络后重试！");
            }
        })
    })

    $('#j-back-control').on('click',function(){
        window.history.go(-1);
    })
})
</script>
<?php $this->endBlock();  ?>
