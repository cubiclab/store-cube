<?php
namespace cubiclab\store;

use Yii;
use cubiclab\base\BaseCube;
/**
 * @version 0.0.1-prealpha
 */
class StoreCube extends BaseCube //implements BootstrapInterface
{
    /** @const VERSION Module version */
    const VERSION = "0.0.1-prealpha";

    /**
     * @inheritdoc
     */
    public static $name = 'store';

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
            ['label' => 'Orders', 'url' => ['/admin/store/orders']],
            ['label' => 'DAP Terms', 'url' => ['/admin/store/dap-terms']],
            ['label' => 'Categories', 'url' => ['/admin/store/categories']],
            ['label' => 'Parameters', 'url' => ['/admin/store/parameters']],
        ]];

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public static function registerTranslations()
    {
        if (empty(Yii::$app->i18n->translations['storecube'])) {
            Yii::$app->i18n->translations['storecube'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        StoreCube::registerTranslations();
        return Yii::t($category, $message, $params, $language);
    }
}