<?php

namespace gaxz\crontab\controllers;

use Yii;
use gaxz\crontab\models\CronTask;
use gaxz\crontab\models\CronTaskLogSearch;
use gaxz\crontab\models\CronTaskSearch;
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
        $logSearchModel = new CronTaskLogSearch(['cron_task_id' => $id]);
        $logDataProvider = $logSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'logDataProvider' => $logDataProvider,
            'logSearchModel' => $logSearchModel,
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

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'routesList' => $this->module->routes
        ]);
    }

    /**
     * Updates an existing CronTask model.
     * If update is successful and model is enabled, removes old task and replaces with a new one.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $crontab = $this->getCrontab();
        $oldCronJob = $this->createCronJob($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->is_enabled) {
                $crontab->setJobs([$oldCronJob]);
                $crontab->remove();

                $crontab->setJobs([$this->createCronJob($model)]);
                $crontab->apply();

                Yii::$app->session->setFlash('success', 'Crontab has been updated');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'routesList' => $this->module->routes,
        ]);
    }

    /**
     * Deletes an existing CronTask model.
     * If deletion is successful, removes the line from crontab.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $crontab = $this->getCrontab();
        $cronJob = $this->createCronJob($model);

        if ($model->delete()) {
            $crontab->setJobs([$cronJob])->remove();
            \Yii::$app->session->setFlash('success', 'Crontab has been updated');
        } else {
            \Yii::$app->session->setFlash('error', 'Unable to delete CronTask');
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates is_enabled attribute and crontab file
     * @param integer $id
     * @return mixed
     */
    public function actionChangeStatus($id)
    {
        $model = $this->findModel($id);

        $model->is_enabled = $model->is_enabled ? 0 : 1;

        if (!$model->save(false)) {
            \Yii::$app->session->setFlash('error', 'Unable to update status');
        }

        $cronJob = $this->createCronJob($model);
        $crontab = $this->getCrontab()->setJobs([$cronJob]);

        if ($model->is_enabled) {
            $crontab->apply();
        } else {
            $crontab->remove();
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * Runs CronTask execution in console and redirects to view page
     * @param integer $id
     * @return mixed
     */
    public function actionExecute($id)
    {
        $model = $this->findModel($id);

        $cronJob = $this->createCronJob($model);

        shell_exec(escapeshellcmd($cronJob->command));

        $this->redirect(['view', 'id' => $id]);
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
     * Creates crontab instance
     * @return CronTab
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
     * Transform CronTask to CronJob using module settings
     * @param CronTask $model
     * @return CronJob
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
