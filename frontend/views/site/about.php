<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title =  '';
$userCache = Yii::$app->userCache;

if (isset($active_menu)) {
	$this->params['active_menu'] = $active_menu;
}
//?>
<div class="col-md-9 content-left">
	<?php if (isset($breadcrumb)) {
		echo \common\helpers\Helpers::renderBreadcrumb($breadcrumb);
	}?>
	<div>
		<h4>
			博主: 徐善通, 2015年开始从事PHP后端开发, 这里记录一些心得和经验
		</h4>
		<p>
			这是我第四次改版了, 这个版本是基于Yii2框架开发的,之前三版分别是使用帝国cms和yii2框架写了两个版本, 每一个版本都是不同的风格,
			可能是以前比较浮躁吧, 比较喜欢花里胡哨的风格,
			我目前比较喜欢干净简约的风格, 所以就有了这个版本的博客
		</p>
		<p>
			由于之前比较懒, 导致将近两年都没有发布过文章, 但是现在愈发认识到学习的重要性, 所以就把博客重新翻新了一遍, 记录下我的学习历程
		</p>
		<p>
			有句话说的好, 最好的学习时间,一个是十年前, 一个是现在
		</p>
		<p>
			现在开始努力, 多年以后, 一定会感谢现在的自己!
		</p>
		<p>
			加油, 与君共勉!
		</p>
		
		<p>
			欢迎大家提意见和建议，我将做及时的修改和完善！也谢谢大家一直以来的支持(虽然没有人, 但是这句话还是要说 <img src="http://img.baidu.com/hi/face/i_f03.gif" alt="">)。
		</p>
		<br>
		<p>
			联系邮箱: shantongxu@qq.com
		</p>
		<p>
			微信: xushantong
		</p>
		<br>
		<br>
		<p>
			发布于 2019.08.10, 一个台风肆虐的傍晚
		</p>
	</div>
	<br>
	<br>
	<br>
	
	<div class="message">
		<h2>给我留言</h2>
		<hr class="hr">
		<form action="/message/release.html" method="post">
			<input type="hidden" name="_csrf-avwd" value="<?=Yii::$app->request->csrfToken?>" />
			<input type="hidden" name="from" value="about" />
			<div class="form-group">
				<label for="exampleInputEmail1">尊姓大名</label>
				<input type="text" required class="form-control" name="nickname" placeholder="您的尊姓大名">
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">Email</label>
				<input type="email" class="form-control" name="email" placeholder="Email(可填)">
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">内容(不超过255个字符)</label>
				<textarea required class="form-control" name="content" placeholder="说点什么吧" rows="8"></textarea>
			</div>

			<button type="submit" class="btn btn-default">提交</button>
		</form>
		<br>
		<br>
		<h2>最新留言</h2>
		<hr class="hr">
		<!--		<img width="70" src="/uploads/avatar/1.jpg" class="img-circle">-->

		<div>
			<ul class="media-list">
				<?php foreach ($messageList as $item) :?>
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
		</div>
	</div>
</div>
