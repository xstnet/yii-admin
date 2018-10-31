<?php

/* @var $this yii\web\View */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title>商品管理</title>
	<?= $this->render('../public/header.php')?>
	<!--<link rel="stylesheet" href="http://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">-->

	<?php $this->head() ?>
	<style>
		.laytable-cell-1-image{  /*最后的pic为字段的field*/
			height: 100%;
			max-width: 100%;
		}
	</style>
</head>
<?php $this->beginBody() ?>
<body class="body">
<!-- 工具集 -->
<div class="my-btn-box">
    <span class="fl">
        <a class="layui-btn layui-btn-danger radius btn-delect" id="btn-delete-all">批量删除</a>
        <a class="layui-btn btn-add btn-default" id="btn-add">添加</a>
        <a class="layui-btn btn-add btn-default" id="btn-refresh"><i class="layui-icon">&#x1002;</i></a>
    </span>
	<span class="fr">
        <span class="layui-form-label">搜索条件：</span>
        <div class="layui-input-inline">
            <input type="text" autocomplete="off" placeholder="请输入搜索条件" class="layui-input">
        </div>
        <button class="layui-btn mgl-20">查询</button>
    </span>
</div>

<!-- 表格 -->
<div id="dataTable"></div>

<?= $this->render('../public/footer_js.php')?>
<?= Html::jsFile('@static_backend/js/index.js')?>
<script type="text/html" id="listImg">
<!--	<img src="{{d.image}}" >-->
	<img src="https://avatar.csdn.net/C/C/D/3_k8080880.jpg" width="50px" height="50px" >
</script>
<script type="text/javascript">
	// layui方法
	layui.use(['table', 'form', 'layer', 'vip_table'], function () {

		// 操作对象
		var form = layui.form
			, table = layui.table
			, layer = layui.layer
			, vipTable = layui.vip_table
			, $ = layui.jquery;

		// 表格渲染
		var tableIns = table.render({
			elem: '#dataTable'                  //指定原始表格元素选择器（推荐id选择器）
			, height: vipTable.getFullHeight()    //容器高度
			,even: true
			, cols: [[                  //标题栏
				{checkbox: true, sort: true, fixed: true, space: true}
				, {field: 'id', title: '图片', width: 80, style: 'height:50px'}
				, {field: 'image', title: 'ID', width: 80, templet: '#listImg'}
				, {field: 'name', title: '名称', width: 150}
				, {field: 'attribute', title: '属性', width: 120}
				, {field: 'desc', title: '说明', width: 180}
				, {field: 'price', title: '价格', width: 180}
				, {field: 'is_stacked', title: '可叠加', width: 180}
				, {field: 'max_quantity', title: '一组数量', width: 120}
				, {fixed: 'right', title: '操作', width: 150, align: 'center', toolbar: '#barOption'} //这里的toolbar值是模板元素的选择器
			]]
			, id: 'dataCheck'
			, url: '<?=Yii::getAlias('@static_backend')?>/json/data_table.json'
			, method: 'get'
			, page: true
			, limits: [30, 60, 90, 150, 300]
			, limit: 30 //默认采用30
			, page: {
				layout: ['prev', 'page', 'next', 'skip', 'count', 'refresh','limit', ]
			}
			, loading: false
			, done: function (res, curr, count) {
				//如果是异步请求数据方式，res即为你接口返回的信息。
				//如果是直接赋值的方式，res即为：{data: [], count: 99} data为当前页数据、count为数据总长度
				console.log(res);

				//得到当前页码
				console.log(curr);

				//得到数据总量
				console.log(count);
			}
		});

		// 获取选中行
		table.on('checkbox(dataCheck)', function (obj) {
			layer.msg('123');
			console.log(obj.checked); //当前是否选中状态
			console.log(obj.data); //选中行的相关数据
			console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
		});

		// 刷新
		$('#btn-refresh').on('click', function () {
			tableIns.reload();
		});


		// you code ...

	});
</script>
<!-- 表格操作按钮集 -->
<script type="text/html" id="barOption">
	<a class="layui-btn layui-btn-mini" lay-event="detail">查看</a>
	<a class="layui-btn layui-btn-mini layui-btn-normal" lay-event="edit">编辑</a>
	<a class="layui-btn layui-btn-mini layui-btn-danger" lay-event="del">删除</a>
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>