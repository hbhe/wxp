<?php

use yii\db\Migration;

class m170206_081754_modify_rbac extends Migration
{
    public function up()
    {        
        $this->addColumn('{{%user}}', 'password_reset_token', $this->string()->unique());
        $this->addColumn('{{%user}}', 'gh_id', $this->string(32));
        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'password_reset_token');
        $this->dropColumn('{{%user}}', 'gh_id');
    }

}
