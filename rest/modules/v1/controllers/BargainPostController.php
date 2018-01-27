<?php
namespace rest\modules\v1\controllers;
use common\modules\bargain\models\BargainPost;
use common\modules\bargain\models\BargainPostSearch;
use rest\controllers\ActiveController;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;


/**
 * Class BargainPostController
 * @package rest\modules\v1\controllers
 *
 * http://127.0.0.1/wxp/rest/web/v1/bargain-posts?access-token=wx3283c99746957d28
 * http://127.0.0.1/wxp/rest/web/v1/bargain-posts/1?access-token=wx3283c99746957d28
 * http://127.0.0.1/wxp/rest/web/v1/bargain-posts?expand=bargainTopic,bargainItem,bargainComments&access-token=wx3283c99746957d28
 * http://127.0.0.1/wxp/rest/web/v1/bargain-posts?expand=bargainTopic,bargainItem&access-token=wx3283c99746957d28
 *
 */
class BargainPostController extends ActiveController
{
    public $modelClass = 'common\modules\bargain\models\BargainPost';

    public $searchModelClass = 'common\modules\bargain\models\BargainPostSearch';

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $model = new BargainPost();
        $model->load(Yii::$app->request->get(), '');
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate()) {
            if (!$model->save()) {
                Yii::error(['save db error', __METHOD__, __LINE__, $model->getErrors()]);
                return $this->serializeModelErrors($model);
            }
        } else {
            Yii::error(['validate user error',  __METHOD__, __LINE__, $model->getErrors()]);
            return $this->serializeModelErrors($model);
        }

        return $model;
    }

}