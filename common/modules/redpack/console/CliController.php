<?php
namespace common\modules\redpack\console;

use yii\helpers\Json;

use common\models\WxGh;
use common\models\WxUser;
use common\models\WxQrlimit;
use common\models\WxMember;
use common\models\WxMemberfans;
use common\modules\redpack\models\RedpackLog;

class CliController extends \yii\console\Controller {

    public $defaultAction = 'index';

    // php yii redpack/cli/test-seed-member jmdx      
    public function actionTestSeedMember($gh_sid) {
        if (YII_ENV_PROD) exit;
        $faker = \Faker\Factory::create('zh_CN');        
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        
        $openids = ['on1d1t6JtXGsXPfn91zSKz8gNHx8', 'on1d1t5-oRsJqbuKEH8ISvAqv-D0', '222', '333'];
        WxMember::deleteAll(['gh_id' => $gh->gh_id]);
        for ($i = 0; $i < 4; $i++) {
            $model = new WxMember;
            $model->loadDefaultValues();
            $model->setAttributes([
                'gh_id' => $gh->gh_id,
                'openid' => $openids[$i],                
                'is_binding' => 1,
                'tel' => $faker->phoneNumber,
            ], false);
            echo "\n insert WxMember " . ($model->save(false) ? 'ok' : 'err:' . print_r($model->getErrors(), true)) . "\n";
        }        
    }

    // php yii redpack/cli/test-seed-revenue jmdx      
    public function actionTestSeedRevenue($gh_sid) {
        if (YII_ENV_PROD) exit;
        $faker = \Faker\Factory::create('zh_CN');        
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        
        $openids = ['on1d1t6JtXGsXPfn91zSKz8gNHx8', 'on1d1t5-oRsJqbuKEH8ISvAqv-D0', '222', '333'];
        RedpackLog::deleteAll(['gh_id' => $gh->gh_id]);
        for ($i = 0; $i < 10; $i++) {
            $openid = $openids[array_rand($openids)];
            $amount = rand(50, 500);
            RedpackLog::writeReveneRecommend($gh->gh_id, $openid, $amount, 'openid_another', 'revenue');
        }
    }

    // php yii redpack/cli/generate-consumes jmdx
    public function actionGenerateConsumes($gh_sid) {
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        RedpackLog::generateConsumes($gh->gh_id);
    }

    // php yii redpack/cli/send-redpacks jmdx
    public function actionSendRedpacks($gh_sid) {
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        RedpackLog::sendRedpacks($gh->gh_id);
    }

    // php yii redpack/cli/check-send-redpacks jmdx
    public function actionCheckSendRedpacks($gh_sid)
    {
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        RedpackLog::checkSendRedpacks($gh->gh_id);
    }

    // php yii redpack/cli/test-add-one-revenue on1d1t6JtXGsXPfn91zSKz8gNHx8
    // php yii redpack/cli/test-add-one-revenue on1d1t5-oRsJqbuKEH8ISvAqv-D0
    public function actionTestAddOneRevenue($openid, $amount=50) {
        $wxUser = WxUser::findOne(['openid' => $openid]);
        RedpackLog::writeReveneRecommend($wxUser->gh_id, $openid, $amount, 'openid_another', 'add a revenue');
    }

    // php yii redpack/cli/test-add-one-consume on1d1t6JtXGsXPfn91zSKz8gNHx8
    public function actionTestAddOneConsume($openid, $amount=100) {
        $wxUser = WxUser::findOne(['openid' => $openid]);
        RedpackLog::writeConsume($wxUser->gh_id, $openid, $amount, $amount = 'add a consume');
    }

    // php yii redpack/cli/test-send-redpack 45
    public static function actionTestSendRedpack($id) {
        $model = RedpackLog::findOne($id);
        $gh = WxGh::findOne(['gh_id' => $model->gh_id]);
        $params = \common\modules\redpack\Module::getParams($model->gh_id);
        $mch_billno = $gh->sendRedpack($model->openid, $model->amount, $params['redpack.wishing.recommend'], $params['redpack.test']);
        if ($mch_billno) {        
            $model->mch_billno = $mch_billno;
            $model->sendtime = date('Y-m-d H:i:s');
            $model->status = RedpackLog::STATUS_SENT;      
            if (!$model->save(false)) {
                yii::error([__METHOD__, __LINE__, $model->toArray(), $model->getErrors()]);
            }

            if (!empty($params['redpack.aftersend.prompt.custom.text'])) {
                $text = strtr($params['redpack.aftersend.prompt.custom.text'], [
                    '{nickname}' => empty($model->wxUser->nickname) ? '' : $model->wxUser->nickname,
                    '{amount}' => $model->amount,
                    '{gh_title}' => $gh->title,
                ]);            
                $gh->sendCustomText($model->openid, $text);
            }                    
            
        }        
    }

    // php yii redpack/cli/send-redpack-openid //红包发放
    public static function actionSendRedpackOpenid() {
        $rows = [
                [
                'openid' => 'on1d1t5-oRsJqbuKEH8ISvAqv-D0',
                'amount' => 101,
                ],
            /* [
                'openid' => 'on1d1t_c8syViY750bcTq36i3gYo',
                'amount' => 1314,
            ],
            [
                'openid' => 'on1d1t2EhoCapQuUiV7Dd6cYmh48',
                'amount' => 1314,
            ],
                htht
            [
                'openid' => 'on1d1t7TqNMFIWFzVq9x0FWlvbNU',
                'amount' => 1314,
            ],  */
        ];

        foreach ($rows as $row) {
            $model = WxUser::findOne(['openid' => $row['openid']]);
            if (null === $model) {
                continue;
            }

            $gh = WxGh::findOne(['gh_id' => $model->gh_id]);
            try {
                $mch_billno = $gh->sendRedpack($row['openid'], $row['amount'], '恭喜中奖', false, '活动红包', '谢谢参与');
                if ($mch_billno) {
                    $gh->sendCustomText($model->openid, '亲，恭喜您在“荆门电信·爱情帮帮砍”活动中获奖，感谢您的参与和支持，快来领取属于您的现金红包吧！');
                }
            } catch (\Exception $e) {
                \yii::error($e->getMessage());
            }
        }
    }

    // php yii redpack/cli/init-redpack-balance jmdx, set is_paid for all fans log //初始化以前的红包
    public function actionInitRedpackBalance($gh_sid) {
        $gh = WxGh::findOne(['sid' => $gh_sid]);
        RedpackLog::deleteAll();

        WxMember::updateAll(['redpack_balance' => 0, 'redpack_revenue_amount' => 0, 'redpack_consume_amount' => 0], ['gh_id' => $gh->gh_id]);
        $models = WxMember::find()
            ->andWhere(['gh_id' => $gh->gh_id])
            ->all();

        foreach($models as $model) {
            $fans = $model->fanMembers;

            if ($count = count($fans)) {
                RedpackLog::writeReveneRecommend($model->gh_id, $model->openid, 50 * $count, '', 'init');
                foreach ($fans as $fan) {
                    $fan->is_paid = 1;
                    $fan->save();
                }
            }
            $model->vermicelli = $count;
            $model->save();
        }
    }

    // php yii redpack/cli/query-send-redpack 1310121701201705129630555262 //红包查询
    public static function actionQuerySendRedpack($mch_billno) {
        $model = RedpackLog::findOne(['mch_billno' => $mch_billno]);  
        $gh = WxGh::findOne(['gh_id' => $model->gh_id]);
        $result = $gh->querySendRedpack($model->mch_billno);
        var_dump($result);
    }

    //php yii redpack/cli/query-jmdx-redpack 1310121701201706021796645267
    public static function actionQueryJmdxRedpack($mch_billno) {
        //$model = RedpackLog::findOne(['mch_billno' => $mch_billno]);
        $gh_id = "gh_4b9887a417ef";
        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        $result = $gh->querySendRedpack($mch_billno);
        var_dump($result);
    }

    public static function actionInitFansStat() {
        exit;
        $gh_id = WxGh::WXGH_DEMO;
        $models = WxMemberfans::find()
            ->andWhere(['gh_id' => $gh_id])
            ->all();
        foreach ($models as $model) {
            if (!empty($model->parentMember->staff->area)) {
                $model->area = $model->parentMember->staff->area;
                $model->save();
            }
        }

        $date = '2017-03-17';
        $today = date("Y-m-d");
        while(true) {
            echo $date . PHP_EOL;
            WxMemberfans::statScore(WxGh::WXGH_DEMO, $date);
            $time = strtotime ('+1 day' ,strtotime($date));
            $date = date("Y-m-d", $time);
            if ($date >= $today) {
                break;
            }
        }

    }
}

