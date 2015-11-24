<?php
/**
 * Created by PhpStorm.
 * User: Pt1c
 * Date: 24.11.2015
 * Time: 22:36
 */
namespace cubiclab\store\assets;

use yii\web\AssetBundle;

class AuditColumnAssetsBundle extends AssetBundle
{
    public $sourcePath = '@cubiclab/store/assets';
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/scripts.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
