<?php

use yii\console\ExitCode;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */

$this->title = $model->getPrettyName();
    
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Cron Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-view">

    <h1><?= Html::encode($this->title) ?></h1>
        
    <p>

        <?= Html::a(Module::t('main', 'Execute'), ['execute', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('main', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('main', ($model->is_enabled ? 'Stop' : 'Enable')), ['change-status', 'id' => $model->id], [
            'class' => 'btn btn-warning'
        ]) ?>
        <?= Html::a(Module::t('main', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('main', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            'created_at:datetime',
            'updated_at:datetime',
            'schedule',
            'route',
            'is_enabled:boolean',
        ],
    ]) ?>


    <h1>Logs</h1>

    <?= GridView::widget([
        'dataProvider' => $logDataProvider,
        'filterModel' => $logSearchModel,
        'columns' => [
            'id',
            'created_at:datetime',
            'output:ntext',
            [
                'attribute' => 'exit_code',
                'format' => 'exitcode',
                'filter' => ExitCode::$reasons
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            Html::tag('span', '', [
                                'class' => 'glyphicon glyphicon-eye-open',
                                'title' => Module::t('main', 'View'),
                                'aria' => ['hidden' => true]
                            ]),
                            Url::to(['cron-task-log/view', 'id' => $model->id])
                        );
                    }
                ]
            ],
        ],
    ]); ?>

</div>