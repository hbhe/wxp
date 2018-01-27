<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%item}}".
 *
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    // 支持嵌套
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = [];

        foreach ($this->resolveFields($fields, $expand) as $field => $definition) {
            $data[$field] = is_string($definition) ? $this->$definition : call_user_func($definition, $this, $field);
        }

        if ($this instanceof Linkable) {
            $data['_links'] = Link::serialize($this->getLinks());
        }

        // start override
        $relations = [];

        // construct relation graph
        foreach ($expand as $relation) {
            if (strstr($relation, '.')) {
                list($relation, $child) = explode('.', $relation);
                $relations[$relation][] = $child;
            } else if (!isset($relations[$relation])) {
                $relations[$relation] = [];
            }
        }

        // serialize relations
        foreach ($relations as $relation => $children) {
            if (isset($data[$relation])) {
                $rel = $data[$relation];
                if (is_array($rel)) {
                    foreach ($rel as $k => $v) {
                        if (is_object($v))
                            $data[$relation][$k] = $v->toArray([], $children);
                    }

                } else if (is_object($rel)) {
                    $data[$relation] = $rel->toArray([], $children);
                } else {
                    $data[$relation] = ArrayHelper::toArray($rel);
                }
            }
        }

        return $data;
    }

}
