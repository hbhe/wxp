<?php

namespace common\modules\redpack;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\WxGh;
use common\models\WxUser;
use common\models\WxtppConfig;
use common\models\Y;

class Module extends \yii\base\Module
{
    //public $layout = 'redpack';
    
    public $gh;
    public $subjectUser;
    public $objectUser;
    public $jsapiSignatureArray;
    
    public function init()
    {
        if (\yii::$app instanceof \yii\console\Application) {
            $class = get_class($this);
            if (($pos = strrpos($class, '\\')) !== false) {
                $this->controllerNamespace = substr($class, 0, $pos) . '\\console';
            }        
        } else {
            parent::init();
        }
        
        $this->gh = \common\wosotech\Util::getSessionGh();
        if (!$this->gh) {
            return true;
        }
        
        \Yii::configure($this, [
            'params' => self::getParams($this->gh->gh_id)
        ]);        
    }
    
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
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
