<?php

namespace gaxz\crontab\commands;

use gaxz\crontab\models\CronTask;
use gaxz\crontab\models\CronTaskLog;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Undocumented class
 */
class ExecController extends Controller
{
    /**
     * @var \gaxz\crontab\Module
     */
    public $module;

    public function actionIndex($id)
    {
        $model = CronTask::findOne($id);

        if (empty($model)) {
            echo "CronTask ID:{$id} not found" . PHP_EOL;
            return ExitCode::DATAERR;
        }

        ob_start();
        $code = $this->run($model->route, [$model->params]);
        $output = ob_get_clean();

        $log = new CronTaskLog([
            'cron_task_id' => $model->id,
            'output' => $output,
            'exit_code' => is_integer($code) ? $code : null,
        ]);

        if (!empty($this->module->outputSetting)) {
            echo $output . PHP_EOL;
        }

        if (!$log->save()) {
            echo "Unable to create CronTaskLog for task ID:{$model->id}" . PHP_EOL;
            return ExitCode::DATAERR;
        }

        return ExitCode::OK;
    }
}
