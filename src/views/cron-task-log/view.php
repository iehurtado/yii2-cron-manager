<?php

use yii\console\ExitCode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTaskLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cron Tasks', 'url' => ['cron-task/index']];
$this->params['breadcrumbs'][] = ['label' => 'Cron Task Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            [
                'attribute' => 'cron_task_id',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->cronTask)) {
                        return Html::a($model->cronTask->route, ['cron-task/view', 'id' => $model->cron_task_id]);
                    }

                    return $model->cron_task_id;
                }
            ],
            'output:ntext',
            [
                'attribute' => 'exit_code',
                'value' => function ($model) {
                    return ExitCode::getReason($model->exit_code);
                },
            ],
        ],
    ]) ?>

</div>