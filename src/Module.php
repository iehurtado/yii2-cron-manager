<?php

namespace gaxz\crontab;

use gaxz\crontab\components\CommandExtractor;

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
     * @var array Commands excluded from list 
     */
    public $excluded = [];

    /**
     * @var array Custom list of commands
     */
    public $commands = [];

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'gaxz\crontab\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->commands)) {
            $this->getCommandsFromSource();
        }
    }

    protected function getCommandsFromSource(): void
    {
        $extractor = new CommandExtractor();

        foreach ((array) $this->source as $class) {
            $this->commands = array_merge($this->commands, $extractor->getCommands($class));
        }
    }
}
