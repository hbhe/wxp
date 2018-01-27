<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '单图文列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'columns' => [
            
            'id',
            
            'title',
            
            'author',
            
            'digest',
            
            [
                'attribute' => 'content',
                'format'=>'html', 
                'headerOptions' => ['width' => '200'],                
            ],
            
            'content_source_url:url',
            
            'show_cover_pic',

            [
                'attribute' => 'photo_id',
                'format' => ['image', ['width'=>'32', 'height'=>'32']],
                'value'=>function ($model, $key, $index, $column) {
                    return \Yii::$app->imagemanager->getImagePath($model->photo_id);
                },
            ],
                       
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
