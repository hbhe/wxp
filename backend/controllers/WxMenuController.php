<?php

namespace backend\controllers;

use Yii;
use common\models\WxMenu;
use common\models\WxMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\wosotech\Util;

/**
 * WxMenuController implements the CRUD actions for WxMenu model.
 */
class WxMenuController extends Controller
{
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
    
    private function getGh() {
        $gh = \common\models\WxGh::findOne(['gh_id' => Util::getSessionGhid()]);
        return $gh;
    }

    /**
     * Lists all WxMenu models.
     * @param string $gh_id 微信公众号原始ID
     * @return mixed
     */
    public function actionIndex()
    {
        $gh = $this->getGh();
        $searchModel = new WxMenuSearch(['gh_id' => Util::getSessionGhid()]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectedGh' => $gh,
        ]);
    }
    
    public function actionFromWx() {
        $gh_id = Util::getSessionGhid();        
        $gh = $this->getGh();
        //$app = yii::$app->wx->getApplication();
        $app = $gh->getWxApp()->getApplication();                        
        $menu = $app->menu;        
        $responseArray = $menu->current();        
        if (1 == $responseArray['is_menu_open'] && !empty($responseArray['selfmenu_info'])) {
            WxMenu::clear($gh_id);
            WxMenu::fromWx($gh_id, $responseArray['selfmenu_info']);
        }
        $searchModel = new WxMenuSearch(['gh_id' => Util::getSessionGhid()]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectedGh' => $gh,
        ]);
    }
    
    public function actionToWx() {
        $gh_id = Util::getSessionGhid();    
        $gh = $this->getGh();
        $app = $gh->getWxApp()->getApplication();                
        $app->menu->destroy();
        $menu_new = WxMenu::toWx($gh_id);
        $app->menu->add($menu_new['button']);
        $responseArray = $app->menu->current();        
        if (1 == $responseArray['is_menu_open'] && !empty($responseArray['selfmenu_info'])) {
            WxMenu::clear($gh_id);
            WxMenu::fromWx($gh_id, $responseArray['selfmenu_info']);
        }
        $searchModel = new WxMenuSearch(['gh_id' => Util::getSessionGhid()]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectedGh' => $gh,
        ]);
    }
    

    /**
     * Displays a single WxMenu model.
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
     * Creates a new WxMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxMenu();
        $gh_id = Util::getSessionGhid();        
        $gh = $this->getGh();
        $model->gh_id = $gh_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'gh' => $gh,
            ]);
        }
    }

    /**
     * Updates an existing WxMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WxMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(empty($model->parent_id)) /*父菜单*/
        {
            WxMenu::deleteAll([
                'parent_id' => $id
            ]);
        }
        $model->delete();

        return $this->redirect(['index', 'gh_id' => $model->gh_id]);
    }

    /**
     * Finds the WxMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
