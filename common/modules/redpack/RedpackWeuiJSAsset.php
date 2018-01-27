<?php

namespace common\modules\redpack;

use \yii\web\AssetBundle;

class RedpackWeuiJSAsset extends AssetBundle
{    
    public $sourcePath = '@common/modules/redpack/assets';

    public $css = [
        //'css/example.css',
    ];

    public $js = [
        //'js/app.js',
    ];

    public $depends = [
//        'yii\web\JqueryAsset',
        'common\wosotech\WeuiJSAsset',
    ];
}
