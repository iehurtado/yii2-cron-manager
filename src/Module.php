<?php

namespace gaxz\crontab;

use gaxz\crontab\components\RouteExtractor;

/**
 * Crontab manager module class
 */
class Module extends \yii\base\Module
{
    /**
     * @var string|array Namespaces with classnames of console controllers
     */
    public $source;

    /**
     * @var array Routes excluded from list 
     */
    public $excluded = [];

    /**
     * @var array Custom list of routes to console commands
     */
    public $routes = [];

    /**
     * This will work only in case PHP script is running from privileged user (e.g. 'root')
     * @var string Crontab user to apply
     */
    public $defaultUsername;

    /**
     * @var string Path to yii bootstrap file
     */
    public $yiiBootstrapAlias = '@app/yii';

    /**
     * @var string Path to php binary
     */
    public $phpBin = 'php';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'gaxz\crontab\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'cron-task';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'gaxz\crontab\commands';
        }

        if (empty($this->routes)) {
            $this->getRouteFromSource();
        }
    }

    /**
     * Populate routes from list of sources
     * @return void
     */
    protected function getRouteFromSource(): void
    {
        $extractor = new RouteExtractor();

        foreach ((array) $this->source as $class) {
            $this->routes = array_merge($this->routes, $extractor->getRoutes($class));
        }
    }

    /**
     * @return string Console route to execution script (gaxz\crontab\commands\ExecController)
     */
    public function getExecRoute(): string
    {
        return "/{$this->id}/exec";
    }

    /**
     * Path to yii2 bootstrap file
     */
    public function getYiiBootstrap(): string
    {
        return \Yii::getAlias($this->yiiBootstrapAlias);
    }
}
