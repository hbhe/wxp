<?php
namespace wechat\controllers;

use wechat\models\Wechat;
use yii\web\Controller;

/*
 * http://wechat.mysite.com/index.php?r=site&gh_id=gh_6b9b67032eb6 
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $wechat = new Wechat();
        return $wechat->run();
    }    
}
