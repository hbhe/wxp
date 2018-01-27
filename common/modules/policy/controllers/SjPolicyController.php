<?php

namespace common\modules\policy\controllers;

use common\models\WxUser;
use common\modules\policy\models\SjPolicy;
use common\modules\policy\models\SjPolicySearch;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SjPolicyController implements the CRUD actions for SjPolicy model.
 */
class SjPolicyController extends \common\wosotech\base\Controller
{
    public $layout = 'sj-poclicy';

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

    public function actions()
    {
        return [
            'avatar-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    $file = $event->file;
                    /* crop */
                    /*
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                    */
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::className()
            ]
        ];
    }

    public function beforeAction($action)
    {
        yii::warning([$_GET, $_POST]);
        return true;
    }

    public function actionModelSubcat()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $parent_id = $parents[0];
                $out = \common\models\WxModel::find()->select(['id', 'name'])->where(['brand_id' => $parent_id])->orderBy(['sort_order' => SORT_DESC])->asArray()->all();
                return \yii\helpers\Json::encode(['output' => $out, 'selected' => empty($out) ? '' : $out[0]['id']]);
            }
        }
        return \yii\helpers\Json::encode(['output' => '', 'selected' => '']);
    }


    // http://127.0.0.1/wxp/mobile/web/?r=policy/sj-policy&gh_id=gh_6b9b67032eb6
    // http://127.0.0.1/wxp/mobile/web/?r=policy/sj-policy&gh_sid=sjdx
    public function actionIndex()
    {
        /*
                $gh = \common\wosotech\Util::getSessionGh();
                $info = $gh->getAuthorizationInfo();
                var_dump($info);
                exit;
        */
        $this->layout = false;
        $openid = \common\wosotech\Util::getSessionOpenid();
        return $this->render('index', [
            'openid' => $openid,
        ]);
    }

    //http://127.0.0.1/wxp/mobile/web/?r=policy/sj-policy/search&gh_id=gh_6b9b67032eb6
    public function actionSearch()
    {
        $openid = \common\wosotech\Util::getSessionOpenid();
        $gh = \common\wosotech\Util::getSessionGh();
        $searchModel = new SjPolicySearch();
        $dataProvider = $searchModel->searchWap(Yii::$app->request->queryParams);
        return $this->render('list', [
            'openid' => $openid,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SjPolicy model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $gh = \common\wosotech\Util::getSessionGh();
        $openid = \common\wosotech\Util::getSessionOpenid();
        return $this->render('view', [
            'gh' => $gh,
            'openid' => $openid,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the SjPolicy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SjPolicy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SjPolicy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate()
    {
        $gh = \common\wosotech\Util::getSessionGh();
        $openid = \common\wosotech\Util::getSessionOpenid();
        $model = new SjPolicy();
        $model->loadDefaultValues();

        $model->openid = $openid;

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->imgPaths)) {
                // upload
                $urls = '';
                foreach ($model->imgPaths as $imgPath) {
                    $urls[] = $imgPath['base_url'] . '/' . $imgPath['path'];
                }
                $model->imgPath = implode(';', $urls);
            }

            if ($model->save()) {
                yii::warning($model->toArray());
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'gh' => $gh,
            'openid' => $openid,
            'model' => $model
        ]);

    }

}


