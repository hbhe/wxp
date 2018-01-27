<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\wall\models\WxWallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = "签到列表";
?>

<!-- <meta http-equiv='refresh' id='meta_wall' content='3'/> -->
<?php 
    /* if(Yii::$app->keyStorage->get("{$gh_id}.common.module.wall.status", '') == 'enabled'){
        echo "<meta http-equiv='refresh' id='meta_wall' content='3'>";
    }else{
        echo '';
    } */
?>

<div class="wx-wall-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

 <!--  <div>
        <a href="<?php echo Url::to(['display-qr','gh_id'=>$gh_id]) ?>" target="_blank" class="btn btn-success">查看签到二维码</a>
    </div>
-->

<?=Html::beginForm();?>
<?php //Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],

            [
            'label' => '头像',
            'format'=>[
                 'image',
                 [
                    'width'=>'46',
                    'height'=>'46'
                ]
            ],
            'value'=>'wxUser.headimgurl'
            ],
            ['label'=>'用户名','value'=>'wxUser.nickname'],
			 [
			'label' => "是否显示",
			'format'=>'html',
			'value'=>function ($model) {
				return $model->is_display==1 ? '已显示' : '未显示'	;				
				},
			], 
            // 'is_from_openid',
            // 'created_at',
            // 'updated_at',     
        ],
    ]); ?>
<?php //Pjax::end(); ?>

<?=Html::submitButton('批量删除', ['class' => 'btn btn-info',]);?>
<?= Html::endForm();?> 

</div>

