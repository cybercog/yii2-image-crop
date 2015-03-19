<?php
/**
 * Created by PhpStorm.
 * User: mt1
 * Date: 17.10.2014
 * Time: 9:17
 */
namespace demoniz\imagecrop;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\BaseHtml;


class CropImage extends InputWidget
{
    public $folder;
    public $aspectRatio;
    public $width;
    public $height;
    public $bigImage;

    public function run()
    {
        $this->registerJs();
        echo $this->renderWidget();
    }

    public function registerJs()
    {
        $view = $this->getView();

        $js = '
        var setting = {
              aspectRatio: '.$this->aspectRatio.',
              autoCropArea: 1,
              strict: true,
              guides: true,
              highlight: false,
              dragCrop: false,
              movable: false,
              resizable: false,
              crop: function(data){

                    $("#putData").val(JSON.stringify($image.cropper("getCanvasData")));
                    $("#putPhpData").val(JSON.stringify(data));
                }
        };

        var $image = $(".containerCrop > img");
        $image.cropper(setting);';

        if (isset($this->model['params']['imgPosition']) && $this->model['params']['imgPosition']) {
            $js .= '
            $image.one("built.cropper", function (e) {
              var option = ' . $this->model['params']['imgPosition'] . ';
                $image.cropper("setCanvasData", option);
            });';
        }

        $js .= '
        $("#inputImage").change(function (evt) {
            //alert("ddd");
            var files = evt.target.files;
            var f = files[0];
                if (f.type.match("image.*")) {
                    var reader = new FileReader();
                    reader.onload = (function (theFile) {
                        return function (e) {
                            $image.one("built.cropper").cropper("reset", true).cropper("replace", e.target.result);
                        };
                    })(f);
                    reader.readAsDataURL(f);
                }
                else {
                    alert("Вы выбрали не изображение");
                }
        });';

        $css = '
        .containerCrop{
            width:'.$this->width.'px;
            height:'.$this->height.'px;
            margin-bottom:15px;
            background: url("data:image/gif;base64,R0lGODlhEAAQAKEAAISChPz+/P///wAAACH5BAEAAAIALAAAAAAQABAAAAIfhG+hq4jM3IFLJhoswNly/XkcBpIiVaInlLJr9FZWAQA7")
        }';

        $view->registerJs($js);
        $view->registerCss($css);

        CropImageAsset::register($view);
    }

    public function renderWidget()
    {
        $imgName = BaseHtml::getAttributeValue($this->model, $this->attribute);
        if (isset($imgName) && $imgName) {
            $img = Html::img($this->bigImage . $imgName);
        } else {
            $img = '<img src="/images/logo/no.png" alt="">';//Html::img(NULL);
        }

        $return = "
        <div class='containerCrop'>
            " . $img . "
        </div>
        " . Html::activeFileInput($this->model, $this->attribute, ['id' => 'inputImage', 'accept' => 'image/*', 'data-toggle' => 'tooltip', 'title' => 'Выберите главное изображение товара']) . "
        " . Html::activeHiddenInput($this->model, 'params[imgPosition]', ['id' => 'putData'])
        . Html::activeHiddenInput($this->model, 'params[imgPhpPosition]', ['id' => 'putPhpData']);

        return $return;
    }

}