<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180710_084031_tasks
 */
class m180710_084031_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'date_creation' => $this->timestamp(),
            'date_start' => $this->dateTime(),
            'image' => $this->string()->null(),
            'status' => $this->tinyInteger()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180710_084031_tasks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180710_084031_tasks cannot be reverted.\n";

        return false;
    }
    */
}
