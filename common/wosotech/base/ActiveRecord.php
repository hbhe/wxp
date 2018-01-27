<?php

namespace common\wosotech\base;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{

    // Support 'the' preffix, if theAttributeName does not exists, then drop back to try attributeName, for example, theName, theTitle, ...
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\yii\base\UnknownPropertyException $e) {
            if ('the' == substr($name, 0, 3)) {
                return parent::__get(lcfirst(substr($name, 3)));
            }
            throw $e;
        }
    }

    public static function insertAjax($params)
    {
        $className = get_called_class();
        $model = new $className;
        $model->loadDefaultValues();
        if ($model->load($params, '') && $model->save()) {
            return \yii\helpers\Json::encode(['code' => 0, 'msg' => 'OK']);	        
        }      
        yii::error(print_r([__METHOD__, $model->getErrors()], true));
        return \yii\helpers\Json::encode(['code' => 1, 'msg' => '保存错误']);                
    }

    public static function createAjax($params)
    {
        return self::insertAjax($params);        
    }

    public static function readAjax($params)
    {
        $className = get_called_class();
        $model = new $className;
        $model->loadDefaultValues();
        if ($model->load($params, '') && $model->save()) {
            return \yii\helpers\Json::encode(['code' => 0, 'msg' => 'OK']);	        
        }      
        yii::error(print_r([__METHOD__, $model->getErrors()], true));
        return \yii\helpers\Json::encode(['code' => 1, 'msg' => '保存错误']);                
    }

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
