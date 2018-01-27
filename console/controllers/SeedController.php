<?php
namespace console\controllers;

use common\models\Activity;
use common\models\WxGh;
use common\modules\bargain\models\BargainComment;
use common\modules\bargain\models\BargainItem;
use common\modules\bargain\models\BargainPost;
use common\modules\bargain\models\BargainTopic;
use noam148\imagemanager\models\ImageManager;
use Yii;
use yii\helpers\FileHelper;

class SeedController extends \yii\console\Controller
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        echo 'hello, world';
    }

    public function actionBargain($gh_sid)
    {
        //if (YII_ENV_PROD) exit;
        $faker = \Faker\Factory::create('zh_CN');
        $gh = WxGh::findOne(['sid' => $gh_sid]);

        $this->generateDemoImages();

        Activity::deleteAll([]);
        $model = new Activity();
        //$model->loadDefaultValues();
        $model->setAttributes([
            'title' => 'bargain',
            'category' => 1,
            'holiday' => 1,
        ], false);
        if (!$model->save()) {
            echo print_r($model->getErrors(), true) . PHP_EOL;
            return;
        }
        $activity_id = $model->id;

        BargainTopic::deleteAll(['gh_id' => $gh->gh_id]);
        BargainItem::deleteAll(['gh_id' => $gh->gh_id]);
        BargainPost::deleteAll(['gh_id' => $gh->gh_id]);
        BargainComment::deleteAll(['gh_id' => $gh->gh_id]);

        $openids = ['on1d1t6JtXGsXPfn91zSKz8gNHx8', 'on1d1t5-oRsJqbuKEH8ISvAqv-D0', 'osHPgshhLKNNnKlLaGT_k5RFtbXg', 'osHPgsmIWDrXFG_5mCe2fCmE1bQg'];
        $topic_ids = [];
        $item_ids = [];

        for ($i = 0; $i < 1; $i++) {
            $topic = new BargainTopic();
            //$topic->loadDefaultValues();
            $topic->setAttributes([
                'activity_id' => $activity_id,
                'gh_id' => $gh->gh_id,
                'title' => 'title-' . ($i + 1),
                'detail' => 'detail-' . ($i + 1),
                'start_time' => '2017-06-05 11:09:01',
                'end_time' => '2018-06-05 11:09:01',
                'status' => BargainTopic::STATUS_DOING,
            ], false);

            if (!$topic->save()) {
                echo print_r($topic->getErrors(), true) . PHP_EOL;
                return;
            }

            $topic_ids[] = $topic->id;
            echo "insert BargainTopic ok" . PHP_EOL;

            for ($j = 0; $j < 4; $j++) {
                $item = new BargainItem();
                $item->loadDefaultValues();
                $item->setAttributes([
                    'topic_id' => $topic->id,
                    'gh_id' => $gh->gh_id,
                    'title' => 'item-title-' . ($j + 1),
                    //'image_url' => $faker->imageUrl,
                    'price' => rand(10000, 50000),
                ], false);

                $image = ImageManager::find()->where(['fileName' => ($j + 1) . '.jpg'])->one();
                if ($image !== null) {
                    $item->image_id = $image->id;
                }

                if (!$item->save()) {
                    echo print_r($item->getErrors(), true) . PHP_EOL;
                    exit;
                }

                $item_ids[] = $item->id;
                echo "insert BargainItem ok" . PHP_EOL;

            }

        }

        //$max = rand(5, 10);
        $max = 2;
        for ($i = 0; $i < $max; $i++) {
            $post = new BargainPost();
            //$post->loadDefaultValues();
            $post->setAttributes([
                'gh_id' => $gh->gh_id,
                'topic_id' => $topic_ids[array_rand($topic_ids)],
                'openid' => $openids[array_rand($openids)],
                'name' => $faker->lastName . $faker->firstName,
                'phone' => $faker->phoneNumber,
                'item_id' => $item_ids[array_rand($item_ids)],
                //'rest_price' => rand(10000, 100000),
            ], false);

            if (!$post->save()) {
                echo print_r($post->getErrors(), true) . PHP_EOL;
                return;
            }

            echo "insert BargainPost ok" . PHP_EOL;

            $end = rand(1, 5);
            //$end = 3;
            for ($j = 0; $j < $end; $j++) {
                $comment = new BargainComment();
                //$comment->loadDefaultValues();
                $comment->setAttributes([
                    'gh_id' => $gh->gh_id,
                    'openid' => $openids[array_rand($openids)],
                    'post_id' => $post->id,
                    //'bargain_price' => rand(100, 1000),
                ], false);

                if (!$comment->save()) {
                    echo print_r($comment->getErrors(), true) . PHP_EOL;
                    return;
                }

                echo "insert BargainComment ok" . PHP_EOL;

            }

        }


    }

    // php yii seed/bargain jmdx

    public function generateDemoImages()
    {
        // if (YII_ENV_DEV) ImageManager::deleteAll();

        $files = FileHelper::findFiles(Yii::$app->imagemanager->mediaPath . DIRECTORY_SEPARATOR . 'ori', ['only' => ['*.jpg']]);
        foreach ($files as $fileName) {
            $sFileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $sFileName = pathinfo($fileName, PATHINFO_BASENAME);
            $model = new ImageManager();
            $model->fileName = str_replace("_", "-", strtolower($sFileName));
            //$model->fileHash = Yii::$app->getSecurity()->generateRandomString(32);
            $model->fileHash = strtolower($sFileName);
            $model->save();
            $toFileName = Yii::$app->imagemanager->mediaPath . DIRECTORY_SEPARATOR . $model->id . '_' . $model->fileHash . '.' . $sFileExtension;
            copy($fileName, $toFileName);
        }
    }

}

