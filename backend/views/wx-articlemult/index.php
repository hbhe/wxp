<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MArticleMultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '多图文列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建多图文', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'article_mult_id',
            'title',
            'create_time',

            [
                'label' => '包含的单图文数量',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
                    foreach($model->articleMultArticles as $articleMultArticle) {
                        //$arr[] = $articleMultArticle->article->title;
                        $photo = $articleMultArticle->article->photo;
                        $str = Html::a(Html::img(Url::to($photo->getPicUrl()), ['width'=>'75']), $photo->getPicUrl());
                        $arr[] = $str;
                    }
                    //$str = empty($arr) ? '' : ' ['.implode(', ', $arr).']';
                    //return count($model->articleMultArticles) . $str .' '.Html::a('<span>详情</span>', ['articlemultarticle/index', 'article_mult_id'=>$model->article_mult_id], [
                    $str = empty($arr) ? '' : implode(', ', $arr);
                    return $str .' '.Html::a('<span>详情</span>', ['articlemultarticle/index', 'article_mult_id'=>$model->article_mult_id], [
                    ]);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
