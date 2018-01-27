<?php

namespace common\modules\bargain;

/**
 * bargain module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\bargain\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function getParams($gh_id) {
        $params = require(__DIR__ . '/params-default.php');
        if (file_exists(__DIR__ . "/params-{$gh_id}.php")) {
            $params = ArrayHelper::merge(
                $params,
                require(__DIR__ . "/params-{$gh_id}.php")
            );
        }
        return $params;
    }

}
