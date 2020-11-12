<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cron-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>
    
    <?= $form->field($model, 'name') ?>
    
    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?= $form->field($model, 'schedule') ?>

    <?= $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'is_enabled') 
    ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('main', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('main', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>