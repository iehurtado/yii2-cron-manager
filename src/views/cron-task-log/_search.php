<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTaskLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cron-task-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'cron_task_id') ?>

    <?= $form->field($model, 'output') ?>

    <?= $form->field($model, 'exit_code') ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('main', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('main', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
