<?php

namespace common\wosotech;

use yii\web\AssetBundle;

class WeuiJSAsset extends AssetBundle
{
    public $sourcePath = '@common/wosotech/weui.js';

    public $js = [
        'dist/weui.min.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    public $depends = [
        '\common\wosotech\WeuiAsset',
        '\yii\web\JqueryAsset',
    ];
}
