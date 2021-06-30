<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%users_chats}}`.
 */
class m210630_093235_create_chats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chats}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_delete' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->insert('chats',[
            'name' => 'Test chat',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_chats}}');
    }
}
