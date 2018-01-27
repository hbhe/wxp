<?php
namespace rest\modules\v1\controllers;

use common\modules\bargain\models\BargainComment;
use common\modules\bargain\models\BargainCommentSearch;
use rest\controllers\ActiveController;
use Yii;

/**
 * Class BargainPostController
 * @package rest\modules\v1\controllers
 *
 * http://127.0.0.1/wxp/rest/web/v1/bargain-comments?access-token=abcd
 *
 */
class BargainCommentController extends ActiveController
{
    public $modelClass = 'common\modules\bargain\models\BargainComment';

    public $searchModelClass = 'common\modules\bargain\models\BargainCommentSearch';

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {
        $model = new BargainComment();
        $model->load(Yii::$app->request->get(), '');
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            $model->bargain_price = $model->getPrice();
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