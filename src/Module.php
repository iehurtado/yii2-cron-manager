<?php

namespace gaxz\crontab;

use gaxz\crontab\components\RouteExtractor;

/**
 * Crontab manager module
 * 
 * This class provides settings and handles operations related to settings.
 * It forms list of routes based on $source and $excluded attributes or just uses $routes if it's not empty.
 * Handles paths like php binary file and path to yii bootstrap
 * and settings of crontab like headers, username, console output etc.
 */
class Module extends \yii\base\Module
{
    /**
     * @var string|array Namespaces with classnames of console controllers.
     * Controllers will be parsed and actions will form list of routes.  
     */
    public $source;

    /**
     * @var array Console command routes excluded from list.
     * It's important to specify absolute console route:
     * E.g. to exclude app/commands/HelloController::actionIndex() $excluded must contain '/hello/index'
     */
    public $excluded = [];

    /**
     * @var array This is a list of valid routes that will be available in gui.
     * It can be filled manually or @see $source and $excluded
     */
    public $routes = [];

    /**
     * @var string Unix command for handling output
     * @example ">> /var/log/app.log" will form the line: * * * * * * php yii /crontab/exec 1 >> /var/log/app.log
     * Which will write execution output to the file  
     */
    public $outputSetting;

    /**
     * This will work only in case PHP script is running from privileged user (e.g. 'root')
     * @var string Crontab user to apply
     */
    public $crontabUsername;

    /**
     * @var string Path to yii bootstrap file
     */
    public $yiiBootstrapAlias = '@app/yii';

    /**
     * @var string Path to php binary
     * In some cases predefined constants might return wrong path to binary file 
     * and it could be useful to override it through module config
     */
    public $phpBin = PHP_BINDIR . "/php";

    /**
     * Crontab header message to merge with $headlines
     * @see Crontab::$headLines
     * @var array
     */
    public $headerMessage = [
        "# The content below is generated by crontab module of yii2 application.",
        "# Editing these lines manually will break synchronization between cron and application."
    ];

    /**
     * Crontab settings that will appear after $headerMessage
     * @see Crontab::$headLines
     * @var array
     */
    public $headLines = [];

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
     * On init: Change controller if called from console and form list of routes.
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
     * Absolute console route to execution script (gaxz\crontab\commands\ExecController)
     * @return string
     */
    public function getExecRoute(): string
    {
        return "/{$this->id}/exec";
    }

    /**
     * Path to yii2 bootstrap file
     * @return string
     */
    public function getYiiBootstrap(): string
    {
        return \Yii::getAlias($this->yiiBootstrapAlias);
    }

    /**
     * Crontab headers
     * @see Crontab::$headlines
     * @return array
     */
    public function getCrontabHeader(): array
    {
        return array_merge($this->headerMessage, $this->headLines);
    }
}
