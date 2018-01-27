<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MArticleMultArticle */

$this->title = $model->article_mult_article_id;
$this->params['breadcrumbs'][] = ['label' => 'Marticle Mult Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->article_mult_article_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->article_mult_article_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'gh_id',
            'article_mult_article_id',
            'article_mult_id',
            'article_id',
            'sort_order',
        ],
    ]) ?>

</div>
