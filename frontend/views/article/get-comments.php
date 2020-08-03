<?php

/* @var $this yii\web\View */
/* @var $article \common\models\Article */

use common\helpers\MyHtml;
use yii\helpers\Html;



function getCommentTree($data, $level = 1) {
    $deleteText = '<span style="color: #FF5722">该评论已隐藏!</span>';

    $htmlString = '';

    foreach ($data as $index => $item) {
        $commentTitle = Html::encode($item['nickname']) . (!empty($item['email']) ? "(" .Html::encode($item['email']).")" : '');

        $content = $item['is_delete'] == 1 ? $deleteText : MyHtml::encode($item['content']);
        $releaseTime = date('Y-m-d H:i', $item['created_at']);
        $tag = $level === 1 ? 'li' : 'div';

        $subTree = '';
        if (!empty($item['children'])) {
            $subTree = '<hr class="hr">' . getCommentTree($item['children'], ($level+1));
        }

        // 每一级的最后一行不显示 hr
        $showHr = '<hr class="hr">';
        if ($index == count($data)-1) {
            $showHr = '';
        }

        $htmlString .= <<<ETO
        <{$tag} class="media">
			<div class="media-left">
				<a href="#">
					<img width="64" class="media-object" src="http://xstnet.com/{$item['avatar']}">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading text-primary">{$commentTitle}</h4>
				<div class="message-content" style="margin-bottom: 5px">
                    {$content}
				</div>
				<p class="text-muted margin-0">
                    <span class="release-time">发布于: $releaseTime</span>
                    <span class="replay-comment"><a href="javascript:void(0)" onclick="replayComment({$item['id']}, '{$commentTitle}')">回复</a></span>
                </p>
				    {$subTree}
			</div>
			{$showHr}
		</{$tag}>
ETO;
    }
    return $htmlString;
}

//?>
<style>
    .replay-comment {
        margin-left: 35px;
    }
    .replay-to {
        color: #fe3d01;
        font-weight: bold;
        padding-right: 8px;
        font-size: 15px;
    }
    .message-content {
        min-height: 30px;
        padding: 5px 0;
    }
</style>


<ul class="media-list">
    <?php
    if (!empty($commentList)) {
        echo getCommentTree($commentList);
    } else {
        echo '<p>暂无评论, 快来抢沙发吧</p>';
    }
    ?>
</ul>
<nav aria-label="Page navigation">
	<?= yii\widgets\LinkPager::widget([
		'pagination' => $pages,
		'maxButtonCount' => 5,
		'nextPageLabel' => '下一页', // 修改上下页按钮
		'prevPageLabel' => '上一页',
	]) ?>
</nav>


