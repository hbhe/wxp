<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

//\frontend\assets\MktWapCommonAsset::register($this);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>屏安宝</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="sj-policy/css/style-index.css">
<body>
    <div class="insurance-main" flex="main:justify dir:top">
        <div flex="dir:top cross:center " class="insurance-main__a">
            <div class="insurance-main__logo" >
                &nbsp;
            </div>
            <div style="margin-top: 1.5rem;">
                <a href="<?php echo Url::to(['sj-policy/create']); ?>" class="insurance-main__mybuy"></a>
            </div>
            <div>
                <a href="<?php echo Url::to(['sj-policy/search']); ?>" class="insurance-main__search"></a>
            </div>
        </div>
        <div style="height: 1.5rem;display: flex ;justify-content: flex-end;margin-right: 10px" >
            <a href="#" style="display: flex ;font-size: .7rem;
            height: 100%;margin-left: 1rem;
             justify-content: flex-end;">
                <span style="color: #fff; margin-right: .25rem;margin-top: .1rem">服务条款</span>
                <img src="sj-policy/img/wenhao.png" alt="" width="20" height="22" />
            </a>
        </div>
    </div>
</body>
</html>