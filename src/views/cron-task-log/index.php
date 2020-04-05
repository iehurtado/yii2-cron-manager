<?php

use yii\console\ExitCode;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel gaxz\crontab\models\CronTaskLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Task Logs';
$this->params['breadcrumbs'][] = ['label' => 'Cron Tasks', 'url' => ['cron-task/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Cron Tasks', ['cron-task/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'created_at',
                'headerOptions' => ['style' => 'width:14%'],
            ],
            [
                'attribute' => 'cron_task_id',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->cronTask)) {
                        return Html::a($model->cronTask->id, ['cron-task/view', 'id' => $model->cron_task_id]);
                    }

                    return $model->cron_task_id;
                }
            ],
            [
                'attribute' => 'output',
                'format' => 'ntext',
                'headerOptions' => ['style' => 'width:50%'],
            ],
            [
                'attribute' => 'exit_code',
                'value' => function ($model) {
                    return ExitCode::getReason($model->exit_code);
                },
                'filter' => ExitCode::$reasons
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open" title="View" aria-hidden="true"></span>',
                            Url::to(['cron-task-log/view', 'id' => $model->id])
                        );
                    }
                ]
            ],
        ],
    ]); ?>

</div>