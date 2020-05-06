# Cron Manager module for Yii2 framework
This is a simple and flexible module for creating\updating background tasks of cron application. It is designed to work with yii2 console controllers and description provides some advice to work with them. 

# Installation
Via composer

    composer require --prefer-dist gaxz/yii2-cron-manager
or add to your composer.json

    "gaxz/yii2-cron-manager": "*"
and

    composer install 

Add the code below to your web and console config files:

    'modules' => [
	    'crontab' => [
	    	'class' => 'gaxz\crontab\Module'
	    ],
    ]
And finally

    php yii migrate --migrationNamespaces=gaxz\\crontab\\migrations

To apply migrations via docker you might need to replace double backslashes with single e.g: --migrationNamespaces=gaxz\crontab\migrations.

Also make sure that cron is installed and running.

# Configuration
Module requires a list of routes to yii2 console controllers. 
The list is used in gui to set up background tasks and form crontab lines.
You can specify this by passing the controller classname with namespace into config file:


    'source' => 'app\commands\HelloController' 
or an array

    'source' => [
	    'console\controllers\EmailController',
	    'console\controllers\UserController',
    ],
Module will parse controllers and form list of routes.
Excluding actions from list if needed:

    'exclude' => [
    	'/email/send'
    ],

or you can specify routes manually

    'routes' => [
	    '/console/email/send',
	    '/console/user/proccess-order'
    ]
**It is important to add "/" at the start to provide an absolute route.**
**Don't forget to apply changes to both console and web configuration files.**


# Usage
Now you can manage background tasks in the module section of your application.

 1. Create Cron Task
 2. Add cron expression to schedule field
 3. Select a route from list

If your background task is using parameters you can add them as a json string in gui, then pass to controller action and decode.

    public function actionTest($params)
    {
	    $params = json_decode($params, true);
	    print $params['message'] . PHP_EOL;
	    return ExitCode::OK;
    }
It is important to use PHP_EOL to write readable output to logs. Also module is using ExitCode class to normalize return values and it's recommended to design your actions accordingly to ExitCode constants. You can use them in exceptions as well:

    if (empty($model)) {
	    throw new Exception('Unable to find model', ExitCode::DATAERR);
    }
**You don't need to try-catch exceptions since module is doing it by itself.** 
This will form readable logs that are comfortable to support and search through.

Will be useful to check crontab file of php user to check if everything works correctly.

    crontab -e -u www-data

# Advanced configuration
**Warning! Before you update settings that change crontab line 
(e.g phpBin, yiiBootstrapAlias, outputSetting), disable all of your active cron tasks.**   

**Duplicating output to a file or STDOUT\STDERR:**


    'modules' => [
	    'crontab' => [
	    	'class' => 'gaxz\crontab\Module'
	    	...
	        'outputSetting' => '>> /var/log/app.log'
	        ...
	    ],
    ]

will form: 

    php yii /crontab/exec 1 >> /var/log/app.log 

**Setting path to php binary file manually:**

    'phpBin' => '/usr/bin/php'
**Writing configs of crontab file:**

    'headLines' => [
        'SHELL=/bin/sh',
        'PATH=/usr/bin:/usr/sbin',
    ],

**Setting crontab username**
*This will work only in case PHP script is running from privileged user (e.g. 'root')*

    'crontabUsername' => 'www-data',
**Setting path to yii bootstrap file**

    'yiiBootstrapAlias' => '@app/yii'
