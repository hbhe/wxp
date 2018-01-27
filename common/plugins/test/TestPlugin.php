<?php
namespace common\plugins\test;

use lo\plugins\BasePlugin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Plugin Name: Test plugin
 * Plugin URI:
 * Version: 1.1
 * Description: Small test plugin
 * Author: hbhe
 * Author URI: https://github.com/hbhe
 */
 
class TestPlugin extends BasePlugin
{
    public static $appId = self::APP_FRONTEND;

    public static $config = [
        'term' => 'Hello, world!',
    ];

    public static function events()
    {
        return [
            Response::class => [
                Response::EVENT_AFTER_PREPARE => ['foo', self::$config]
            ],
        ];
    }

    public static function foo($event)
    {
        $term = ($event->data['term']) ? $event->data['term'] : self::$config['term'];
        $event->sender->content =  str_replace($term,"<h1>$term</h1>", $event->sender->content);
        return true;
    }
}