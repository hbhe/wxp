<?php

common\assets\EmojiAsset::register($this);
require_once Yii::getAlias('@common/3rdlibs/php-emoji/emoji.php');

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\wall\models\WxWallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '微信墙消息';
?>

<!-- <meta http-equiv='refresh' id='meta_wall' content='3'/> -->
<?php 
     if(Yii::$app->keyStorage->get("{$gh_id}.common.module.wall.refresh", '') == 'enabled'){
        echo "<meta http-equiv='refresh' id='meta_wall' content='3'>";
    }else{
        echo '';
    } 
?>

<div class="wx-wall-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div style="float:left;">
        <?php
        $gh_id = \common\wosotech\Util::getSessionGhid();
        if(Yii::$app->keyStorage->get("{$gh_id}.common.module.wall.status", "") == 'enabled' ){
             if(Yii::$app->keyStorage->get("{$gh_id}.common.module.wall.refresh", "") == 'enabled'){
            		echo  Html::a('停止刷新',['/wall/admin/bled','gh_id'=>$gh_id], ['class' => 'btn btn-success']);     
			}else{
					echo  Html::a('开始刷新',['/wall/admin/bled','gh_id'=>$gh_id], ['class' => 'btn btn-success','id' => 'btn_ena']);
			}
    	}
         ?>
        
        
        
    </div>
    <div style="float:right;">
        <?= Html::a('查看已上墙的消息', ['wall','gh_id'=>$gh_id], ['class' => 'btn btn-success']) ?>
    </div>
    <div style="clear:both"></div>
  
<?php //Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
	    'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
            [
            'label' => '头像',
            'format'=>[
                 'image',
                 [
                    'width'=>'46',
                    'height'=>'46'
                ]
            ],
            'value'=>'wxuser.headimgurl'
            ],
            [
                'label' => "微信昵称",
                'format'=>'html',
                'value'=>function ($model) {
                    return emoji_unified_to_html(emoji_softbank_to_unified($model->wxuser->nickname));
                },
			],

            [
                'attribute' => 'content',
                'format'=>'html',
                'value'=>function ($model) {
                    return \common\wosotech\Util::qqface_convert_html(emoji_unified_to_html(emoji_softbank_to_unified($model->content)));
                },
			],

            //'wxuser.nickname',
            //'content',
            [
			'label' => "审核",
			'format'=>'html',
			'value'=>function ($model) {
				if($model->is_checked==0){
					return Html::a('通过', ['/wall/admin/update', 'id'=>$model->id,'gh_id'=>$model->gh_id], ['title' => '', 'class' => 'btn btn-xs btn-primary']);
				}else{
					return Html::a('已通过', ['/wall/admin/update', 'id'=>$model->id,'gh_id'=>$model->gh_id], ['title' => '', 'class' => 'btn btn-xs btn-primary']);
				}
			},
			],
			[
            'label' => '状态',
            'format'=>'html',
            'value' => function($model){
                if ( $model->is_wall==0 ) {
                    if ( $model->is_checked==0 ) {
                        return '未通过(未上墙)';
                    } else {
                        return '已通过(未上墙)';
                    }
                }
            }
            ],
			/* [
			'label' => "上墙",
			'format'=>'html',
			'value'=>function ($model) {
				return $model->is_wall==1 ? '已上墙' : '未上墙'	;				
				},
			], */
            // 'is_from_openid',
            // 'created_at',
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn',
				'template' => '{delete}'
			],
            
        ],
    ]); ?>
<p style="text-align: left">
    <?= Html::a('批量审核', "javascript:void(0);", ['class' => 'btn btn-success gridview']) ?>
</p>
    <?php //Pjax::end(); ?></div>

<?php
$this->registerJs('
$(".gridview").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
    $.ajax({
        url:"?r=wall/admin/update",
        type: "GET",
        cache: false,
        dataType: "json",
        data: "keys=" + keys,
        success: function (resp) {
            alert(resp);
            }
    });
    
});
');
?>
