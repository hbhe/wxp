<?php
namespace rest\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveController
 * @package rest\models
 */
class ActiveController extends \yii\rest\ActiveController
{

    public $searchModelClass;

    /**
     *
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function beforeAction($action)
    {
        Yii::error(['INIT', $this->route, Yii::$app->request->get(), Yii::$app->request->post()]);
        return parent::beforeAction($action);
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'access-token',
            'optional' => [
                'login',
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        unset($behaviors['contentNegotiator']);
        return $behaviors;
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;

        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The model $modelClass ($id) does not exist.');
        }
    }

    public function prepareDataProvider($action) {
        if (empty($this->searchModelClass)) {
            throw new \Exception('Search model class CAN NOT be empty.');
        }
        $searchModel = new $this->searchModelClass();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function serializeModelErrors($model)
    {
        Yii::$app->response->setStatusCode(422, 'Data Validation Failed.');
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'message' => $message,
            ];
        }
        $result['message'] = implode(';', ArrayHelper::getColumn($result, 'message'));
        return $result;
    }

}