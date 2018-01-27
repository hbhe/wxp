<?php

use yii\db\Migration;

class m170612_014741_create_department_tables extends Migration
{
    public function up()
    {
        if (Yii::$app->db->schema->getTableSchema('wx_department') !== null) {
            $this->dropTable('wx_department');
        }

        if (Yii::$app->db->schema->getTableSchema('wx_department_employee') !== null) {
            $this->dropTable('wx_department_employee');
        }

        $this->createTable('wx_department', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer()->notNull()->comment('父结点id'),
            'sid' => $this->string(32),
            'gh_id' => $this->string(64)->notNull(),
            'title' => $this->string()->comment('名称'),
            'detail' => $this->text()->comment('简介'),
            'address' => $this->string()->comment('地址'),
            'longitude' => $this->double()->comment('经度'),
            'latitude' => $this->double()->comment('纬度'),
            'offset_type' => $this->smallInteger()->defaultValue(1)->comment('坐标类型'),
            'telephone' => $this->string()->comment('联系电话'),
            'open_time' => $this->string()->comment('营业时间'),
            'is_public' => $this->smallInteger()->notNull()->defaultValue(1)->comment('对外部门'),
            'is_visible' => $this->smallInteger()->notNull()->defaultValue(1)->comment('是否上线展示'),
            'is_self_operated' => $this->boolean()->notNull()->defaultValue(0)->comment('是否公众号自营'),
            'sort_order' => $this->integer()->notNull()->comment('排序'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->createIndex('sid', 'wx_department', ['sid'], true);
        $this->createIndex('gh_id', 'wx_department', ['gh_id']);
        $this->createIndex('pid', 'wx_department', ['pid']);

        $this->createTable('wx_employee', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(64)->notNull(),
            'name' => $this->string()->comment('员工姓名'),
            'mobile' => $this->string(16)->notNull()->comment('员工手机号'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->createIndex('gh_id', 'wx_employee', ['gh_id']);
        $this->createIndex('mobile', 'wx_employee', ['mobile']);

        $this->createTable('wx_department_employee', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(64)->notNull(),
            'department_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'role' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->createIndex('gh_id', 'wx_department_employee', ['gh_id']);
        $this->createIndex('department_id_employee_id', 'wx_department_employee', ['department_id', 'employee_id'] , true);
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('wx_department') !== null) {
            $this->dropTable('wx_department');
        }

        if (Yii::$app->db->schema->getTableSchema('wx_employee') !== null) {
            $this->dropTable('wx_employee');
        }

        if (Yii::$app->db->schema->getTableSchema('wx_department_employee') !== null) {
            $this->dropTable('wx_department_employee');
        }
    }

}
