<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxGhSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公众号';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-gh-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('接入新的公众号', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn'],
            //'sid',
            'clientName',
            'title',
            'gh_id',
            'appId',
            'appSecret',
            [
                'attribute' => 'is_service',
                'value' => function ($model, $key, $index, $column) { return \common\wosotech\Util::getYesNoOptionName($model->is_service); },
            ],

            //'token',
            // 'accessToken',
            // 'accessToken_expiresIn',
            //'encodingAESKey',
            //  'encodingMode',
            // 'partnerId',
            // 'partnerKey',
            // 'paySignKey',
            // 'created_at',
            // 'updated_at',
			[
				'label' => "管理",
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) {
				    return Html::a('设为管理公众号', ['gh/set-session-gh-id', 'id'=>$model->id], ['title' => '', 'class' => \common\wosotech\Util::getSessionGhid() == $model->gh_id ? 'btn btn-xs btn-success' : 'btn btn-xs btn-primary']);
				},
                'headerOptions' => array('style'=>'width:70px;'),           
			],


            
        ],
    ]); ?>

</div>

<?php
/*
<?php
$js = <<<EOD
insertRow();
EOD;

$this->registerJs($js, yii\web\View::POS_READY);
?>


<script type="text/javascript">
function insertRow() {
	var args = {
		'classname': '\\common\\models\\SjPolicy',
		'funcname': 'insertAjax',
		'params': {
			'generate_policy_sid' : '55555',
            'openid' : 'openid-55555',
			'imei': '555555',
            'clerk_id': 'A001555',
                ....

		}
	};

	$.ajax({
		url: "http://192.168.252.1/wxp/mobile/web/index.php?r=sj-policy/ajax-broker",
		type: "GET",
		cache: false,
		dataType: "json",
		data: "args=" + JSON.stringify(args),
		success: function (resp) {
			if (resp['code'] != 0) {
				alert("handle error");
				return;
			}
            alert('ok');
		},
		error: function () {
			alert('exception');
		}
	});
}

</script>
*/