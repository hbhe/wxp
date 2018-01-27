<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use \kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxMembersearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '绑定会员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-xgdx-member-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('创建会员', ['create'], ['class' => 'btn btn-success']) ?>
         <?php echo \kartik\daterange\DateRangePicker::widget([
            'model' => $searchModel,
            'attribute' => 'created_at',
            'presetDropdown' => true,
            'options' => [
                'id' => 'created_at',
                //'value' => "2017-07-17 - 2017-07-17",
            ],
            'pluginOptions' => [
                'format' => 'YYYY-MM-DD',
                'separator' => ' TO ',
                'opens'=>'left',
            ] ,
            'pluginEvents' => [
                "apply.daterangepicker" => "function() { $('.grid-view').yiiGridView('applyFilter'); }",
            ],
        ]); ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
            
        'filterSelector' => '#created_at',
        
        'responsive' => true,
        'resizableColumns' => true,
        'persistResize' => true,
        'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
        'layout' => "{toolbar}\n{summary}\n{items}\n{pager}",
        'toolbar' =>  [
            [
                'content'=> implode(' ', [
                        //Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                        //Html::a('<i class="glyphicon glyphicon-list"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-success', 'title'=>'创建']),
                        //$searchModel->group_by ? Html::a('<i class="glyphicon glyphicon-list"> 明细 </i>', ['index'], ['data-pjax'=>0, 'class' => 'btn btn-success']) : Html::a('<i class="glyphicon glyphicon-stats"> 累计 </i>', ['index', 'group_by' => 'area'], ['data-pjax'=>0, 'class' => 'btn btn-success']),
                        ])
            ], 
                '{export}',
                '{toggleData}',
                ], 
            
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            // 'gh_id',
            //'id',

			/*[
				'label' => '头像',
				'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->wxUser->headimgurl))
                        return '';
                    $headimgurl = Html::img(\common\wosotech\Util::getWxUserHeadimgurl($model->wxUser->headimgurl, 46), ['class' => "img-responsive img-circle"]);
                    return $headimgurl;
				},
			],*/
            [
                'label'=>'微信昵称',
                'value'=>'wxUser.nickname',
                'headerOptions' => array('style'=>'width:80px;')
            ],

            'tel',
            [
                'label' => "粉丝数",
                'format'=>'html',
                'value'=>function ($model) {
                    return $model->vermicelli == 0 ? 0 : Html::a($model->vermicelli, ['/wx-xg-memberfans/index','scene_str'=>$model->wxQrlimit['scene_str']], ['title' => '', 'class' => 'btn btn-xs btn-primary']);
                    //return $model->wxQrlimit->scene_str;
                },
			],

            [
                'label' => "实际粉丝数",
                'format'=>'html',
                'value'=>function ($model) {
                    return count($model->fanMembers);
                },
                'visible' => false,
			],
            
            'openid',
            ['label'=>'推荐二维码参数','value'=>'wxQrlimit.scene_str'],
            [
                'label' => '是否孝感电信',
                'value'=>function ($model, $key, $index, $column) {
                        return \common\models\WxMember::is_xgdxtel($model->tel) ? "是" : "否";
                    },
                "visible" => \common\models\WxMember::is_xiaogan(),
                'headerOptions' => array('style'=>'width:80px;'),
            ],
            //'created_at',
            [
                'attribute' => 'created_at',
                'filter' => false,
            ],
            //'is_binding:boolean:已关注',
            [
                'attribute' =>'is_binding',
                'label' => '已关注',
                'value' => function ($model){
                    return empty($model->is_binding) ? '否': '是';
                },
                'filter' => Html::activeDropDownList($searchModel,'is_binding',['否','是'],['prompt' => '全部','class' => 'form-control']),
                //'headerOptions' => array('style'=>'width:100px;'),
            ],
            // 'member_type',
            // 'member_grade',
            // 'created_at',
            // 'updated_at',

            //'redpack_status',

          /* [
                'attribute' => 'redpack_revenue_amount',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->redpack_revenue_amount / 100 ; },
           ],

           [
                'attribute' => 'redpack_consume_amount',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->redpack_consume_amount / 100 ; },
           ],

            [
                'attribute' => 'redpack_balance',
                'format' => 'html',
                'value'=>function ($model) {
                    return Yii::$app->formatter->asCurrency($model->redpack_balance / 100) . ' ' . Html::a('明细', ['redpack/admin/index', 'openid' => $model->openid], ['title' => '', 'class' => 'btn btn-xs btn-primary']);
                },
			],*/

            ['class' => 'yii\grid\ActionColumn'],
        ]
    ]); ?>

</div>

