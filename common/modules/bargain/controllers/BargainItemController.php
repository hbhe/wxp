<?php

namespace common\modules\bargain\controllers;

use Yii;
use common\modules\bargain\models\BargainItem;
use common\modules\bargain\models\BargainItemSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BargainItemController implements the CRUD actions for BargainItem model.
 */
class BargainItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BargainItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BargainItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->user->setReturnUrl(Url::current());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BargainItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BargainItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BargainItem();
        $model->load(Yii::$app->request->get(), '');
        if ($model->load(Yii::$app->request->post())) {
            //var_dump($model->send_week);exit;
            $model->gh_id = \common\wosotech\Util::getSessionGhid();
            $model->price = $model->price * 100;
            $model->end_price = $model->end_price * 100;
            if ($model->save()) {
                return $this->goBack();
            }
            Yii::error(['save error', $model->errors]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing BargainItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (strpos(Yii::$app->request->getReferrer(),"index") !== false) {
            $model->price = $model->price / 100;
            $model->end_price = $model->end_price / 100;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->price = $model->price * 100;
            $model->end_price = $model->end_price * 100;
            if ($model->save()) {
                return $this->goBack();
            }

        }
            return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing BargainItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'topic_id' => Yii::$app->request->get('topic_id')]);
    }

    /**
     * Finds the BargainItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BargainItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BargainItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
