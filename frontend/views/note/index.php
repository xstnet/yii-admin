<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title =  '笔记-专栏';
$userCache = Yii::$app->userCache;
if (isset($active_menu)) {
	$this->params['active_menu'] = $active_menu;
}
//?>
<div class="col-md-9 content-left">
	<div class="list-item">
        <h4><a target="_blank" href="/columns/linux-note">Linux 学习笔记</a></h4>
        <div class="list-item-description">Linux 学习笔记, 基于gitbook搭建的平台</div>
        <div class="list-item-separator"></div>
    </div>
</div>
