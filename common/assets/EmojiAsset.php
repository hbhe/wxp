<?php
namespace common\assets;

use yii\web\AssetBundle;

class EmojiAsset extends AssetBundle {
    public $sourcePath = '@common/assets/php-emoji';
    public $css = [
        'emoji.css',
    ];
}

