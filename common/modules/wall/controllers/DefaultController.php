<?php

namespace common\modules\wall\controllers;

/**
 * Default controller for the `wall` module
 */
class DefaultController extends \common\wosotech\base\Controller
{
    /* 微信互动消息上墙
     * http://127.0.0.1/wxp/mobile/web/index.php?r=wall/default/index&gh_id=gh_6b9b67032eb6
     */
    public function actionIndex()
    {
        $this->layout = false;
        $gh = \common\wosotech\Util::getSessionGh();
        return $this->render('index', [
            'gh' => $gh,
        ]);
    }

    /*
     * 微信签到后个人图像上墙
     * http://127.0.0.1/wxp/mobile/web/index.php?r=wall/default/sign&gh_id=gh_301295a80bd5
     */
    public function actionSign()
    {
        $this->layout = false;
        $gh = \common\wosotech\Util::getSessionGh();
        return $this->render('sign', [
            'gh' => $gh,
        ]);
    }

    /*
     * 微信摇一摇抽奖
     */
    public function actionShake()
    {
        $this->layout = false;
        $gh = \common\wosotech\Util::getSessionGh();
        return $this->render('shake', [
            'gh' => $gh,
        ]);
    }

}
