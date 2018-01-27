<?php
namespace rest\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Class ActiveController
 * @package rest\models
 */
class SiteController extends Controller
{

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return ['code' => $exception->getCode(), 'message' => $exception->getMessage(), 'exception' => $exception];
        }
        return $exception;
    }

}