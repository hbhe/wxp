<?php
namespace rest\modules\v1\controllers;

use common\models\WxMember;
use common\models\WxMemberSearch;
use rest\controllers\ActiveController;
use yii\web\NotFoundHttpException;

/*
 * http://127.0.0.1/wxp/rest/web/v1/wx-xgdx-members/1111?access-token=wx4776f6dc70c1aca0
 *
 * */
class WxMemberController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\WxMember';

    public $searchModelClass = 'common\models\WxMemberSearch';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['view']['findModel'] = [$this, 'findModel'];

        return $actions;
    }

    public function findModel($id)
    {
        $model = WxMember::find()->where(['or', ['id' => $id], ['openid' => $id]])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The model $modelClass ($id) does not exist.');
        }
    }

}