<?php
namespace rest\modules\v1\controllers;

use common\modules\bargain\models\BargainItemSearch;
use rest\controllers\ActiveController;

/**
 * Class BargainPostController
 * @package rest\modules\v1\controllers
 *
 * http://127.0.0.1/wxp/rest/web/v1/bargain-items?access-token=wx3283c99746957d28
 *
 */

class BargainItemController extends ActiveController
{
    public $modelClass = 'common\modules\bargain\models\BargainItem';

    public $searchModelClass = 'common\modules\bargain\models\BargainItemSearch';

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
}