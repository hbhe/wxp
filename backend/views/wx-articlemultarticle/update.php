<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MArticleMultArticle */

$this->title = '编辑' . ' ' . $model->article->title;
$this->params['breadcrumbs'][] = ['label' => '多图文列表', 'url' => ['articlemult/index']];
$this->params['breadcrumbs'][] = ['label' => '一条多图文所包含的单图文列表', 'url' => ['index', 'article_mult_id'=>$model->articleMult->article_mult_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-article-update">

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
