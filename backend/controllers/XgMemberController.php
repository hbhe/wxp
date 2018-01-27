<?php

namespace backend\controllers;

use Yii;
use common\models\WxMember;
use common\models\WxMemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\WxQrlimit;
use common\wosotech\modules\keyStorage\models\FormModel;

/**
 * XgMemberController implements the CRUD actions for WxMember model.
 */
class XgMemberController extends Controller
{
    public $type;
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
     * Lists all WxMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WxMemberSearch();
        if (null !== ($openid = Yii::$app->request->get('openid'))) {
            Yii::$app->request->queryParams = \yii\helpers\ArrayHelper::merge(Yii::$app->request->queryParams, [
                'WxMemberSearch' => ['openid' => Yii::$app->request->get('openid')]
            ]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WxMember model.
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
     * Creates a new SjPolicy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxMember();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SjPolicy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SjPolicy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$url =WxMember::find()->where(['id'=>$id])->One();
    	if(!empty($url->imgPaths)){
    	foreach ($url->imgPaths as $img_url){
    				$img_name=substr($img_url,strrpos($img_url,'/'));
    				if(is_file('../../storage/web/source/1'.$img_name)){
    				unlink('../../storage/web/source/1'.$img_name);
    				}
    	}
    	
    }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WxMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SjPolicy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSetting()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $model = new FormModel([
            'keys' => [
                /*"{$gh_id}.common.module.cost.title" => [
                    'label' => '三元话费全网',
                    'type' => FormModel::TYPE_TEXTINPUT
                ],*/

                "{$gh_id}.common.module.cost.status" => [
                    'label' => '启用状态',
                    'type' => FormModel::TYPE_DROPDOWN,
                    'items' => [
                        'disabled' => '停止',
                        'enabled' => '启用',
                    ]
                ],

            ]
        ]);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('alert', [
                'body' => '保存成功',
                'options' => ['class' => 'alert alert-success']
            ]);
            return $this->refresh();
        }
        return $this->render('setting', ['model' => $model]);
    }
}
