<?php


namespace console\controllers;

use common\models\WxGh;
use common\models\WxMemberfans;
use Yii;
use yii\console\Controller;

class DayController extends Controller
{
    public function actionIndex()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        if (!ini_set('memory_limit', '-1')) {
            yii::error("ini_set(memory_limit) error");
        }
        $time = microtime(true);

        $beforeYesterday = date("Y-m-d", strtotime("-2 days"));
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day"));

        yii::warning("###########" . __CLASS__ . " BEGIN");

        try {
            WxMemberfans::statScore(WxGh::WXGH_DEMO, $yesterday);
        } catch (\Exception $exception) {
            yii::error(__LINE__ . $exception->getMessage());
        }

        yii::warning("###########" . __CLASS__ . " END, (time: " . sprintf('%.3f', microtime(true) - $time) . "s)");
    }

}


