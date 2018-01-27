<?php

use yii\db\Migration;
use yii\db\Schema;

class m161220_094415_create_table_wx_wall_shake extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }
        $this->createTable('wx_wall_shake', [
                'id' => $this->primaryKey(),
                'gh_id' => $this->string(32)->notNull()->defaultValue(''),
                'openid' => $this->string(64)->notNull()->defaultValue(''),
                'number' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'activitduration'=>Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'awardsnumber'=>Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'activityname'=>$this->string(40)->notNull()->defaultValue(''),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
                ]);

    }

    public function down()
    {
         $this->dropTable('wx_wall_shake');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
