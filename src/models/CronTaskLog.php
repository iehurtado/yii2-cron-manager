<?php

namespace gaxz\crontab\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_task_log".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int|null $cron_task_id
 * @property string|null $output
 * @property int|null $exit_code
 *
 * @property CronTask $cronTask
 */
class CronTaskLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_task_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['cron_task_id', 'exit_code'], 'integer'],
            [['output'], 'string'],
            [['cron_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => CronTask::className(), 'targetAttribute' => ['cron_task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'cron_task_id' => 'Cron Task ID',
            'output' => 'Output',
            'exit_code' => 'Exit Code',
        ];
    }

    /**
     * Gets query for [[CronTask]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCronTask()
    {
        return $this->hasOne(CronTask::className(), ['id' => 'cron_task_id']);
    }
}
