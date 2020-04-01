<?php

use gaxz\crontab\Asset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Asset::register($this);

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cron-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'schedule')->textInput(['maxlength' => true, 'placeholder' => 'Crontab schedule expression']) ?>

    <?= $form->field($model, 'command')->dropDownList($commandList, ['prompt' => 'Select command']) ?>

    <?= $form->field($model, 'options')->textarea(['rows' => '3', 'placeholder' => 'Must be a valid json string or empty']) ?>

    <?= $form->field($model, 'is_enabled')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>