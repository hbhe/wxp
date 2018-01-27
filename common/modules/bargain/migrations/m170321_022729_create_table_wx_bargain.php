<?php
// php yii migrate --migrationPath=@common/modules/bargain/migrations
// php yii seed/bargain jmdx
use yii\db\Migration;

class m170321_022729_create_table_wx_bargain extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=myisam';
        }

        $this->createTable('wx_activity', [
            'id' => $this->primaryKey()->comment('活动ID'),
            'sid' => $this->string(32),
            'holiday' => $this->Integer()->notNull()->defaultValue(0)->comment('节日'), // 0:全部, 1:重阳节, ...
            'category' => $this->Integer()->notNull()->defaultValue(0)->comment('类型'), // 0: 全部, 1:常规抽奖, ...
            'title' => $this->string(128)->notNull()->defaultValue('')->comment('活动标题'),
            'detail' => $this->text()->comment('活动说明'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'), // 0:已下架, 1:已上架
            'logo_id' => $this->Integer()->notNull()->defaultValue(0)->comment('活动图片'), // 活动image id
            'sort_order' => $this->integer()->notNull()->comment('排序'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->createTable('wx_bargain_topic', [
            'id' => $this->primaryKey()->comment('公众号创建的活动'), // 客户(即公众号)根据activity_id创建的活动
            'activity_id' => $this->Integer()->notNull()->defaultValue(0)->comment('活动类型ID'),
            'gh_id' => $this->string(32)->notNull()->defaultValue('')->comment('公众号ID'),
            'title' => $this->string(128)->notNull()->defaultValue('')->comment('活动标题,如元旦有礼'),
            'detail' => $this->text()->comment('活动说明'),
            'start_time' => $this->timestamp()->defaultValue(null)->comment('活动开始时间'),
            'end_time' => $this->timestamp()->defaultValue(null)->comment('活动结束时间'),
            'params' => $this->text()->comment('活动参数'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'), // 0:未开始, 1:进行中, 2:暂停, 3:已结束,
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('gh_id', 'wx_bargain_topic', ['gh_id']);
        $this->createIndex('activity_id', 'wx_bargain_topic', ['activity_id']);

        $this->createTable('wx_bargain_item', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(32)->notNull()->defaultValue('')->comment('公众号ID'),
            'topic_id' => $this->Integer()->notNull()->defaultValue(0)->comment('活动ID'),
            'cat' => $this->string(64)->notNull()->defaultValue('')->comment('类型'), // 0:商品, 1:优惠券
            'title' => $this->string(64)->notNull()->defaultValue('')->comment('商品名称'),
            'detail' => $this->string()->notNull()->defaultValue('')->comment('商品详情'),
            'price' => $this->Integer()->notNull()->defaultValue(0)->comment('商品价格'),
            'image_id' => $this->Integer()->notNull()->defaultValue(0)->comment('商品图片'), // 活动image id, 可计算image_url
            'params' => $this->text()->comment('参数'),
            'sort' => $this->Integer()->notNull()->defaultValue(0)->comment('排序'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'remark' => $this->text()->comment('备注'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('gh_id', 'wx_bargain_item', ['gh_id']);
        $this->createIndex('topic_id', 'wx_bargain_item', ['topic_id']);

        $this->createTable('wx_bargain_post', [
            'id' => $this->primaryKey()->comment('发起者创建的活动'),
            'gh_id' => $this->string(32)->notNull()->defaultValue('')->comment('公众号ID'),
            'topic_id' => $this->Integer()->notNull()->defaultValue(0)->comment('活动ID'),
            'openid' => $this->string(64)->notNull()->defaultValue('')->comment('活动创建者openid'),
            'name' => $this->string(11)->notNull()->defaultValue('')->comment(''),
            'phone' => $this->string(11)->notNull()->defaultValue('')->comment('手机号码'),
            'item_id' => $this->Integer()->notNull()->defaultValue(0)->comment('活动创建者挑选的商品ID'),
            'rest_price' => $this->Integer()->notNull()->defaultValue(0)->comment('商品剩余价格'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'ip' => $this->string(128)->notNull()->defaultValue('')->comment('IP'),
            'longitude' => $this->decimal(10,6)->comment('经度'),
            'latitude' => $this->decimal(10,6)->comment('经度'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('gh_id', 'wx_bargain_post', ['gh_id']);
        $this->createIndex('openid', 'wx_bargain_post', ['openid']);
        $this->createIndex('topic_id', 'wx_bargain_post', ['topic_id']);

        $this->createTable('wx_bargain_comment', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(32)->notNull()->defaultValue('')->comment('公众号ID'),
            'openid' => $this->string(64)->notNull()->defaultValue('')->comment('砍价者openid'),
            'post_id' => $this->Integer()->notNull()->defaultValue(0)->comment('发起者创建的活动ID'),
            'bargain_price' => $this->Integer()->notNull()->defaultValue(0)->comment('砍价的金额'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'longitude' => $this->decimal(10,6)->comment('经度'),
            'latitude' => $this->decimal(10,6)->comment('经度'),
            'ip' => $this->string(128)->notNull()->defaultValue('')->comment('IP'),
            'nickname' => $this->string(64)->comment('砍价者微信昵称'),
            'sex' => $this->smallInteger(),
            'city' => $this->string(32),
            'country' => $this->string(32),
            'province' => $this->string(32),
            'headimgurl' => $this->string(512),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('gh_id', 'wx_bargain_comment', ['gh_id']);
        $this->createIndex('openid', 'wx_bargain_comment', ['openid']);
    }

    public function down()
    {
        $this->dropTable('wx_activity');
        $this->dropTable('wx_bargain_topic');
        $this->dropTable('wx_bargain_item');
        $this->dropTable('wx_bargain_post');
        $this->dropTable('wx_bargain_comment');
    }

}
