<?php

namespace gaxz\crontab;

use yii\web\AssetBundle;

/**
 * Declaring the asset files.
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@gaxz/crontab/assets';
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/crontab.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}
