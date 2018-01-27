<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\base\Controller;
use Yii;

/**
 * Class CheckGhidBehavior
 * @package common\behaviors
 */
class CheckGhidBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    public function beforeAction()
    {
        if (empty($gh_id = \common\wosotech\Util::getSessionGhid())) {
            if (Yii::$app->user->can(\common\models\User::ROLE_ADMINISTRATOR)) {
                $this->owner->redirect(['gh/index']);
            }
        }
    }
}
