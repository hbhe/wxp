<?php

namespace common\modules\wall\controllers;

use Yii;
use common\modules\wall\models\WxWall;
use common\modules\wall\models\WxWallSearch;
use common\modules\walls\models\WxWalls;
use common\modules\walls\models\WxWallsSearch;
use common\modules\wall\models\WxWallSign;
use common\modules\wall\models\WxWallSignSearch;
use common\modules\wall\models\WxWallShake;
use common\modules\wall\models\WxWallShakeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\wosotech\modules\keyStorage\models\FormModel;
use GuzzleHttp\json_decode;
use kartik\datetime\DatetimePicker;


/**
 * AdminController implements the CRUD actions for WxWall model.
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
     * Lists all WxWall models.
     * @return mixed
     */
    // http://127.0.0.1/wxp/mobile/web/index.php?r=wall/admin&gh_id=gh_6b9b67032eb6
    // http://m.mysite.com/index.php?r=wall/admin&gh_id=gh_6b9b67032eb6
    // http://127.0.0.1/wxp/backend/web/wall/admin/index?gh_id=gh_6b9b67032eb6
    public function actionIndex()
    {
    	//$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
        $gh_id = \common\wosotech\Util::getSessionGhid(); 
        $gh = \common\wosotech\Util::getSessionGh();   
        $searchModel = new WxWallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'gh_id'   => $gh_id,
       ]);
    }
    public function actionSign()
    {
        // $gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $gh = \common\wosotech\Util::getSessionGh();
        if (isset($_POST['selection'])) {
            foreach($_POST['selection'] as $selection) {
                if ($model = WxWallSign::findOne($selection)) {
                    $model->delete();
                }
            }
        }
        $searchModel = new WxWallSignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('sign', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'gh_id'   => $gh_id,
                ]);
    }
    public function actionWall()
     {
     	//$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
     	$gh_id = \common\wosotech\Util::getSessionGhid(); 
        $gh = \common\wosotech\Util::getSessionGh();    

     	$searchModel = new WxWallSearch();
   		$dataProvider = $searchModel->searchs(Yii::$app->request->queryParams);   
     	return $this->render('wall', [
     			'searchModel' => $searchModel,
     			'dataProvider' => $dataProvider,
     			'gh_id'   => $gh_id,
     			]);
     }
    
     public function actionShake()
     {
         //$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
         $gh_id = \common\wosotech\Util::getSessionGhid();
         $gh = \common\wosotech\Util::getSessionGh();
         
         if (isset($_POST['selection'])) {
             foreach($_POST['selection'] as $selection) {
                 if ($model = WxWallShake::findOne($selection)) {
                     $model->delete();
                 }
             }
         }
         
         $searchModel = new WxWallShakeSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         return $this->render('shake', [
                 'searchModel' => $searchModel,
                 'dataProvider' => $dataProvider,
                 'gh_id'   => $gh_id,
                 ]);
     }

    /**
     * Displays a single WxWall model.
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
     * Creates a new WxWall model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxWall();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WxWall model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $gh = \common\wosotech\Util::getSessionGh();
        if ( \Yii::$app->request->isAjax ) {
            $ids=$_GET['keys'];   
            $arr=explode("," ,$ids);
            foreach ( $arr as $v ) {
                $model=WxWall::findOne($v);
                $model->is_checked=1;
                $model->save();
                $models=WxWalls::findOne($v);
                $models->is_checked=1;
                $models->save();
            }
            return $this->redirect(['index','gh_id'=>$gh_id]);
        } else {
            $id=intval($_GET['id']);
            $gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
            $model=WxWall::findOne($id);
            $model->is_checked=1;
            $model->save();
            $models=WxWalls::findOne($id);
            $models->is_checked=1;
            $models->save();
            return $this->redirect(['index','gh_id'=>$gh_id]);
        } 
    }
    
    public function actionBled()
    {
        //$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
        $gh_id = \common\wosotech\Util::getSessionGhid(); 
        $gh = \common\wosotech\Util::getSessionGh();    
         if(Yii::$app->keyStorage->get("{$gh_id}.common.module.wall.refresh", '') == 'enabled'){
             $gh->setKeyStorage("common.module.wall.refresh", "disbled");
                 return $this->redirect(['index','gh_id'=>$gh_id]);
         }else{
             $gh->setKeyStorage("common.module.wall.refresh", "enabled");
                 return $this->redirect(['index','gh_id'=>$gh_id]);
             
         }
    }
    /**
     * Deletes an existing WxWall model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model= $this->findModel($id);
        $gh_id=$model->gh_id;
        $is_wall=$model->is_wall;
        $model->delete();
        if ( $is_wall == 1 ) {
            return $this->redirect(['wall','gh_id'=>$gh_id]);
        } else {
            return $this->redirect(['index','gh_id'=>$gh_id]);
        }
    }

    /**
     * Finds the WxWall model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxWall the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxWall::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSettings()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();    	    
        $model = new FormModel([
            'keys' => [
                "{$gh_id}.common.module.wallsign.title" => [
                     'label' => '签到',
                     'type' => FormModel::TYPE_TEXTINPUT
                ],
                    
                "{$gh_id}.common.module.wallsign.status" => [
                     'label' => '签到状态',
                     'type' => FormModel::TYPE_DROPDOWN,
                      'items' => [
                         'disabled' => '停止',                      
                         'enabled' => '开始',
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
        return $this->render('settings', ['model' => $model]);
    }
    
    public function actionSettingswall()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $model = new FormModel([
                'keys' => [
                    "{$gh_id}.common.module.wall.title" => [
                         'label' => '活动名称',
                         'type' => FormModel::TYPE_TEXTINPUT
                     ],
    
                            //$gh->getKeyStorage('common.module.wall.status', 'disabled');
                    "{$gh_id}.common.module.wall.status" => [
                         'label' => '活动状态',
                         'type' => FormModel::TYPE_DROPDOWN,
                         'items' => [
                              'disabled' => '停止',
                              'enabled' => '开始',
                          ]
                      ],
                    "{$gh_id}.common.module.wall.refresh" => [
                          'type' => FormModel::TYPE_HIDDENINPUT,
                          'label' => '',
                          'items' => [
                                'disabled' => '停止',
                                'enabled' => '开始',
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
            return $this->render('settingswall', ['model' => $model]);
        }
        
        public function actionSettingsshake()
        {
            $gh_id = \common\wosotech\Util::getSessionGhid();
            $gh = \common\wosotech\Util::getSessionGh();
            $model = new FormModel([
                'keys' => [
                    "{$gh_id}.common.module.wallshake.title" => [
                           'label' => '活动名称',
                            'type' => FormModel::TYPE_TEXTINPUT
                        ],
        
                    //$gh->getKeyStorage('common.module.wall.status', 'disabled');
                    "{$gh_id}.common.module.wallshake.starttime" => [
                            'label' => '是否开始活动',
                            'type' => FormModel::TYPE_DROPDOWN,
                            'items' => [
                                    'disabled' => '否',
                                    'enabled' => '是',
                                    ]
                      ],
                    /* "{$gh_id}.common.module.wallshake.stoptime"  => [
                             'label' => '结束时间',
                            'type'=>FormModel::TYPE_WIDGET,
                            'options'=>[ 
                                 'name' => 'Article[stoptime]', 
                                 'options' => ['placeholder' => ''], 
                                 'value' => '2016-12-21 22:10:10', 
                                 'pluginOptions' => [  'autoclose' => true, 
                                  'format' => 'yyyy-mm-dd HH:ii:ss', 
                                  'todayHighlight' => true 
                                  ]
                            ], 
                            'widget'=>"kartik\datetime\DatetimePicker" ,
                     ], */
                     "{$gh_id}.common.module.wallshake.duration" => [
                             'label' => '活动时长(单位:秒)',
                             'type' => FormModel::TYPE_TEXTINPUT,
                      ],
                    
                    "{$gh_id}.common.module.wallshake.awards" => [
                            'label' => '奖项个数(单位:个)',
                            'type' => FormModel::TYPE_TEXTINPUT,
                     ],
 
                   ],
                ]);
            if ($gh->getKeyStorage('common.module.wallshake.starttime', '')=='enabled'){
                $data = Yii::$app->cache->get('data_key');
                if ($data === false) {
                    Yii::$app->cache->set('data_key', 'enabled', $gh->getKeyStorage('common.module.wallshake.duration', ''));
                }
            }
              if ($model->load(Yii::$app->request->post()) && $model->save()) {
                   Yii::$app->session->setFlash('alert', [
                       'body' => '保存成功',
                        'options' => ['class' => 'alert alert-success']
                    ]);
                    return $this->refresh();
        }
                    return $this->render('settingsshake', ['model' => $model]);
   }    

    public function actionDisplayQr()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();    	
        return \yii\helpers\Html::img(\common\modules\wall\models\WxWallSign::getWallSignQrUrl($gh_id));
    }
    
    
}
