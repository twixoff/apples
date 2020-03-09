<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m200309_084346_create_apples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(25)->notNull(),
            'weight' => $this->integer()->notNull()->defaultValue(100),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'date_created' => $this->integer()->notNull(),
            'date_fallen' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apples}}');
    }
}
