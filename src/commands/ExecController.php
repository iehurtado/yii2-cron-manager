<?php

namespace gaxz\crontab\commands;

use gaxz\crontab\models\CronTask;
use gaxz\crontab\models\CronTaskLog;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Every crontab line of this module is being executed through index method of this class.
 */
class ExecController extends Controller
{
    /**
     * @var \gaxz\crontab\Module
     */
    public $module;

    /**
     * This method takes a CronTask::$id as a parameter finds a model and runs a controller action.
     * Each run is being logged to database via CronTaskLog model or if it's set, sending it to console output.
     * Console output may be handled
     * @see \gaxz\crontab\Module $outputSetting
     *   
     * @param integer $id CronTask::$id
     * @return integer
     */
    public function actionIndex($id)
    {
        $model = CronTask::findOne($id);

        if (empty($model)) {
            echo "CronTask ID:{$id} not found" . PHP_EOL;
            return ExitCode::DATAERR;
        }

        ob_start();

        try {
            $code = $this->run($model->route, [$model->params]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: null;
            echo $e;
        }

        $output = ob_get_clean();

        if (!empty($this->module->outputSetting)) {
            echo $output . PHP_EOL;
        }

        $log = new CronTaskLog([
            'cron_task_id' => $model->id,
            'output' => $output,
            'exit_code' => is_integer($code) ? $code : null,
        ]);

        if (!$log->save()) {
            echo "Unable to create CronTaskLog for task ID:{$model->id}" . PHP_EOL;
            return ExitCode::DATAERR;
        }

        return ExitCode::OK;
    }
}
