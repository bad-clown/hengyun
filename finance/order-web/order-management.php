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
            <li class="active">账单管理</li>
            <li class="active">账单审批</li>
            <li class="active">订单审批</li>
        </ul>
    </div>
    <div class="detail-label">
        <table class="table table-striped" border="2" bordercolor="#d2d2d2" id="billList">
            <thead style="font-size: 14px;">
            <tr>
                <th>类型</th>
                <th style="width: 180px">未收款／待审批／待审批</th>
                <th style="width: 180px">收款中／待支付／待修改</th>
                <th style="width: 180px">已收款／已支付／已修改</th>
                <th>总计</th>
                <th>不同意</th>
            </tr>
            </thead>
            <tbody class="text-center" style="font-size: 16px;">

            </tbody>
        </table>
    </div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="/assets/8c065db/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/static/js/search.js"></script>
<script type="text/javascript">
    $(function(){
        function getData() {
            $.ajax({
                type: "get",
                url: "<?= $Path;?>/finance/order/order-management",
                dataType: "json",
                success: function (d) {
                    var data = d.data;
                    var urlTitle = "<?= $Path;?>/finance/";
                    var $approve = $('#billList').find('tbody');
                    $approve.empty();
                    $.each(data, function (i, o) {
                        var title = (i == 'shipper') ? '货主账单' : (i == 'driver' ? '司机账单' : '订单修改');
                        var urlName = (i == 'shipper') ? 'bill-shipper-web/list?actStatus=' : (i == 'driver' ? 'bill-driver-web/list?actStatus=' : 'order-web/apply-update?approve=');
                        var url = urlTitle + urlName;
                        var sum = o.key * 1 + o.key1 * 1 + o.key2 * 1;
                        var Html = '<tr valign="middle"><td><div class="form-group">' + title + '</div></td><td><div class="form-group"><label><a href="' + url + '0" class="j-price-list" >' + o.key + '单</a></label></div></td><td><div class="form-group"><label><a href="' + url + '1" class="j-price-list" >' + o.key1 + '单</a></label></div></td><td><div class="form-group"><label><a href="' + url + '2" class="j-price-list" >' + o.key2 + '单</a></label></div></td><td><div class="form-group"><label><a href="' + url + '"  >' + sum + '单</a></label></div></td><td><div class="form-group"><label><a href="' + url + '-1"  >' + (o.key3 || 0) + '单</a></label></div></td></tr>';
                        $approve.append(Html);
                    })
                }
            })
        }
        getData()

        setInterval(function () {
            getData()
        }, 30000);

    })

</script>
<?php $this->endBlock();  ?>
