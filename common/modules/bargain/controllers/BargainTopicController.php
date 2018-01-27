<?php

namespace common\modules\bargain\controllers;

use Yii;
use common\modules\bargain\models\BargainTopic;
use common\modules\bargain\models\BargainTopicSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BargainTopicController implements the CRUD actions for BargainTopic model.
 */
class BargainTopicController extends Controller
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
     * Lists all BargainTopic models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->user->setReturnUrl(Url::current());

        $searchModel = new BargainTopicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPause($id)
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $model = BargainTopic::findOne($id);
        if ($model->status == 1) {
            if (BargainTopic::updateAll(['status' => 2],['id' => $id])) {
                return $this->redirect(['index','gh_id'=>$gh_id]);
            }
        }
        if ($model->status == 2) {
            if (BargainTopic::updateAll(['status' => 1],['id' => $id])) {
                return $this->redirect(['index','gh_id'=>$gh_id]);
            }
        }
        return $this->redirect(['index','gh_id'=>$gh_id]);
    }

    public function actionSelectActivity(){
        $db = new \yii\db\Query;
        $model = $db->select('*')->from('wx_activity')->where(['status' => 0])->all();
        return $this->render('select-activity', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single BargainTopic model.
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
     * Creates a new BargainTopic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($activity_id)
    {
        $model = new BargainTopic();
        $model->load(Yii::$app->request->get(), '');
        if ($model->load(Yii::$app->request->post())) {
            Yii::error(['data', Yii::$app->request->post()]);
            if ($model->save()) {
                //return $this->redirect(['index']);
                return $this->goBack();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing BargainTopic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        Yii::error(['data', Yii::$app->request->post()]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goBack();
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing BargainTopic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BargainTopic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BargainTopic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BargainTopic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
