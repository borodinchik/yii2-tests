<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180710_084009_tasks
 */
class m180710_084009_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    /*public function safeUp()
    {

    }*/

    /**
     * {@inheritdoc}
     */
    /*public function safeDown()
    {
        echo "m180710_084009_tasks cannot be reverted.\n";

        return false;
    }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->null(),
            'description' => $this->text()->null(),
            'date_creation' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'date_start' => $this->dateTime(),
            'image' => $this->string()->null(),
            'status' => $this->tinyInteger()->defaultValue(0)
        ]);
    }

    public function down()
    {
        $this->dropTable('tasks');
    }

}
