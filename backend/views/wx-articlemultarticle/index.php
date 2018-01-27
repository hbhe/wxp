<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MArticleMultArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '一条多图文所包含的单图文列表';
$this->params['breadcrumbs'][] = ['label' => '多图文列表', 'url' => ['articlemult/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-article-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增一条单图文', ['create', 'article_mult_id'=>$articleMult->article_mult_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            'article_mult_id',
            'article_id',
            [
                'label' => '标题',
                'value'=>function ($model, $key, $index, $column) { return $model->article->title; },
            ],
            [
                'label' => '内容',
                'value'=>function ($model, $key, $index, $column) { return $model->article->content; },
            ],
            [
                'label' => '',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
                    $photo = $model->article->photo;
                    return Html::a(Html::img(Url::to($photo->getPicUrl()), ['width'=>'75']), $photo->getPicUrl());
                },
            ],

            'sort_order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
