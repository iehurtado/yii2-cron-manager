<?php

namespace gaxz\crontab\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cron_task_log}}`.
 */
class m200403_055706_create_cron_task_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cron_task_log}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime(),
            'cron_task_id' => $this->integer(),
            'output' => $this->text(),
            'exit_code' => $this->integer(),
        ]);

        $this->createIndex('idx-cron_task_log-cron_task_id', '{{%cron_task_log}}', 'cron_task_id');
        $this->addForeignKey(
            'fk-cron_task_id-cron_task-id',
            '{{%cron_task_log}}',
            'cron_task_id',
            '{{%cron_task}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cron_task_id-cron_task-id', '{{%cron_task_log}}');
        $this->dropIndex('idx-cron_task_log-cron_task_id', '{{%cron_task_log}}');

        $this->dropTable('{{%cron_task_log}}');
    }
}
