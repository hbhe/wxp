<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,

    'rules' => [
        ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/article', 'only' => ['index', 'view', 'options']],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/user', 'only' => ['index', 'view', 'options']],

        ['class' => 'yii\rest\UrlRule', 'controller' => 'gh'],

        ['class' => 'yii\rest\UrlRule',
            'controller' => ['v1/bargain-topic'],
            'pluralize' => false,
            'extraPatterns' => [
                'GET search/{id}' => 'search',
            ],
            'only' => ['index', 'view', 'options']
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['bargain-item'],
            'except' => ['create'],
            'pluralize' => false,
        ],

    ]

];
