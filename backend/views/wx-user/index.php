<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '微信粉丝';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //if (YII_DEBUG) echo Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
			[
				'label' => 'ID',
                'attribute' => 'id',
                'filter' => false,
                'headerOptions' => array('style'=>'width:40px;'),           

			],

			[
				'label' => '头像',
				'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->headimgurl))
                        return '';
                    $headimgurl = Html::img(\common\wosotech\Util::getWxUserHeadimgurl($model->headimgurl, 46), ['style'=>'width:46px;', 'class' => "img-responsive img-circle"]);
                    return $headimgurl;
				},
			],

			[
//				'label' => '微信昵称',
                'attribute' => 'nickname',
				'value'=>function ($model, $key, $index, $column) {  
						return empty($model->nickname) ? '' : $model->nickname;
					},
                'headerOptions' => array('style'=>'width:80px;'),           
			],

            'city',
            'openid',
/*
            //'status',
            [
                'label' => '状态',
                'attribute' => 'status',
                'value'=>function ($model, $key, $index, $column) { 
						return common\models\WxUser::getStatusOptions($model->status); 
					},
                'filter' => common\models\WxUser::getStatusOptions(),
                'headerOptions' => array('style'=>'width:80px;'),           
            ],

            //'industry_id',
			[
				'label' => '行业',
                'attribute' => 'industry_id',
				'value'=>function ($model, $key, $index, $column) {
                    if (empty($model->industry)) 
                        return '';
                    return $model->industry->getPaths('-');
				},
			],            

            //'area_id',
			[
				'label' => '区域',
				'value'=>function ($model, $key, $index, $column) {
                    if (empty($model->area)) 
                        return '';
                    return $model->area->getPaths('-');
				},
			],            

			[
				'label' => "金币",
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) {
                    if (empty($model->goldSum)) return '0';
				    return Html::a($model->goldSum, ['gold/index', 'user_id'=>$model->id]);
				},
                'headerOptions' => array('style'=>'width:40px;'),           
			],

			[
                'attribute' => 'mobile',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->mobile))
                        return '';
                    return $model->mobile;
				},
			],

			[
                'attribute' => 'email',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->email))
                        return '';
                    return $model->email;
				},
                'visible' => false
			],

			[
                'attribute' => 'qq',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->qq))
                        return '';
                    return $model->qq;
				},
                'visible' => false
			],

			[
                'attribute' => 'weixin_number',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->weixin_number))
                        return '';
                    return $model->weixin_number;
				},
                'visible' => false
			],
*/
            // 'status',
            // 'create_time',
            // 'update_time',
            // 'openid',
            // 'unionid',
            // 'subscribe',
            // 'subscribe_time:datetime',
            // 'nickname',
            // 'sex',
            // 'city',
            // 'country',
            // 'province',
            // 'headimgurl:url',
/*
			[
				'label' => "post",
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) {
                    $str = '';
                    if (!empty($model->postCount)) {
				        $str .= Html::a('Post:' . $model->postCount, ['post/index', 'user_id'=>$model->id], ['title' => 'Post', 'class' => 'btn btn-xs btn-primary']);
                    }
                    return $str;
				},
                'headerOptions' => array('style'=>'width:70px;'),           
			],

*/            
            [   'class' => 'yii\grid\ActionColumn',
                'headerOptions' => array('style'=>'width:80px;'),           
            ],

        ],
    ]); ?>

</div>
