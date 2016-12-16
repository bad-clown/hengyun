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
<div class="content" id="activity">
	<div class="breadcrumbBox">
		<ul class="breadcrumb">
			<li class="active">活动中心</li>
			<li>活动发布</li>
		</ul>
        <div class="btn btn-control" >
            <a href="<?= $Path;?>/admin/activity-web" id="j-back">返回</a>
        </div>
	</div>
	<div class="detail-label">
        <div class="form-group has-warning">
            <label class="col-sm-2 control-label"  >活动标题</label>
            <div class="col-sm-10">
                <input type="text" class="form-control a-title" placeholder="请输入活动标题"  name="title" />
            </div>
        </div>
        <div class="form-group has-warning">
            <label class="col-sm-2 control-label"  >活动时间</label>
            <span class="col-sm-5">
                <input type="text" class="form-control" placeholder="开始时间"  name="startTime" value="" id="startTime"/>
                <i class="activeTime" ></i>
            </span>
        </div>
        <span class="form-group endTime has-warning">
            <div class="col-sm-10" style=" width: 100%;">
                <input type="text" class="form-control" placeholder="结束时间"   name="endTime" value="" id="endTime"/>
                <i class="activeTime" ></i>
            </div>
        </span>
        <div class="form-group has-warning">
            <label class="col-sm-2 control-label" >活动内容</label>
            <div class="col-sm-10">
                <textarea class="textarea form-control" rows="10"  name="content"></textarea>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
    <div class="form-group text-right">
        <div class="col-sm-12">
            <a class="btn-default j-release" data-key="1" >发布</a>
            <a class="btn-gray j-release" data-key="0">保存草稿</a>
        </div>
    </div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/laydate/laydate.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
	$(function() {
        $(document).on('click', '.j-release', function () {
                var startTime = Date.parse($('input[name="startTime"]').val()) /1000 || '';
                var endTime   = Date.parse($('input[name="endTime"]').val()) /1000 || '';
                var status = $(this).data('key');
                var data = {
                    title : $('input[name="title"]').val(),
                    startTime : startTime,
                    endTime : endTime,
                    content : $('textarea[name="content"]').val(),
                    status : status
                }
                $.ajax({
                    type : "post",
                    url : "<?= $Path;?>/admin/activity/create",
                    data : data,
                    dataType : 'json',
                    success : function(data) {
                        if(data.code == '0') {
                            alert('成功！');
                            window.history.go(-1);
                        }
                        else {
                            alert('失败，请检查账单后重试！');
                        }
                    },
                    erroe : function() {
                        alert("失败，请检查网络后重试！");
                    }
                })
        })



        $(document).on('click', '#endTime', function() {
            $('#endTime').data('hasChange',true);
            if($(this).data('hasChange')) {
                laydate({
                    elem: '#endTime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    istoday: false,
                    choose: function(dates){
                        // $('#billTime').change()
                    }
                });
            }
        })

        $(document).on('click', '#startTime', function() {
            $('#startTime').data('hasChange',true);
            if($(this).data('hasChange')) {
                laydate({
                    elem: '#startTime',
                    format: 'YYYY-MM-DD hh:mm:ss',
                    istime: true,
                    istoday: false,
                    choose: function(dates){
                        // $('#billTime').change()
                    }
                });
            }
        })

    });

</script>
<?php $this->endBlock();  ?>
