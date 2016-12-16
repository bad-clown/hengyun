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
        <input type="text" class="search-text" name="search" value="" placeholder="搜索" />
        <i class="glyphicon glyphicon-search"></i>
    </div>
    <div class="username">
        <a href="#"><?= \Yii::$app->user->identity->phone;?></a> | <a href="<?= $Path;?>/user/logout-web" target="_parent" data-method="post">安全退出</a>
    </div>
</div>

<div class="content">
    <div class="breadcrumbBox">
        <ul class="breadcrumb">
        	<li><a href="<?= $Path;?>/admin/sched-router">路线管理</a></li>
            <li class="active">新增路线</li>
        </ul>
        <a href="javascript:;" id="j-save-control" class="btn save-control">保存</a>
        <a href="<?= $Path;?>/admin/sched-router" class="btn back-control">返回</a>
    </div>

    <div class="sched-router ">
	    <div class="clearfix">
	    	<div class="form-group select-menu">
	            <label for="provinceFrom" class="control-label">出发省份</label>
	            <select id="provinceFrom" name="provinceFrom" class="form-control"></select>
	        </div>
	        <div class="form-group select-menu">
	            <label for="provinceTo" class="control-label">到达省份</label>
	            <select id="provinceTo" name="provinceTo" class="form-control"></select>
	        </div>
	    </div>
	    <div class="clearfix">
	    	<div class="form-group select-menu">
                <label for="name" class="control-label">交易员</label>
                <select id="name" name="name" class="form-control"></select>
            </div>
	        <div class="form-group">
	            <label for="phone" class="control-label">联系电话</label>
	            <input class="form-control" name="phone" id="phone" value="" type="text">
	        </div>
	    </div>
    </div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
	var phoneData = [];
	$.ajax({
    	type : 'get',
    	url : '<?= $Path;?>/admin/city/province',
    	dataType : 'json',
    	success : function(data) {
    		$('#provinceFrom').get(0).options.add(new Option('请选择出发省',0));
            $('#provinceTo').get(0).options.add(new Option('请选择到达省',0));
    		$.each(data, function(i, o) {
                $('#provinceFrom').get(0).options.add(new Option(o.name,o.code));
                $('#provinceTo').get(0).options.add(new Option(o.name,o.code));
            })
    	}
    })
    $.ajax({
        type : 'get',
        url : '<?= $Path;?>/admin/sched-router/sched-list',
        dataType : 'json',
        success : function(data) {
        	$('#name').get(0).options.add(new Option('请选择交易员员', 0));
            $.each(data, function(i, o) {
                var name = o.name || '暂无';
                var phone = o.phone || '暂无';
                $('#name').get(0).options.add(new Option(name,o._id));
                phoneData.push({
                    _id : o._id,
                    phone : phone
                })
            })
        }
    })
    $('#name').on('change', function() {
        var _this = $(this);
        $.each(phoneData, function(i, o) {
            if(_this.val() == o._id) {
                $('input[name="phone"]').val(o.phone);
            }
        })
    })

	$('#j-save-control').on('click', function() {
		if($('#provinceFrom').val()==0){ alert('请选择出发省');return false;}
		if($('#provinceTo').val()==0){ alert('请选择到达省');return false;}
		if($('#name').val()==0){ alert('请选择交易员');return false;}

		var data = {
			provinceFrom : $('#provinceFrom').val(),
			provinceTo : $('#provinceTo').val(),
			userId : $('#name').val(),
		}

		$.ajax({
			type : 'post',
			url : '<?= $Path;?>/admin/sched-router/create',
			data : data,
			success : function(data) {
				if(data.code == 0) {
					alert('创建成功！')
					window.location.href="<?= $Path;?>/admin/sched-router";
				}
				else {
					alert('创建失败！')
				}
			},
			error : function() {
				alert("创建失败，请检查网络后重试！");
			}
		})
	})

})
</script>
<?php $this->endBlock();  ?>
