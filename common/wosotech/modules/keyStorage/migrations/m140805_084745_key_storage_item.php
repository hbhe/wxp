<?php

use yii\db\Schema;
use yii\db\Migration;

//php yii migrate/up --migrationPath=@common/wosotech/modules/keyStorage/migrations
//php yii migrate/down --migrationPath=@common/wosotech/modules/keyStorage/migrations
class m140805_084745_key_storage_item extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8';
        }

        $this->createTable('{{%key_storage_item}}', [
            'key' => $this->string(128)->notNull(),
            'value' => $this->text()->notNull(),
            'comment' => $this->text(),
            'updated_at'=>$this->integer(),
            'created_at'=>$this->integer()
        ], $tableOptions);

        $this->addPrimaryKey('pk_key_storage_item_key', '{{%key_storage_item}}', 'key');
        $this->createIndex('idx_key_storage_item_key', '{{%key_storage_item}}', 'key', true);
    }

    public function down()
    {
        $this->dropTable('{{%key_storage_item}}');
    }
}
