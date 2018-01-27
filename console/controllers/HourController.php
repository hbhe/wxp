<?php

/*
php D:\htdocs\mkt\console\yii hour
/usr/bin/php /www/web/mkt/console/yii hour
0 * * * * /usr/bin/php /www/web/mkt/console/yii hour
*/

namespace console\controllers;

use Yii;
use yii\console\Controller;

class HourController extends Controller
{
    public function actionIndex()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        if (!ini_set('memory_limit', '-1'))
            yii::error("ini_set(memory_limit) error");
        $time = microtime(true);

        yii::warning("###########" . __CLASS__ . " BEGIN");

        if (
            date('H') == 10 ||
            date('H') == 11 ||
            date('H') == 16 ||
            date('H') == 17 ||
            date('H') == 20
        ) {

        }
        yii::warning("###########" . __CLASS__ . " END, (time: " . sprintf('%.3f', microtime(true) - $time) . "s)");
    }

}

/*
		
*/		

