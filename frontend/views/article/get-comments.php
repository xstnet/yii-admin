<?php

/* @var $this yii\web\View */
/* @var $article \common\models\Article */
use yii\helpers\Html;

//?>
<ul class="media-list">
	<?php foreach ($commentList as $item) :?>
		<li class="media">
			<div class="media-left">
				<a href="#">
					<img width="64" class="media-object" src="<?=$item['avatar']?>">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading text-primary"><?=Html::encode($item['nickname']) . (!empty($item['email']) ? "(" .Html::encode($item['email']).")" : '')?></h4>
				<div class="message-content" style="margin-bottom: 5px"><?=Html::encode($item['content'])?></div>
				<p class="text-muted margin-0">发布于: <?=date('Y-m-d H:i', $item['created_at'])?></p>
			</div>
			<hr class="hr">
		</li>
	<?php endforeach;?>
</ul>
<nav aria-label="Page navigation">
	<?= yii\widgets\LinkPager::widget([
		'pagination' => $pages,
		'maxButtonCount' => 5,
		'nextPageLabel' => '下一页', // 修改上下页按钮
		'prevPageLabel' => '上一页',
	]) ?>
</nav>


