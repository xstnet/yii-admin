<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-22
 * Time: 下午3:51
 */

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Json;

$name = '用户';
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
		<title><?= $name?>管理</title>
		<?= $this->render('../public/header.php')?>
		<?php $this->head() ?>
		<style>
			#headImg {
				width: 120px;
			}
			.layui-disabled {
				background-color: #e4e4e4;
				color: #040404 !important;
			}
		</style>
	</head>
	<?php $this->beginBody() ?>
	<body class="body">

	<span id="csrfToken"><?=Yii::$app->request->csrfToken?></span>

	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
		<legend>修改个人信息</legend>
	</fieldset>

	<form class="layui-form" action="">
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-inline">
				<input type="text" name="username" disabled autocomplete="off" value="<?=Yii::$app->user->identity->username?>" class="layui-input layui-disabled">
			</div>
			<div class="layui-form-mid layui-word-aux">用户名不能修改</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">昵称</label>
			<div class="layui-input-inline">
				<input type="text" name="nickname" lay-verify="required" lay-verify="title" autocomplete="off" placeholder="请输入昵称" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-inline">
				<input type="text" name="password" autocomplete="off" placeholder="请输入密码" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">不修改密码请留空</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">邮箱</label>
			<div class="layui-input-inline">
				<input type="text" name="email" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">头像</label>
			<div class="layui-input-inline">
				<div class="layui-upload">
					<div class="layui-upload-list">
						<img class="layui-upload-img" id="headImg">
						<p id="demoText"></p>
					</div>
					<button type="button" class="layui-btn" id="uploadHeadImg">上传头像</button>
				</div>
			</div>
		</div>

		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
			</div>
		</div>
	</form>


	<?= $this->render('../public/footer_js.php')?>
	<?= Html::jsFile('@static_backend/js/index.js')?>

	<script type="text/javascript">
		// layui方法
		layui.use(['form', 'layer', 'upload',], function () {
			// 操作对象
			var form = layui.form
				, layer = layui.layer
				, $ = layui.jquery
				, upload = layui.upload;

				//普通图片上传
				var uploadInst = upload.render({
					elem: '#uploadHeadImg'
					,url: '<?= Yii::$app->urlManager->createUrl("upload/image")?>'
					,data: {_csrf_token_backend_xstnet: $('#csrfToken').text()}
					,before: function(obj){
						//预读本地文件示例，不支持ie8
						obj.preview(function(index, file, result){
							$('#headImg').attr('src', result); //图片链接（base64）
						});
					}
					,done: function(res){
						//如果上传失败
						if(res.code > 0){
							return layer.msg('上传失败');
						}
						//上传成功
					}
					,error: function(){
						//演示失败状态，并实现重传
						var demoText = $('#demoText');
						demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
						demoText.find('.demo-reload').on('click', function(){
							uploadInst.upload();
						});
					}
				});

			// 提交表单
			form.on('submit(form-submit)', function(data){
				var url = '<?= Yii::$app->urlManager->createUrl("member/add-member")?>';
				if (data.field.id > 0) {
					url = '<?= Yii::$app->urlManager->createUrl("member/save-member")?>';
				} else {
					if (data.field.password.length < 5) {
						layer.msg('密码长度不能小于5位');
						return false;
					}
				}

				return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
			});

		});



	</script>
	<?php $this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>