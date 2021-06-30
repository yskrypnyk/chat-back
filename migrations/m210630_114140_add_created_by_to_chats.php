<?php

use yii\db\Migration;

/**
 * Class m210630_114140_add_created_by_to_chats
 */
class m210630_114140_add_created_by_to_chats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chats','created_by',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210630_114140_add_created_by_to_chats cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210630_114140_add_created_by_to_chats cannot be reverted.\n";

        return false;
    }
    */
}
