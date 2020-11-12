<?php

namespace gaxz\crontab\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use gaxz\crontab\Module;

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
            'id' => Module::t('model', 'ID'),
            'created_at' => Module::t('model', 'Executed At'),
            'cron_task_id' => Module::t('model', 'Cron Task ID'),
            'output' => Module::t('model', 'Output'),
            'exit_code' => Module::t('model', 'Exit Code'),
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
