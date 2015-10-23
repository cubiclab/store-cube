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

    public $image_placeholder = "@static/images/product.jpg";
    public $image_path = "@static/upload/products/{prod_id}";
    public $image_url = "@static_url/upload/products/{prod_id}";
    public $image_thumbs = [
        'thumb' => ['width' => 400, 'quality' => 90],
        'preview' => ['width' => 200, 'height' => 200],
    ];

    public static $menu =
        ['label' => 'Store', 'icon' => 'fa-shopping-cart', 'items' => [
            ['label' => 'All Products', 'url' => ['/admin/store/products']],
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