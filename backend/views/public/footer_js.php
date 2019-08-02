<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
?>
<?= Html::jsFile('@static_backend/frame/layui/layui.js')?>
<?= Html::jsFile('@static_backend/js/common.js?v=' . Yii::$app->params['static_file_t'])?>