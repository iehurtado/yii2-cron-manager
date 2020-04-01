<?php

namespace gaxz\crontab\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cron_task".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $schedule
 * @property string|null $command
 * @property string|null $options
 * @property int|null $is_enabled
 */
class CronTask extends ActiveRecord
{
    const SCHEDULE_REGEX = "/^((?:[1-9]?\d|\*)\s*(?:(?:[\/-][1-9]?\d)|(?:,[1-9]?\d)+)?\s*){5}$/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_enabled'], 'integer'],
            [['options'], 'validateJson'],
            [['schedule', 'command'], 'string', 'max' => 255],
            [['schedule'], 'match', 'pattern' => self::SCHEDULE_REGEX],
            [['schedule', 'command'], 'required'],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            'updated_at' => 'Updated At',
            'schedule' => 'Schedule',
            'command' => 'Command',
            'options' => 'Options',
            'is_enabled' => 'Is Enabled',
        ];
    }

    public function validateJson($attribute)
    {
        if (empty($this->$attribute) || json_decode($this->$attribute)) {
            return true;
        }

        $this->addError($attribute, 'The field must contain a valid JSON string');
        return false;
    }
}
