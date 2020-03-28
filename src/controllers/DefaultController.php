<?php

namespace gaxz\crontab\controllers;

use yii\web\Controller;

/**
 * Default controller for the `crontab-manager` module
 */
class DefaultController extends Controller
{
    public $module;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
