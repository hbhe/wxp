<?php
namespace rest\modules\v1\controllers;

use common\models\WxUser;
use common\models\WxUserSearch;
use rest\controllers\ActiveController;
use yii\web\NotFoundHttpException;

/*
 * http://127.0.0.1/wxp/rest/web/v1/wx-users/1111?access-token=wx4776f6dc70c1aca0&expand=WxMember
 */

class WxUserController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\WxUser';

    public $searchModelClass = 'common\models\WxUserSearch';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']);

        return $actions;
    }

    /**
     * @param $action
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider($action)
    {
        $reservedParams = ['sort', 'page', 'per-page'];
        $arr = [];
        foreach (\Yii::$app->request->queryParams as $key => $value) {
            if (!in_array(strtolower($key), $reservedParams)) {
                $arr[$key] = $value;
            }
        }
        $params['WxUserSearch'] = $arr;
        $searchModel = new WxUserSearch();
        return $searchModel->search($params);
    }

    public function actionView($id)
    {
        $model = WxUser::findOne(['openid' => $id]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The model $modelClass ($id) does not exist.');
        }

    }
}