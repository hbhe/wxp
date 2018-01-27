<?php

namespace common\modules\redpack\controllers;

use common\models\WxUser;
use common\modules\redpack\models\Vote;
use Yii;

class VoteController extends \common\wosotech\base\Controller
{

    public $layout = 'weui';

    public $enableCsrfValidation = true;

    // http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/vote/index
    public function actionIndex()
    {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $openid = \common\wosotech\Util::getSessionOpenid();
        //$model = WxUser::find()->where(['openid' => $openid])->one();
        //$model = WxUser::find()->where(['id' => [1, 2, 3, 4]])->one();
        return $this->render('index', [
            //'model' => $model,
            'openid' => $openid,
        ]);
    }

    // http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/vote/over
    public function actionOver()
    {
        //$gh_id = \common\wosotech\Util::getSessionGhid();
        //$openid = \common\wosotech\Util::getSessionOpenid();
        //$model = WxUser::find()->where(['openid' => $openid])->one();
        $model = WxUser::find()->where(['id' => [1, 2, 3, 4]])->one();
        return $this->render('over', [
            'model' => $model,
        ]);
    }

    // http://127.0.0.1/wxp/mobile/web/index.php?r=redpack/vote/result
    public function actionResult()
    {
        //$gh_id = \common\wosotech\Util::getSessionGhid();
        //$openid = \common\wosotech\Util::getSessionOpenid();
        //$model = WxUser::find()->where(['openid' => $openid])->one();
        $model = WxUser::find()->where(['id' => [1, 2, 3, 4]])->one();
        $data['totalNumber'] = Vote::getStatTotalNumber();

        $sum = 0;
        for ($i = 0; $i < count(Vote::getGenderOptionName()); $i++) {
            $data['gender'][$i] = Vote::getStatGender($i);
            $sum += $data['gender'][$i];
        }
        $data['gender']['sum'] = $sum;

        $sum = 0;
        for ($i = 0; $i < count(Vote::getAgeOptionName()); $i++) {
            $data['age'][$i] = Vote::getStatAge($i);
            $sum += $data['age'][$i];
        }
        $data['age']['sum'] = $sum;

        $sum = 0;
        for ($i = 0; $i < count(Vote::getExpenseOptionName()); $i++) {
            $data['expense'][$i] = Vote::getStatExpense($i);
            $sum += $data['expense'][$i];
        }
        $data['expense']['sum'] = $sum;

        /*
        $arr = Vote::getStatTypeAndProblem();
        $sum = 0;
        for ($i = 0; $i < 4; $i++) {
            $data['type'][$i] = $arr['type'];
            $sum += $data['type'][$i];
        }
        $data['type']['sum'] = $sum;
*/
        $arr = Vote::getStatTypeAndProblem();
        Yii::error($arr);

        $data['type'] = $arr['type'];
        $data['problem'] = $arr['problem'];
        return $this->render('result', [
            'model' => $model,
            'data' => $data,
        ]);
    }
}
