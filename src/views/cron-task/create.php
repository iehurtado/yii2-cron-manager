<?php

use yii\helpers\Html;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */

$this->title = Module::t('main', 'Create Cron Task');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Cron Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'routesList' => $routesList,
    ]) ?>

</div>