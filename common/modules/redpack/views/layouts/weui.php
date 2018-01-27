<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

\common\modules\redpack\RedpackWeuiJSAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta name="keywords" content="首页关键字" />
    <meta name="description" content="首页描述" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <title><?php echo Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php echo Html::csrfMetaTags() ?>
</head>

<body style="<?= empty($this->params['bodyStyle']) ? 'background: #ebebeb none repeat scroll 0 0;' : $this->params['bodyStyle']; ?>">
<?php $this->beginBody() ?>

<?php echo $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
