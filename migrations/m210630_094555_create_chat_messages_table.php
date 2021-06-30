<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%chat_messages}}`.
 */
class m210630_094555_create_chat_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_messages}}', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'is_delete' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            '{{%idx-messages-members}}',
            '{{%chat_messages}}',
            'member_id'
        );

        $this->addForeignKey(
            '{{%fk-messages-members}}',
            '{{%chat_messages}}',
            'member_id',
            '{{%chat_members}}',
            'id',
            'CASCADE'
        );

        $this->insert('chat_messages',[
            'member_id' => 1,
            'message' => '1) Hello, Bobert',
        ]);

        $this->insert('chat_messages',[
            'member_id' => 2,
            'message' => '2) Hello, Robert',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_messages}}');
    }
}
