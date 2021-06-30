<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%chat_messages}}`.
 */
class m210630_093301_create_chat_members_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_members}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->integer()->notNull()->defaultValue(0),
            'is_delete' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            '{{%idx-members-users}}',
            '{{%chat_members}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-members-users}}',
            '{{%chat_members}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-members-chats}}',
            '{{%chat_members}}',
            'chat_id'
        );

        $this->addForeignKey(
            '{{%fk-members-chats}}',
            '{{%chat_members}}',
            'chat_id',
            '{{%chats}}',
            'id',
            'CASCADE'
        );

        $this->insert('chat_members',[
            'chat_id' => 1,
            'user_id' => 1,
            'role' => 0,
        ]);

        $this->insert('chat_members',[
            'chat_id' => 1,
            'user_id' => 2,
            'role' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_members}}');
    }
}
