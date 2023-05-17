<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m230516_090727_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color_index' => $this->tinyInteger()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'eaten_fraction' => $this->decimal(2, 2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'fell_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
