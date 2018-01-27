<?php
namespace common\modules\wall\assets;

use yii\web\AssetBundle;
use yii;

class SignAsset extends AssetBundle {
//    public $sourcePath = __DIR__ . '/wall';
//    public $sourcePath = __DIR__ . '/wall';
    //public $sourcePath = dirname(__DIR__) . '/wall');
    public $sourcePath = '@common/modules/wall/assets/wall';
//    public $basePath = '@webroot';
 //   public $baseUrl = '@web';
    public $css = [
         'css/style-qiandao.css',
    ];


    public $js = [
 //       'jquery.min.js',
    ];
   
}