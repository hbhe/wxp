<?php
namespace common\modules\wall\assets;

use yii\web\AssetBundle;

class WallAsset extends AssetBundle {
//    public $sourcePath = __DIR__ . '/wall';
//    public $sourcePath = __DIR__ . '/wall';
    //public $sourcePath = dirname(__DIR__) . '/wall');
    public $sourcePath = '@common/modules/wall/assets/wall';
    public $css = [
        'css/style-index.css',
    ];

    public $js = [
//        'jquery.min.js',
    ];
}