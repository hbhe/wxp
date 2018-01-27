<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\models;

use Yii;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * GhidBlameableBehavior automatically fills the specified attributes with the current user ID.
 *
 * To use GhidBlameableBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use common\models\GhidBlameableBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         GhidBlameableBehavior::className(),
 *     ];
 * }
 * ```
 *
 * By default, GhidBlameableBehavior will fill the `created_by` and `updated_by` attributes with the current user ID
 * when the associated AR object is being inserted; it will fill the `updated_by` attribute
 * with the current user ID when the AR object is being updated.
 *
 * Because attribute values will be set automatically by this behavior, they are usually not user input and should therefore
 * not be validated, i.e. `created_by` and `updated_by` should not appear in the [[\yii\base\Model::rules()|rules()]] method of the model.
 *
 * If your attribute names are different, you may configure the [[attribute]] and [[updatedByAttribute]]
 * properties like the following:
 *
 * ```php
 
public function behaviors() {
    return [
        [
            'class' => \common\models\GhidBlameableBehavior::className(),
            'attribute' => 'gh_id',
        ],
    ];
}
 
 * ```
 *
 * @author Luciano Baraglia <luciano.baraglia@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class GhidBlameableBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive current user ID value
     * Set this property to false if you do not want to record the creator ID.
     */
    public $attribute = 'gh_id';

    /**
     * @inheritdoc
     *
     * In case, when the property is `null`, the value of `Yii::$app->user->id` will be used as the value.
     */
    public $value;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->attribute],
            ];
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] property is `null`, the value of `Yii::$app->user->id` will be used as the value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $gh_id = \common\wosotech\Util::getSessionGhid();
            if (!$gh_id) {
                throw new \Exception('gh_id is empty!.');
            }
            return $gh_id;
        }

        return parent::getValue($event);
    }
}
