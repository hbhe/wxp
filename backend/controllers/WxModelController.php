<?php

namespace backend\controllers;

use Yii;
use common\models\WxModel;
use common\models\WxModelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SjPhoneModelController implements the CRUD actions for SjPhoneModel model.
 */
class WxModelController extends Controller
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
     * Lists all SjPhoneModel models.
     * @return mixed
     */
    public function actionIndex($brand_id)
    {
        $brand = \common\models\WxBrand::findOne($brand_id);    
        Yii::$app->request->queryParams = yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams,
            [ 'SjPhoneModelSearch' => [
                'brand_id' => $brand_id,
            ]]
        );
    
        $searchModel = new WxModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new WxModel();
        $model->loadDefaultValues();
        $model->brand_id = $brand_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index', 'brand_id'=>$brand->id]);
        }
        
        return $this->render('index', [
            'model' => $model,
            'brand' => $brand,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SjPhoneModel model.
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
     * Creates a new SjPhoneModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($brand_id)
    {
        $model = new WxModel();
        $model->loadDefaultValues(); // added by hbhe
        $brand = \common\models\WxBrand::findOne($brand_id);
        $model->brand_id = $brand_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index', 'brand_id'=>$brand->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SjPhoneModel model.
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
     * Deletes an existing SjPhoneModel model.
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
     * Finds the SjPhoneModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
