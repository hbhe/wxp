<?php

namespace common\modules\redpack\controllers;

use Yii;
use common\modules\redpack\models\RedpackLog;
use common\modules\redpack\models\RedpackLogSearch;
use common\modules\redpack\models\RedpackStat;
use common\modules\redpack\models\RedpackStatSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for RedpackLog model.
 */
class AdminController extends Controller
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
     * Lists all RedpackLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (null !== ($openid = Yii::$app->request->get('openid'))) {
            Yii::$app->request->queryParams = \yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [
                'RedpackLogSearch' => ['openid' => Yii::$app->request->get('openid')]
            ]);
        }

        if (Yii::$app->request->post('hasEditable')) {
            $RedpackLogId = Yii::$app->request->post('editableKey');
            $model = RedpackLog::findOne($RedpackLogId);
            $out = \yii\helpers\Json::encode(['output'=>'', 'message'=>'']);
            $posted = current($_POST['RedpackLog']);
            $post = ['RedpackLog' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output = '';
                if (isset($posted['amount'])) {
                    //$output = Yii::$app->formatter->asCurrency($model->amount /100);
                }                 
                $out = \yii\helpers\Json::encode(['output'=>$output, 'message'=>'']);
            }
            echo $out;
            return;
        }
    
        $searchModel = new RedpackLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Displays a single RedpackLog model.
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
     * Creates a new RedpackLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RedpackLog();
        $model->gh_id = \common\wosotech\Util::getSessionGhid();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RedpackLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing RedpackLog model.
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
     * Finds the RedpackLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RedpackLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RedpackLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStat()
    {
        $searchModel = new RedpackStatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('stat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
}
