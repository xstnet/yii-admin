<?php

/* @var $this yii\web\View */
/* @var $article \common\models\Article */
use yii\helpers\Html;

$this->title =  $article->title;

$isMarkdown = !empty($article->content->markdown_content) ? 1 : 0;
$contentClass = $isMarkdown ? 'markdown-content' : 'html-content';
$userCache = Yii::$app->userCache;
//?>

<div class="col-md-9 content-left">
	<?php if (isset($breadcrumb)) {
		echo \common\helpers\Helpers::renderBreadcrumb($breadcrumb);
	}?>
	<h2 class="margin-t-5"><?= $article->title?></h2>
	<div class="list-item-footer icon-wrap margin-t-5">
		<span><i class="glyphicon glyphicon-th-list" aria-hidden="true"></i><a href="/category-<?=$article->category_id?>.html"><?= $userCache->getArticleCategoryNameById($article->category_id)?></a></span>
		<span><i class="glyphicon glyphicon-time" aria-hidden="true"></i><?= date('Y-m-d', $article->created_at)?></span>
		<span><i class="glyphicon glyphicon-eye-open" aria-hidden="true"></i>阅读(<?= $article->hits?>)</span>
		<span><i class="glyphicon glyphicon-comment" aria-hidden="true"></i>评论(<?= $article->comment_count?>)</span>
	</div>
	<hr class="hr">
	<div id="articleContent" class="article-content <?= $contentClass?>">
		<?= $article->content->content;?>
	</div>
	<hr class="hr">
	<div class="tag-list margin-t-15">
		<?php
			$tags = explode(',', trim($article->keyword));
			$tagMap = ['default', 'success', 'info', 'warning', 'danger'];
			foreach ($tags as $k => $tag) {
				echo "<a href=\"/tag/$tag.html\"><span class=\"label label-{$tagMap[$k%5]}\">$tag</span></a> ";
			}
		?>
	</div>
	<div class="bg-danger padding-15 margin-t-20">
		<span>作者： 徐善通</span>
		<br>
		<span>地址： <a href="<?=Yii::$app->request->hostInfo . Yii::$app->request->url?>"><?=Yii::$app->request->hostInfo . Yii::$app->request->url?></a></span>
		<br>
		<span>声明： 除非本文有注明出处，否则转载请注明本文地址</span>
	</div>
	<hr class="hr">
	<p>上一篇： <a style="margin-right: 50px" href="<?=empty($prevArticle) ? '/' : "article-{$prevArticle['id']}.html"?>"><?=empty($prevArticle) ? '返回首页' : $prevArticle['title']?></a>
		下一篇： <a href="<?=empty($nextArticle) ? '/' : "article-{$nextArticle['id']}.html"?>"><?=empty($nextArticle) ? '返回首页' : $nextArticle['title']?></a></p>
	<br>
</div>
<?php if ($isMarkdown) :?>
<link rel="stylesheet" href="/static/backend/plugins/mditor/dist/css/mditor.min.css" />
<?php else : ?>
	<link rel="stylesheet" href="/static/frontend/css/google-code-lights.css" />
	<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
	<script src="/static/frontend/js/prettify.js"></script><!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="/static/frontend/js/ie10-viewport-bug-workaround.js"></script>
	<!--渲染谷歌代码高亮-->
	<script language='javascript'>
		$('pre').addClass('prettyprint linenums');
		prettyPrint();
	</script>
<?php endif; ?>