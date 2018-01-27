<?php

namespace backend\controllers;

use Yii;
use common\models\WxArticle;
use common\models\WxArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use common\wosotech\Util;

use common\models\WxGh;

/**
 * ArticleController implements the CRUD actions for WxArticle model.
 */
class WxArticleController extends Controller
{
/*
    public $layout = 'metronic';
    public function actions()
    {
        return [
            'imagesget' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => Yii::$app->request->getHostInfo(). Yii::getAlias('@web/wysiwyg/'),                
                'path' => '@webroot/wysiwyg',
                'type' => \vova07\imperavi\actions\GetAction::TYPE_IMAGES,
            ],
            'imageupload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => Yii::$app->request->getHostInfo(). Yii::getAlias('@web/wysiwyg/'),
                'path' => '@webroot/wysiwyg',
            ],            
        ];
    }
*/
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all WxArticle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WxArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WxArticle model.
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
     * Creates a new WxArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxArticle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WxArticle model.
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
     * Deletes an existing WxArticle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WxArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WxArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxArticle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

/*
//        $model->messageMassPreview(MGh::GH_XIANGYANGUNICOM_OPENID_HBHE);
//        $model->messageMassPreview(MGh::GH_XIANGYANGUNICOM_OPENID_KZENG);
//        $model->messageMassPreview(MGh::GH_WOSO_OPENID_HBHE);
//        $model->messageMassSend([MGh::GH_WOSO_OPENID_HBHE]);
//        $model->messageMassSend([MGh::GH_WOSO_OPENID_KZENG]);

*/
