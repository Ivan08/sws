<?php

use yii\db\Migration;

/**
 * Class m190709_110924_init
 */
class m190709_110924_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'message' => $this->string(200)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull()->unique(),
            'password' => $this->string(32)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(32)->notNull(),
        ], $tableOptions);
        $this->createTable('follow', [
            'user_from' => $this->integer()->notNull(),
            'user_to' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('post');
        $this->dropTable('user');
        $this->dropTable('follow');
    }
}
