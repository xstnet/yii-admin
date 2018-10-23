<?php

/* @var $this yii\web\View */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title>控制台</title>
	<?= $this->render('../public/header.php')?>
	<!--<link rel="stylesheet" href="http://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">-->

	<?php $this->head() ?>
	<style>
		.laytable-cell-1-image{  /*最后的pic为字段的field*/
			height: 100%;
			max-width: 100%;
		}
		.notice-text {
			color: #3e8f3e;
			font-size: 16px;
		}
	</style>
</head>
<?php $this->beginBody() ?>
<body class="body">
<blockquote class="layui-elem-quote">
	<span class="layui-bg-gray">
		欢迎你，<span class="notice-text"><?=Yii::$app->user->identity->nickname?>！</span>
		本次登录时间为： <span class="notice-text"><?=date('Y-m-d H:i:s', Yii::$app->user->identity->login_at)?></span>,
		上次登录时间为： <span class="notice-text"><?=date('Y-m-d H:i:s', Yii::$app->user->identity->last_login_at)?></span>，
		本次登录IP为： <span class="notice-text"><?=Yii::$app->user->identity->login_ip?></span>，
		上次登录IP为： <span class="notice-text"><?=Yii::$app->user->identity->last_login_ip?></span>
	</span>
</blockquote>
<div class="layui-row layui-col-space10 my-index-main">
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/btn.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-btn-danger layui-icon">&#xe756;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">按钮</p>
			</div>
		</div>
	</div>
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/form.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-btn-warm layui-icon">&#xe735;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">表单</p>
			</div>
		</div>
	</div>
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/table.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-icon">&#xe715;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">表格</p>
			</div>
		</div>
	</div>
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/tab-card.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-btn-normal layui-icon">&#xe705;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">选项卡</p>
			</div>
		</div>
	</div>
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/progress-bar.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-bg-cyan layui-icon">&#xe6b2;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">进度条</p>
			</div>
		</div>
	</div>
	<div class="layui-col-xs4 layui-col-sm2 layui-col-md2">
		<div class="my-nav-btn layui-clear" data-href="./demo/folding-panel.html">
			<div class="layui-col-md5">
				<button class="layui-btn layui-btn-big layui-bg-black layui-icon">&#xe698;</button>
			</div>
			<div class="layui-col-md7 tc">
				<p class="my-nav-text">40</p>
				<p class="my-nav-text layui-elip">折叠面板</p>
			</div>
		</div>
	</div>

	<div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
		<div class="layui-collapse">
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">登录历史</h2>
				<div class="layui-colla-content layui-show">
					<table class="layui-table">
						<colgroup>
							<col width="150">
							<col width="200">
							<col>
						</colgroup>
						<thead>
						<tr>
							<th>昵称</th>
							<th>IP</th>
							<th>登录时间</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($loginHistory as $item):?>
						<tr>
							<td><?=$item['nickname']?></td>
							<td><?=$item['login_ip']?></td>
							<td><?=date('Y-m-d H:i:s', $item['login_at'])?></td>
						</tr>
						<?php endforeach;?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
	<div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
		<div class="layui-collapse">
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">表格</h2>
				<div class="layui-colla-content layui-show">

				</div>
			</div>
		</div>
	</div>
	<div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
		<div class="layui-collapse">
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">待办事项</h2>
				<div class="layui-colla-content layui-show">
					<table class="layui-table">
						<colgroup>
							<col width="150">
							<col width="200">
							<col>
						</colgroup>
						<thead>
						<tr>
							<th>昵称</th>
							<th>IP</th>
							<th>登录时间</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($loginHistory as $item):?>
							<tr>
								<td><?=$item['nickname']?></td>
								<td><?=$item['login_ip']?></td>
								<td><?=date('Y-m-d H:i:s', $item['login_at'])?></td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->render('../public/footer_js.php')?>
<?= Html::jsFile('@static_backend/js/index.js')?>
<script type="text/javascript">
	layui.use(['element', 'form', 'table', 'layer', 'vip_tab'], function () {
		var form = layui.form
			, table = layui.table
			, layer = layui.layer
			, vipTab = layui.vip_tab
			, element = layui.element
			, $ = layui.jquery;


		// 打开选项卡
		$('.my-nav-btn').on('click', function(){
			if($(this).attr('data-href')){
				//vipTab.add('','标题','路径');
				vipTab.add($(this),'<i class="layui-icon">'+$(this).find("button").html()+'</i>'+$(this).find('p:last-child').html(),$(this).attr('data-href'));
			}
		});

		// you code ...


	});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>