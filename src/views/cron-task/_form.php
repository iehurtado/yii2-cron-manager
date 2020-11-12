<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use gaxz\crontab\Module;

/* @var $this yii\web\View */
/* @var $model gaxz\crontab\models\CronTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cron-task-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxLength' => true, 'placeholder' => Module::t('form', 'Task name')]) ?>
    
    <?= $form->field($model, 'description')->textInput(['placeholder' => Module::t('form', 'Task description')]) ?>

    <?= $form->field($model, 'schedule')->textInput(['maxlength' => true, 'placeholder' => Module::t('form', 'Crontab schedule expression')]) ?>

    <?= $form->field($model, 'route')->dropDownList($routesList, ['prompt' => Module::t('form', 'Select route')]) ?>

    <?= $form->field($model, 'params')->textarea(['rows' => '3', 'placeholder' => Module::t('form', 'Must be a valid json string or empty')]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>