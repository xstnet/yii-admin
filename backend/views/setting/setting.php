<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-22
 * Time: 下午9:20
 */

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Json;

$name = '菜单';
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
		<title><?= $name?>管理</title>
		<?= $this->render('../public/header.php')?>
		<style>
			body {
				background-color: #F2F2F2;
			}
			.setting-img {
				max-width: 360px;
				height: 120px;
			}
		</style>
		<?php $this->head() ?>
	</head>
	<?php $this->beginBody() ?>
	<body class="body">
	<!-- 工具集 -->
	<div class="my-btn-box">
    <span class="fl">
<!--        <a class="layui-btn layui-btn-danger radius btn-delect" id="btn-delete">删除</a>-->
    </span>
		<span class="fr">
         <a class="layui-btn btn-add btn-default" id="btn-refresh"><i class="layui-icon layui-icon-refresh"></i></a>
    </span>
	</div>
	<span id="csrfToken"><?=Yii::$app->request->csrfToken?></span>

	<div style="padding: 0 20px;">
		<div class="layui-tab layui-tab-card" style="background: #fff">
			<ul class="layui-tab-title">
				<li class="layui-this">网站设置</li>
				<li>用户管理</li>
				<li>权限分配</li>
				<li>商品管理</li>
				<li>订单管理</li>
			</ul>
			<form class="layui-form" action="" style="padding:20px">
			<div class="layui-tab-content">
				<div class="layui-tab-item layui-show">
					<div class="layui-form-item">
						<label class="layui-form-label">网站名称</label>
						<div class="layui-input-block">
							<input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="网站名称" class="layui-input">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">网站域名</label>
						<div class="layui-input-block">
							<input type="text" name="username" lay-verify="required" placeholder="http://www.xstnet.com" autocomplete="off" class="layui-input">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">网站Logo</label>
						<div class="layui-input-inline">
							<div class="layui-upload">
								<div class="layui-upload-list">
									<img class="layui-upload-img setting-img" src="<?=Yii::$app->user->identity->avatar ? : '/'. Yii::$app->params['defaultAvatar']?>">
									<p id="demoText"></p>
								</div>
								<button type="button" class="layui-btn layui-btn-warm layui-btn-sm" id="uploadHeadImg">选择图片</button>
								<input type="hidden" id="headImgInput" name="avatar" value="<?=Yii::$app->user->identity->avatar ? : Yii::$app->params['defaultAvatar']?>">
							</div>
						</div>
					</div>
					<div class="layui-form-item">
						<div class="layui-inline">
							<label class="layui-form-label">多规则验证</label>
							<div class="layui-input-inline">
								<input type="text" name="number" lay-verify="required|number" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-inline">
							<label class="layui-form-label">验证日期</label>
							<div class="layui-input-inline">
								<input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-inline">
							<label class="layui-form-label">验证链接</label>
							<div class="layui-input-inline">
								<input type="tel" name="url" lay-verify="url" autocomplete="off" class="layui-input">
							</div>
						</div>
					</div>

					<div class="layui-form-item">
						<label class="layui-form-label">验证身份证</label>
						<div class="layui-input-block">
							<input type="text" name="identity" lay-verify="identity" placeholder="" autocomplete="off" class="layui-input">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">自定义验证</label>
						<div class="layui-input-inline">
							<input type="password" name="password" lay-verify="pass" placeholder="请输入密码" autocomplete="off" class="layui-input">
						</div>
						<div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
					</div>

					<div class="layui-form-item">
						<div class="layui-inline">
							<label class="layui-form-label">范围</label>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="price_min" placeholder="￥" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid">-</div>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="price_max" placeholder="￥" autocomplete="off" class="layui-input">
							</div>
						</div>
					</div>

					<div class="layui-form-item">
						<label class="layui-form-label">单行选择框</label>
						<div class="layui-input-block">
							<select name="interest" lay-filter="aihao">
								<option value=""></option>
								<option value="0">写作</option>
								<option value="1" selected="">阅读</option>
								<option value="2">游戏</option>
								<option value="3">音乐</option>
								<option value="4">旅行</option>
							</select>
						</div>
					</div>
				</div>
				<div class="layui-tab-item">2</div>
				<div class="layui-tab-item">3</div>
				<div class="layui-tab-item">4</div>
				<div class="layui-tab-item">5</div>
				<div class="layui-tab-item">6</div>
			</div>
			</form>
		</div>
	</div>

	<?= $this->render('../public/footer_js.php')?>
	<?= Html::jsFile('@static_backend/js/index.js')?>
	<script type="text/javascript">
		// 权限树
		var pageName = '<?= $name?>';
		// layui方法
			layui.use('element', function(){
			var $ = layui.jquery
				,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

		});


	</script>
	<?php $this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>