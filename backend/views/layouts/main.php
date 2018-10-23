<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title>后台管理系统</title>
	<?= $this->render('../public/header.php')?>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- layout admin -->
<div class="layui-layout layui-layout-admin"> <!-- 添加skin-1类可手动修改主题为纯白，添加skin-2类可手动修改主题为蓝白 -->
	<!-- header -->
	<div class="layui-header my-header">
		<a href="index.html">
			<!--<img class="my-header-logo" src="" alt="logo">-->
			<div class="my-header-logo">后台管理系统</div>
		</a>
		<div class="my-header-btn">
			<button class="layui-btn layui-btn-small btn-nav"><i class="layui-icon layui-icon-more"></i></button>
		</div>

		<!-- 顶部左侧添加选项卡监听 -->
		<ul class="layui-nav" lay-filter="side-top-left">
			<!--<li class="layui-nav-item"><a href="javascript:;" href-url="demo/btn.html"><i class="layui-icon">&#xe621;</i>按钮</a></li>
			<li class="layui-nav-item">
				<a href="javascript:;"><i class="layui-icon">&#xe621;</i>基础</a>
				<dl class="layui-nav-child">
					<dd><a href="javascript:;" href-url="demo/btn.html"><i class="layui-icon">&#xe621;</i>按钮</a></dd>
					<dd><a href="javascript:;" href-url="demo/form.html"><i class="layui-icon">&#xe621;</i>表单</a></dd>
				</dl>
			</li>-->
		</ul>

		<!-- 顶部右侧添加选项卡监听 -->
		<ul class="layui-nav my-header-user-nav" lay-filter="side-top-right">
			<li class="layui-nav-item"><a target="_blank" href="/" href-url="">网站首页</a></li>
			<li class="layui-nav-item">
				<a class="name" href="javascript:;"><i class="layui-icon layui-icon-theme"></i>主题</a>
				<dl class="layui-nav-child">
					<dd data-skin="0"><a href="javascript:;">默认</a></dd>
					<dd data-skin="1"><a href="javascript:;">纯白</a></dd>
					<dd data-skin="2"><a href="javascript:;">蓝白</a></dd>
				</dl>
			</li>
			<li class="layui-nav-item">
				<a class="name" href="javascript:;"><img src="<?=Yii::$app->user->identity->avatar?>" alt="logo"> <?=Yii::$app->user->identity->username?> </a>
				<dl class="layui-nav-child">
					<dd><a href="javascript:;" href-url="<?=Yii::$app->urlManager->createUrl('user/profile')?>"><i class="layui-icon layui-icon-edit"></i>修改个人信息</a></dd>
					<dd><a href="<?=Yii::$app->urlManager->createUrl(['login/logout'])?>"><i class="layui-icon layui-icon-close"></i>退出</a></dd>
					<dd><a href="<?=Yii::$app->urlManager->createUrl(['login/logout'])?>"><i class="layui-icon layui-icon-close"></i>退出</a></dd>
				</dl>
			</li>
		</ul>

	</div>
	<!-- side -->
	<div class="layui-side my-side">
		<div class="layui-side-scroll">
			<!-- 左侧主菜单添加选项卡监听 -->
			<ul class="layui-nav layui-nav-tree" lay-filter="side-main">
			</ul>
		</div>
	</div>
	<!-- body -->
	<div class="layui-body my-body">
		<div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
			<ul class="layui-tab-title">
				<li class="layui-this" lay-id="1"><span><i class="layui-icon layui-icon-layer"></i>欢迎页</span></li>
			</ul>
			<div class="layui-tab-content">
				<div class="layui-tab-item layui-show">
					<iframe id="iframe" src="<?= Yii::$app->urlManager->createUrl(['site/welcome'])?>" frameborder="0"></iframe>
				</div>
			</div>
		</div>
	</div>
	<!-- footer -->
	<div class="layui-footer my-footer">
		<p><a href="http://vip-admin.com" target="_blank">vip-admin后台模板v1.8.0</a>&nbsp;&nbsp;&&nbsp;&nbsp;<a href="http://vip-admin.com/index/gather/index.html" target="_blank">vip-admin管理系统v1.2.0</a></p>
		<p>2017 © copyright 蜀ICP备17005881号</p>
	</div>
</div

<!-- 右键菜单 -->
<div class="my-dblclick-box none">
	<table class="layui-tab dblclick-tab">
		<tr class="card-refresh">
			<td><i class="layui-icon layui-icon-refresh-1"></i>刷新当前标签</td>
		</tr>
		<tr class="card-close">
			<td><i class="layui-icon layui-icon-star-fill"></i>关闭当前标签</td>
		</tr>
		<tr class="card-close-all">
			<td><i class="layui-icon layui-icon-close"></i>关闭所有标签</td>
		</tr>
	</table>
</div>

<?= Html::jsFile('@static_backend/frame/layui/layui.js')?>
<?= Html::jsFile('@static_backend/frame/static/js/vip_comm.js')?>
<script type="text/javascript">
	layui.config({
		base: "<?=Yii::getAlias('@static_backend')?>/frame/static/js/" //你存放新模块的目录，注意，不是layui的模块目录
	});
	layui.use(['layer','vip_nav'], function () {

		// 操作对象
		var layer       = layui.layer
			,vipNav     = layui.vip_nav
			,$          = layui.jquery;

		// 顶部左侧菜单生成 [请求地址,过滤ID,是否展开,携带参数]
		vipNav.top_left('<?=Yii::getAlias("@static_backend")?>/json/nav_top_left.json','side-top-left',false);
		// 主体菜单生成 [请求地址,过滤ID,是否展开,携带参数]
		vipNav.main('<?=Yii::$app->urlManager->createUrl('setting/get-menus')?>','side-main',true);

		// you code ...


	});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
