<?php

namespace common\wosotech;

use yii\web\AssetBundle;

class WeuiAsset extends AssetBundle
{
    public $sourcePath = '@common/wosotech/weui';

    public $css = [
        'dist/style/weui.min.css',
    ];
}
