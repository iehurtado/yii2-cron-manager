<?php

namespace gaxz\crontab\controllers;

use gaxz\crontab\models\CronLine;
use Yii;
use gaxz\crontab\models\CronTask;
use gaxz\crontab\models\CronTaskSearch;
use Symfony\Component\Process\PhpExecutableFinder;
use yii2tech\crontab\CronJob;
use yii2tech\crontab\CronTab;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CronTaskController implements the CRUD actions for CronTask model.
 */
class CronTaskController extends Controller
{
    /**
     * @var \gaxz\crontab\Module
     */
    public $module;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CronTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CronTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'routesList' => $this->module->routes,
        ]);
    }

    /**
     * Displays a single CronTask model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'routesList' => $this->module->routes,
        ]);
    }

    /**
     * Creates a new CronTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CronTask();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->is_enabled) {
                $cronJob = $this->createCronJob($model);

                $this->getCrontab()
                    ->setJobs([$cronJob])
                    ->apply();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'routesList' => $this->module->routes
        ]);
    }

    /**
     * Updates an existing CronTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'routesList' => $this->module->routes,
        ]);
    }

    /**
     * Deletes an existing CronTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CronTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CronTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CronTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Get crontab instance
     */
    protected function getCrontab(): CronTab
    {
        $crontab = new CronTab();
        $crontab->headLines = $this->module->getCrontabHeader();

        if (!empty($this->module->username)) {
            $crontab->username = $this->module->crontabUsername;
        }

        return $crontab;
    }

    /**
     * Transform CronTask to CronJob
     * @param CronTask $model
     */
    protected function createCronJob(CronTask $model): CronJob
    {
        return new CronJob([
            'line' => $model->getLine(
                $this->module->phpBin,
                $this->module->getYiiBootstrap(),
                $this->module->getExecRoute(),
                $this->module->outputSetting,
            )
        ]);
    }
}
