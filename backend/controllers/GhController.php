<?php

namespace backend\controllers;

use common\models\WxAuthorizer;
use common\models\WxGh;
use common\models\WxGhSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * GhController implements the CRUD actions for WxGh model.
 */
class GhController extends Controller
{
    public $layout = 'platform';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all WxGh models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can(\common\models\User::ROLE_ADMINISTRATOR)) {
            $this->redirect(['wx-user/index']);
        }
        
        $searchModel = new WxGhSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WxGh model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the WxGh model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WxGh the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxGh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new WxGh model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxGh();
        $model->loadDefaultValues();

        if (YII_ENV_PROD) {
            $openPlatform = WxAuthorizer::getOpenPlatform();
            $preAuthCode = $openPlatform->pre_auth->getCode();
            //$response = $openPlatform->pre_auth->redirect(Url::current([], true));
            $response = $openPlatform->pre_auth->redirect(Url::to(['gh/create'], true), true);
            $authUrl = $response->getTargetUrl();
        } else {
            $authUrl = null;
        }

        if (!empty($authCode = \Yii::$app->request->get('auth_code'))) {
            Yii::error(['gh/create', __METHOD__, __LINE__]);
            WxAuthorizer::handleAuthCode($authCode);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'authUrl' => $authUrl,
            ]);
        }
    }

    /**
     * Updates an existing WxGh model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WxGh model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetSessionGhId($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->set('gh_sid', $model->sid);
        Yii::$app->session->set('gh_id', $model->gh_id);        
        return $this->redirect(['index']);
    }

}
