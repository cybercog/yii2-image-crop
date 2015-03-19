<?php
/**
 * Created by PhpStorm.
 * User: mt1
 * Date: 17.10.2014
 * Time: 14:28
 */

namespace demoniz\imagecrop;

use yii\web\AssetBundle;

class CropImageAsset extends AssetBundle{

    public $css = [
        'css/cropper.css'
    ];

    public $js = [
        'js/cropper.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        return parent::init();
    }
}