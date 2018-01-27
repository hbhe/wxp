<?php

namespace backend\controllers;

use Yii;
use common\models\WxKeyword;
use common\models\WxKeywordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use GuzzleHttp\json_encode;
use trntv\filekit\actions\UploadAction;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\behaviors\UploadBehavior;
use \noam148\imagemanager\models\ImageManager;



/**
 * WxKeywordController implements the CRUD actions for WxKeyword model.
 */
class WxKeywordController extends Controller
{
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
     * Lists all WxKeyword models.
     * @return mixed
     */
     /* public function actions()
    {
        return [
                'avatar-upload' => [
                        'class' => UploadAction::className(),
                        'deleteRoute' => 'avatar-delete',
                        'on afterSave' => function ($event) {
                            $file = $event->file;
                            //$img = ImageManagerStatic::make($file->read())->fit(215, 215);
                           // $file->put($img->encode());
                        }
                                    ],
                 'avatar-delete' => [
                        'class' => DeleteAction::className()
                                    ],
               ];
    }  */
    
    public function actionIndex()
    {
        $searchModel = new WxKeywordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WxKeyword model.
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
     * Creates a new WxKeyword model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxKeyword();
        $gh_id = \common\wosotech\Util::getSessionGhid();
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->keyword)) {
            	$keywords=explode(',', $model->keyword);
            	foreach ($keywords as $keyword) {
            	    
            	    $_model=clone $model;
            	    $_model->setAttributes($keyword);
            		$_model->keyword = $keyword;
            		$_model->gh_id=$gh_id;
            		if (!empty($_model->image_id)) {
            		    //$imgpath =$_model->imgpath;
            		    //var_dump($imgpath);exit;
            		    //$_model->avatar_path=$imgpath['path'];
            		    //$_model->avatar_base_url=$imgpath['base_url'];
            		    //$_model->picurl= $imgpath['base_url'] . '/' . $imgpath['path'];
            		    $_model->picurl = \Yii::$app->imagemanager->getImagePath($_model->image_id, 9999, 9999);
            		}
            		if (!$_model->save()) {
            			$_model->addError('keyword',"关键词错误");
            		}
            	}
            	return $this->redirect(['index']);
            } else {
                $model->gh_id=$gh_id;
                if (!empty($model->image_id)) {
                    //$imgpath =$model->imgpath;
                    //$model->avatar_path=$imgpath['path'];
                    //$model->avatar_base_url=$imgpath['base_url'];
                    //$model->picurl= $imgpath['base_url'] . '/' . $imgpath['path'];
                    $model->picurl = \Yii::$app->imagemanager->getImagePath($model->image_id, 9999, 9999);
                }
                if ($model->save()) {
                    yii::warning($model->toArray());
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }   
        } else {
            return $this->render('create', [
                    'model' => $model,
                    ]);
        }
    }
    
    

    /**
     * Updates an existing WxKeyword model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        if (!empty($model->image_id)) {
                    //$imgpath =$model->imgpath;
                    //$model->avatar_path=$imgpath['path'];
                    //$model->avatar_base_url=$imgpath['base_url'];
                    //$model->picurl= $imgpath['base_url'] . '/' . $imgpath['path'];
                    $model->picurl = \Yii::$app->imagemanager->getImagePath($model->image_id, 9999, 9999);
                }
            if ( $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WxKeyword model.
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
     * Finds the WxKeyword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxKeyword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxKeyword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
