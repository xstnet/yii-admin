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
use common\helpers\SystemSettingForm;

$name = '系统设置';
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
				max-width: 700px;
				min-width: 120px;
				height: 120px;
				border: 1px solid #ddd;
			}
			.setting-item { width: 70% !important; }
			.setting-code { width: 200px !important; }
			.layui-tab-content { padding: 20px }
		</style>
		<?php $this->head() ?>
	</head>
	<?php $this->beginBody() ?>
	<body class="body">
	<span id="csrfToken"><?=Yii::$app->request->csrfToken?></span>
	<div style="padding: 0 20px;">
		<form class="layui-form" action="" >
		<div class="layui-tab layui-tab-card" lay-filter="setting-tab" style="background: #fff">
			<ul class="layui-tab-title">
				<?php $i = 0; foreach ($categories as $k => $item): $i++;?>
				<li class="<?=$i === 1 ? 'layui-this' : ''?>"><?=$item['name']?></li>
				<?php endforeach; $i = 0;?>
			</ul>

			<div class="layui-tab-content">
				<?php $i = 0; foreach ($systemConfigs as $categoryId => $configs): $i++;?>
				<div class="layui-tab-item <?=$i === 1 ? 'layui-show' : ''?>">
					<?php foreach ($configs as $item) :?>
						<div class="layui-form-item">
							<?php
								switch ($item['type']) {
									case 'text':
										echo SystemSettingForm::text($item);
										break;
									case 'imagefile' :
										echo SystemSettingForm::fileImage($item);
										break;
									case 'textarea' :
										echo SystemSettingForm::textarea();
										break;
								}
							?>
						</div>
					<?php endforeach;?>
				</div>
				<?php endforeach;?>
				<div class="layui-form-item">
					<div class="layui-input-block" style="padding: 20px 0">
						<button type="button" class="layui-btn">添加</button>
						<button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="form-submit">保存修改</button>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>

	<?= $this->render('../public/footer_js.php')?>
	<?= Html::jsFile('@static_backend/js/index.js')?>
	<script type="text/javascript">
		// layui方法
		layui.use(['form', 'element', 'upload', 'layer'], function() {
			var $ = layui.jquery,
				layer = layui.layer,
				element = layui.element, //Tab的切换功能，切换事件监听等，需要依赖element模块
				upload = layui.upload,
				form = layui.form;

			// 上传图片
			var uploadInst = upload.render({
				elem: '.select-image-file',
				url: '<?= Yii::$app->urlManager->createUrl("upload/image-file")?>',
				data: {_csrf_token_backend_xstnet: $('#csrfToken').text()},
				before: function(obj){
					//预读本地文件示例，不支持ie8
					obj.preview(function(index, file, result) {
						$('#headImg').attr('src', result); //图片链接（base64）
					});
				},
				done: function(res) {
					var item = this.item;
					if(res.code === AJAX_STATUS_SUCCESS){
						//上传成功
						item.prev().find('.setting-img').attr('src', '/'+res.data.file);
						item.prev().find('.upload-message').html('');
						item.prev().find('input').val(res.data.file);
					} else {//如果上传失败
						var msg = res.message ? res.message : '上传失败';
						layer.msg(msg);
						this.retry();
						return false;
					}
				},
				retry: function () {
					var item = this.item;
					//演示失败状态，并实现重传
					var uploadMessage = item.prev().find('.upload-message');
					uploadMessage.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
					uploadMessage.find('.demo-reload').on('click', function(){
						uploadInst.upload();
					});
				},
				error: function(){
					layer.closeAll();
					this.retry();
				}

			});

			// 提交表单 保存设置
			form.on('submit(form-submit)', function(data) {
				var formData = $('form').serializeJson();
				console.log(formData);
				var url = '<?= Yii::$app->urlManager->createUrl("setting/save-setting")?>';
				$.post(
					url,
					formData,
					function (result) {
						layer.msg(result.message);
					},
					'json'
				)

				return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
			});

			/**
			 * 切换分类时，禁用其他分类的设置
			 */
			element.on('tab(setting-tab)', function(data){
				disabledInput(data.index);
			});
			
			function disabledInput(index) {
				$('.layui-tab-content :input').prop('disabled', true);
				$('.layui-tab-content :checkbox').prop('disabled', true);
				$('.layui-tab-content :radio').prop('disabled', true);

				$('.layui-tab-item').eq(index).find(':input').prop('disabled', false);
				$('.layui-tab-item').eq(index).find(':checkbox').prop('disabled', false);
				$('.layui-tab-item').eq(index).find(':radio').prop('disabled', false);
				$('button').prop('disabled', false);
			}

			// 默认禁用其他分类的设置，显示第一个
			disabledInput(0);
		});
	</script>
	<?php $this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>