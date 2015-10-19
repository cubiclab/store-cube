<?php
namespace cubiclab\store;

use Yii;
use yii\base\BootstrapInterface;

/**
 * @version 0.0.1-prealpha
 */
class StoreCube extends \yii\base\Module //implements BootstrapInterface
{
    /** @const VERSION Module version */
    const VERSION = "0.0.1-prealpha";

    public static $menu =
        ['label' => 'Store', 'icon' => 'fa-shopping-cart', 'items' => [
            ['label' => 'All users', 'url' => ['/admin/store']],
        ]];

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        if (empty(Yii::$app->i18n->translations['storecube'])) {
            Yii::$app->i18n->translations['storecube'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}