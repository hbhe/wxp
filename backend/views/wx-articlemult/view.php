<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MArticleMult */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Marticle Mults', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->article_mult_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->article_mult_id], [
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
            'article_mult_id',
            'title',
            'create_time',
        ],
    ]) ?>

</div>
