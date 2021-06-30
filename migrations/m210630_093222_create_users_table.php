<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m210630_093222_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'auth_type' => $this->integer()->notNull()->defaultValue(0),
            'is_delete' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->insert('users',[
            'name' => 'Robert',
            'username' => 'robert',
            'password' => Yii::$app->security->generatePasswordHash('robert'),
            'auth_type' => 1,
            'auth_key'=>Yii::$app->security->generateRandomString()
        ]);

        $this->insert('users',[
            'name' => 'Bobert',
            'username' => 'bobert',
            'password' => Yii::$app->security->generatePasswordHash('bobert'),
            'auth_type' => 1,
            'auth_key'=>Yii::$app->security->generateRandomString()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
