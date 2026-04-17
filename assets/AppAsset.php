<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/styles.css',
    ];
    
    public $js = [
    ];
    
    public static $extra_js = [];
    public static $extra_css = [];
    public static $extra_depends = [];    
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
    
    public function init()
    {
        parent::init();
        foreach(self::$extra_depends as $d) $this->depends[] = $d;
        foreach(self::$extra_js as $js) $this->js[] = $js;
        foreach(self::$extra_css as $css) $this->css[] = $css;
    }

    public static function addJs($js)
    {
        self::$extra_js[] = $js;
    }

    public static function addCss($css)
    {
        self::$extra_css[] = $css;
    }

    public static function addDepends($depends)
    {
        if (!is_array($depends)) $depends = [$depends];

        foreach($depends as $el) {
            self::$extra_depends[] = $el;
        }
    }    
}
