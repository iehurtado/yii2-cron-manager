<?php

use yii\helpers\Html;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */

$this->title = Module::t('main', 'Update {name}', ['name' => $model->getPrettyName()]);

$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Cron Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('main', 'Update');
?>
<div class="cron-task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'routesList' => $routesList,
    ]) ?>

</div>