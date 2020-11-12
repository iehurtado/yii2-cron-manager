<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $searchModel gaxz\crontab\models\CronTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Cron Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('main', 'Create Cron Task'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('main', 'Logs'), ['cron-task-log/index'], ['class' => 'btn btn-success']) ?>

    </p>

    <div class="grid-wrapper">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['style' => 'width:5%'],
                ],
                [
                    'attribute' => 'name',
                    'headerOptions' => ['style' => 'width:14%']
                ],
                [
                    'attribute' => 'description'
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'datetime',
                    'filter' => false,
                    'headerOptions' => ['style' => 'width:14%'],
                ],
                [
                    'attribute' => 'schedule',
                    'headerOptions' => ['style' => 'width:10%'],

                ],
                [
                    'attribute' => 'route',
                    'value' => function ($model) use ($routesList) {
                        return !empty($routesList[$model->route]) ? $routesList[$model->route] : $model->route;
                    },
                    'filter' => $routesList
                ],
                [
                    'attribute' => 'latestLog.exit_code',
                    'label' => Module::t('model', 'Latest Result'),
                    'format' => 'exitcode'
                ],
                [
                    'attribute' => 'is_enabled',
                    'label' => Module::t('model', 'Status'),
                    'format' => 'status',
                    'filter' => [
                        0 => Module::t('main', 'Disabled'), 
                        1 => Module::t('main', 'Enabled')
                    ],
                    'headerOptions' => ['style' => 'width:10%'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:10%'],
                    'template' => '{run} {changeStatus} {view} {update} {delete}',
                    'buttons' => [
                        'run' => function ($url, $model, $key) {
                            return Html::a(
                                Html::tag('span', '', [
                                    'class' => 'glyphicon glyphicon-console',
                                    'title' => Module::t('main', 'Execute'),
                                    'aria' => ['hidden' => true]
                                ]),
                                Url::to(['execute', 'id' => $model->id])
                            );
                        },
                        'changeStatus' => function ($url, $model, $key) {
                            if (!$model->is_enabled) {
                                return Html::a(
                                    Html::tag('span', '', [
                                        'class' => 'glyphicon glyphicon-play',
                                        'title' => Module::t('main', 'Enable'),
                                        'aria' => ['hidden' => true]
                                    ]),
                                    Url::to(['change-status', 'id' => $model->id])
                                );
                            }

                            return Html::a(
                                Html::tag('span', '', [
                                    'class' => 'glyphicon glyphicon-stop',
                                    'title' => Module::t('main', 'Disable'),
                                    'aria' => ['hidden' => true]
                                ]),
                                Url::to(['change-status', 'id' => $model->id])
                            );
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                Html::tag('span', '', [
                                    'class' => 'glyphicon glyphicon-list',
                                    'title' => Module::t('main', 'Logs'),
                                    'aria' => ['hidden' => true]
                                ]),
                                Url::to(['view', 'id' => $model->id])
                            );
                        }
                    ],

                ],
            ],
        ]); ?>
    </div>

</div>