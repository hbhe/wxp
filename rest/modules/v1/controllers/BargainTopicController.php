<?php
namespace rest\modules\v1\controllers;

use common\modules\bargain\models\BargainTopicSearch;
use rest\controllers\ActiveController;

/**
 * Class BargainTopicController
 * @package rest\modules\v1\controllers
 *
 * http://127.0.0.1/wxp/rest/web/v1/bargain-topics?access-token=wx3283c99746957d28
 * http://127.0.0.1/wxp/rest/web/v1/bargain-topics/1?access-token=wx3283c99746957d28&expand=bargainItems
 * http://127.0.0.1/wxp/rest/web/v1/bargain-topics/4?access-token=wx3283c99746957d28
 * http://api.mysite.com/v1/bargain-topics?access-token=wx3283c99746957d28&expand=bargainItems
 * http://localhost:8080/#/activity/1/bargainirg?openid=o71PJs0zwHG66Zn-H9tH3FAntACw&appid=wx3283c99746957d28
 */
class BargainTopicController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\modules\bargain\models\BargainTopic';

    public $searchModelClass = 'common\modules\bargain\models\BargainTopicSearch';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        unset($actions['view']);

        return $actions;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $model;
    }

}