<?php

namespace common\modules\redpack\models;

use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\dynattribute\DynamicAttributeBehavior;

/**
 * This is the model class for table "wx_redpack_vote".
 *
 * @property integer $id
 * @property integer $gender
 * @property integer $age
 * @property integer $expense
 * @property string $type
 * @property string $problem
 * @property integer $status
 * @property string $ip
 * @property string $created_at
 * @property string $updated_at
 */
class Vote extends \yii\db\ActiveRecord
{
    const FAKE_NUMBER = 800;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_redpack_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'age', 'expense', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'problem'], 'string', 'max' => 255],
            [['ip', 'openid'], 'string', 'max' => 128],

            [['status'], 'default', 'value' => 0],

            [[
                'type_h5',
                'type_client',
                'type_app',
                'type_box',

                'problem_happy',
                'problem_performance',
                'problem_content',
                'problem_art',
            ], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender' => '性别',
            'age' => '年龄',
            'expense' => '月消费',
            'type' => '类型',
            'problem' => '问题',
            'status' => '状态',
            'ip' => 'IP',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'type_h5' => '网页游戏（能够用电脑浏览器直接玩的游戏）',

        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ip'],
                ],
                'value' => function ($event) {
                    if (Yii::$app->request->isConsoleRequest) {
                        return '127.0.0.1';
                    }
                    return $this->ip ?: Util::getIpAddr();
                }
            ],

            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],

            [
                'class' => DynamicAttributeBehavior::className(),
                'storageAttribute' => 'type',
                'saveDynamicAttributeDefaults' => true,
                //'allowRandomDynamicAttribute' => true,
                'dynamicAttributeDefaults' => [
                    'type_h5' => 0,
                    'type_client' => 0,
                    'type_app' => 0,
                    'type_box' => 0,
                ],
            ],

            [
                'class' => DynamicAttributeBehavior::className(),
                'storageAttribute' => 'problem',
                'saveDynamicAttributeDefaults' => true,
                //'allowRandomDynamicAttribute' => true,
                'dynamicAttributeDefaults' => [
                    'problem_happy' => 0,
                    'problem_performance' => 0,
                    'problem_content' => 0,
                    'problem_art' => 0,
                ],
            ],

        ];
    }

    public static function getGenderOptionName($key = null)
    {
        $arr = [
            0 => '男',
            1 => '女',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getAgeOptionName($key = null)
    {
        $arr = [
            0 => '18岁及以下',
            1 => '19至22',
            2 => '23至28',
            3 => '29至36',
            4 => '36以上',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getExpenseOptionName($key = null)
    {
        $arr = [
            0 => '几乎没有',
            1 => '25元及以下',
            2 => '25至200（含200）',
            3 => '200以上',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getTypeOptionName($key = null)
    {
        $arr = [
            0 => '网页游戏（能够用电脑浏览器直接玩的游戏）',
            1 => '客户端游戏（需要下载安装程序至电脑上才能使用的游戏）',
            2 => '手机上玩的游戏',
            3 => '游戏主机上玩的游戏（PS4,XBOX,PSP,SWITCH,VR等）',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getProblemOptionName($key = null)
    {
        $arr = [
            0 => '娱乐性不足',
            1 => '品质（视觉，音效，流畅度等）不佳',
            2 => '游戏内容上层次、格调待提升',
            3 => '游戏的美感、艺术性不足',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function ajaxCreate($params)
    {
        /*
        [
            [gender] => 0
            [age] => 0
            [expense] => 0
            [type] => [
                [0] => 0
                [1] => 1
            ]

            [problem] => Array
            (
                [0] => 1
                [1] => 2
            )

        ]
        */
        $model = Vote::findOne(['openid' => $params['openid']]);
        if (null !== $model) {
            return \yii\helpers\Json::encode(['code' => 1, 'msg' => '不能重复投票!']);
        }
        $model = new Vote();
        foreach ($params['type'] as $val) {
            if ($val == 0) {
                $model->type_h5 = 1;
            }
            if ($val == 1) {
                $model->type_client = 1;
            }
            if ($val == 2) {
                $model->type_app = 1;
            }
            if ($val == 3) {
                $model->type_box = 1;
            }
        }
        foreach ($params['problem'] as $val) {
            if ($val == 0) {
                $model->problem_happy = 1;
            }
            if ($val == 1) {
                $model->problem_performance = 1;
            }
            if ($val == 2) {
                $model->problem_content = 1;
            }
            if ($val == 3) {
                $model->problem_art = 1;
            }
        }

        unset($params['type'], $params['problem']);
        $model->load($params, '');
        Yii::error($model->toArray());

        if (!$model->save()) {
            return \yii\helpers\Json::encode(['code' => 1, 'msg' => print_r($model->errors, true)]);
        }
        return \yii\helpers\Json::encode(['code' => 0, 'msg' => 'OK']);
    }

    public static function getStatTotalNumber()
    {
        return Vote::find()->where([])->count() + Vote::FAKE_NUMBER;
    }

    public static function getStatGender($value = 0)
    {
        return Vote::find()->where(['gender' => $value])->count() + ($value ? Vote::FAKE_NUMBER / 4 : Vote::FAKE_NUMBER * 3 / 4);
    }

    public static function getStatAge($value = 0)
    {
        return Vote::find()->where(['age' => $value])->count() + Vote::FAKE_NUMBER / 5;
    }

    public static function getStatExpense($value = 0)
    {
        return Vote::find()->where(['expense' => $value])->count() + Vote::FAKE_NUMBER / 4;
    }

    public static function getStatTypeAndProblem()
    {
        $type_h5 = $type_client = $type_app = $type_box = 0;
        $problem_happy = $problem_performance = $problem_content = $problem_art = 0;

        $models = Vote::find()->all();
        foreach ($models as $model) {
            if ($model->type_h5) {
                $type_h5 += 1;
            }
            if ($model->type_client) {
                $type_client += 1;
            }
            if ($model->type_app) {
                $type_app += 1;
            }
            if ($model->type_box) {
                $type_box += 1;
            }

            if ($model->problem_happy) {
                $problem_happy += 1;
            }
            if ($model->problem_performance) {
                $problem_performance += 1;
            }
            if ($model->problem_content) {
                $problem_content += 1;
            }
            if ($model->problem_art) {
                $problem_art += 1;
            }
        }
        return [
            'type' => [
                $type_h5 + Vote::FAKE_NUMBER, $type_client + Vote::FAKE_NUMBER, $type_app + Vote::FAKE_NUMBER, $type_box + Vote::FAKE_NUMBER,
                'sum' => $type_h5 + $type_client + $type_app + $type_box + 4 * Vote::FAKE_NUMBER,
            ],
            'problem' => [
                $problem_happy + Vote::FAKE_NUMBER, $problem_performance + Vote::FAKE_NUMBER, $problem_content + Vote::FAKE_NUMBER, $problem_art + Vote::FAKE_NUMBER,
                'sum' => $problem_happy + $problem_performance + $problem_content + $problem_art  + 4 * Vote::FAKE_NUMBER,
            ],
        ];
    }



}
