<?php

namespace gaxz\crontab\commands;

use gaxz\crontab\models\CronTask;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Undocumented class
 */
class ExecController extends Controller
{
    public function actionIndex($id)
    {
        $model = CronTask::findOne($id);

        if (empty($model)) {
            return ExitCode::DATAERR;
        }

        $this->run('/' . $model->route, [$model->params]);

        return ExitCode::OK;
    }
}
