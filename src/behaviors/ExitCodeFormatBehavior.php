<?php

namespace gaxz\crontab\behaviors;

use yii\base\Behavior;
use yii\console\ExitCode;

class ExitCodeFormatBehavior extends Behavior
{
    public function asExitcode($value) 
    {
        if ($value === null) {
            return $this->owner->nullDisplay;
        }
        return ExitCode::getReason($value);
    }
}
