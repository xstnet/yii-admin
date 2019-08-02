<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
?>
	<meta charset="UTF-8">
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?= Html::cssFile('@static_backend/frame/layui/css/layui.css')?>
	<?= Html::cssFile('@static_backend/frame/static/css/style.css')?>
	<?= Html::cssFile('@static_backend/css/style.css?v=' . Yii::$app->params['static_file_t'])?>
	<?= Html::cssFile('@static_backend/frame/static/image/code.png', ['rel' => 'icon'])?>
