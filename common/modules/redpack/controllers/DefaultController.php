<?php

namespace common\modules\redpack\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
//use common\models\WxSceneidMobile;
use common\models\WxUser;
//use common\models\Mobile;
use common\models\Y;
use common\models\WxGh;
//use common\models\WxUserMobile;
use common\modules\redpack\models\RedpackLog;
use common\modules\redpack\models\RedpackLogSearch;
//use common\modules\redpack\models\IncomeSearch;
//use common\modules\redpack\models\SpendingSearch;
use common\models\WxMember;

class DefaultController extends \common\wosotech\base\Controller {

    public $layout = 'weui';
    
    public $enableCsrfValidation = true;

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/index&gh_sid=jmdx
    public function actionIndex() {
        // just for xgdx now
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        Yii::$app->request->queryParams = \yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [
            'RedpackLogSearch' => ['openid' => $openid, 'is_revenue' => 0]
        ]);    

        $model = WxMember::find()->where(['openid' => $openid])->one();
        $searchModel = new RedpackLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/bindphone&gh_sid=jmdx
    public function actionBindphone() {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxUser::find()->where(['openid' => $openid])->one();                
        return $this->render('bindphone', [
            'model' => $model,
        ]);            
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/bindphonex&gh_sid=jmdx
    public function actionBindphonex() {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxUser::find()->where(['openid' => $openid])->one();                
        return $this->render('bindphonex', [
            'model' => $model,
        ]);            
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/profile&gh_sid=jmdx
    public function actionProfile() {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $user = WxUser::find()->where(['openid' => $openid])->one();                
        $model = WxMember::find()->where(['openid' => $openid])->one();
        if (empty($model->tel)) {         
            return $this->redirect(['bindphonex']); // bindphone
        } 
        return $this->render('profile', [
            'model' => $model,
        ]);        
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/summary&gh_sid=jmdx
    public function actionSummary() {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxMember::find()->where(['openid' => $openid])->one();
        return $this->render('summary', [
            'model' => $model,
        ]);        
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/list&gh_sid=jmdx
    public function actionList($is_revenue = 1) {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        Yii::$app->request->queryParams = yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [ 
            'RedpackLogSearch' => ['openid' => $openid, 'is_revenue' => $is_revenue]
        ]);    

        $model = WxMember::find()->where(['openid' => $openid])->one();
        $searchModel = new RedpackLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('list', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        
    }

    //http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/about&gh_sid=jmdx
    public function actionAbout() {
        $gh = \common\wosotech\Util::getSessionGh();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxMember::find()->where(['openid' => $openid])->one();
        $params = \common\modules\redpack\Module::getParams($gh->gh_id);        
        return $this->render('about', [
            'model' => $model,
            'gh' => $gh,
            'params' => $params,
        ]);        
    }
    
    public function actionHow() {
        $gh = \common\wosotech\Util::getSessionGh();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxMember::find()->where(['openid' => $openid])->one();
        $params = \common\modules\redpack\Module::getParams($gh->gh_id);        
        return $this->render('how', [
            'model' => $model,
            'gh' => $gh,
            'params' => $params,
        ]);        
    }

    public function actionWhy() {
        $gh = \common\wosotech\Util::getSessionGh();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = WxMember::find()->where(['openid' => $openid])->one();
        $params = \common\modules\redpack\Module::getParams($gh->gh_id);        
        return $this->render('why', [
            'model' => $model,
            'gh' => $gh,
            'params' => $params,
        ]);        
    }

    // http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/default/input-demo
    public function actionInputDemo() {
        return $this->render('input-demo', [
        ]);
    }
}
