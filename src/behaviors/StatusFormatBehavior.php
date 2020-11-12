<?php

namespace gaxz\crontab\behaviors;

use yii\base\Behavior;
use gaxz\crontab\Module;

class StatusFormatBehavior extends Behavior 
{
    public function asStatus ($value) 
    {
        $text = ($value ? 'Enabled' : 'Disabled');
        return Module::t('main', $text);
    }
}
