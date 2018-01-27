<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
common\assets\EmojiAsset::register($this);
require_once Yii::getAlias('@common/3rdlibs/php-emoji/emoji.php');

/* @var $this yii\web\View */
/* @var $searchModel common\modules\wall\models\WxWallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = '微信墙消息';
?>
<div class="wx-wall-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <p style="text-align:right;">
        <?= Html::a('去审核', ['index','gh_id'=>$gh_id], ['class' => 'btn btn-success']) ?>
    </p>
    <div style="clear:both"></div>
<?php //Pjax::begin(); ?>    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
      	'filterModel' => $searchModel,
        'columns' => [
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
	        
			 [
			'label' => "上墙",
			'format'=>'html',
			'value'=>function ($model) {
				return $model->is_wall==1 ? '已上墙' : '未上墙'	;				
				},
			],
            // 'is_from_openid',
            // 'created_at',
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn',
				'template' => '{delete}'
			],
            
        ],
    ]); ?>
<?php //Pjax::end(); ?></div>

