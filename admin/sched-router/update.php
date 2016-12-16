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
            <li class="active">更新路线</li>
        </ul>
        <a href="javascript:;" id="j-save-control" class="btn save-control">保存</a>
        <a href="<?= $Path;?>/admin/sched-router" class="btn back-control">返回</a>
    </div>

    <div class="sched-router">
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
                <input class="form-control" name="phone" id="phone" value="" type="text" readonly="readonly">
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
	var oData = {},phoneData = [];
	$.ajax({
    	type : 'get',
    	url : '<?= $Path;?>/admin/sched-router/detail?id=<?= $_id;?>',
    	dataType : 'json',
    	success : function(data) {
    		if(data.code == 0) {
    			var data = data.data;
    			oData._id = data._id;
    			oData.name = data.name;
    			oData.phone = data.phone;
    			oData.provinceFrom = data.provinceFrom;
    			oData.provinceTo = data.provinceTo;
    			oData.userId = data.userId;
    			$('#phone').val(oData.phone);

    			getData()
    		}
    	}
    })

	function getData() {
		$.ajax({
	    	type : 'get',
	    	url : '<?= $Path;?>/admin/city/province',
	    	dataType : 'json',
	    	success : function(data) {
	    		$.each(data, function(i, o) {
	                $('#provinceFrom').get(0).options.add(new Option(o.name,o.code));
	                $('#provinceTo').get(0).options.add(new Option(o.name,o.code));
	            })
	            $('#provinceFrom').val(oData.provinceFrom).triggerHandler("change");
	            $('#provinceTo').val(oData.provinceTo).triggerHandler("change");
	    	}
	    })

	    $.ajax({
	        type : 'get',
	        url : '<?= $Path;?>/admin/sched-router/sched-list',
	        dataType : 'json',
	        success : function(data) {
	            $.each(data, function(i, o) {
	                var name = o.name || '暂无';
	                var phone = o.phone || '暂无';
	                $('#name').get(0).options.add(new Option(name,o._id));
	                phoneData.push({
	                    _id : o._id,
	                    phone : phone
	                })
	            })
	            $('#name').val(oData.userId).triggerHandler("change");
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
	}

	$('#j-save-control').on('click', function() {
		var data = {
			provinceFrom : $('#provinceFrom').val(),
			provinceTo : $('#provinceTo').val(),
			userId : $('#name').val(),
		}


		$.ajax({
			type : 'post',
			url : '<?= $Path;?>/admin/sched-router/update?id=<?= $_id;?>',
			data : data,
			success : function(data) {
				if(data.code == 0) {
					alert('保存成功！')
				}
				else {
					alert('保存失败！')
				}
			},
			error : function() {
				alert("保存失败，请检查网络后重试！");
			}
		})
	})
})
</script>
<?php $this->endBlock();  ?>
