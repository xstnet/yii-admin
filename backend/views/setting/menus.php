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

$name = '系统设置';
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
		<title><?= $name?>管理</title>
		<?= $this->render('../public/header.php')?>
		<?= Html::cssFile('@static_backend/css/eleTree.css')?>
		<?php $this->head() ?>
	</head>
	<?php $this->beginBody() ?>
	<body class="body">
	<!-- 工具集 -->
	<div class="my-btn-box">
    <span class="fl">
        <a class="layui-btn layui-btn-danger radius btn-delect" id="btn-delete">删除</a>
		<!--        <a class="layui-btn btn-add btn-default" id="btn-add">添加--><?//= $name?><!--</a>-->
    </span>
		<span class="fr">
         <a class="layui-btn btn-add btn-default" id="btn-refresh"><i class="layui-icon layui-icon-refresh"></i></a>
    </span>
	</div>
	<span id="csrfToken"><?=Yii::$app->request->csrfToken?></span>

	<div style="padding: 20px; background-color: #F2F2F2; min-height: 100vh">
		<div class="layui-row layui-col-space15">
			<div class="layui-col-md3">
				<div class="layui-card">
					<div class="layui-card-header">菜单列表</div>
					<div class="layui-card-body">
						<!--菜单树-->
						<div class="eleTree" id="treeList" lay-filter="data1"></div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?= $this->render('../public/footer_js.php')?>
	<?= Html::jsFile('@static_backend/js/index.js')?>
	<?= Html::jsFile('@static_backend/js/eleTree.js')?>
	<script type="text/javascript">
		// 权限树
		var pageName = '<?= $name?>',
			menusTree = {},
			currentMenu = {};
		// layui方法
		layui.use(['form', 'layer', 'eleTree'], function () {
			// 操作对象
			var form = layui.form
				, layer = layui.layer
				, eleTree = layui.eleTree
				, $ = layui.jquery;

		});


	</script>
	<?php $this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>