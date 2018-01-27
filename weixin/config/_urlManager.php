<?php
return [
    'class'=>'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    //'enableStrictParsing' => true,
    //'suffix' => '.html',
    'rules' => [
        ['pattern' => 'site/index/<appid>', 'route'=>'site/index'],
    ],

];
