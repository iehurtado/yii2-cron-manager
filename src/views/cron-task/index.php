<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel gaxz\crontab\models\CronTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cron Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            'updated_at',
            'schedule',
            'route',
            'is_enabled:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>