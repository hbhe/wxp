<?php

namespace common\modules\policy\models;

/**
 * This is the ActiveQuery class for [[SjPolicy]].
 *
 * @see SjPolicy
 */
class SjPolicyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SjPolicy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SjPolicy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
