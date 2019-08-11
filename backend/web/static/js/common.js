/**
 * Desc:
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/10
 * Time: 17:53
 */
var AJAX_STATUS_SUCCESS = 0;
var AJAX_STATUS_FAILD = 1;

var layer,
	$;

layui.config({
	base: "/static/frame/static/js/" //你存放新模块的目录，注意，不是layui的模块目录
});

layui.use(['jquery', 'layer', 'table', 'laydate', 'form'], function() {
	$ = layui.$,
		layer = layui.layer;

	(function ($) {
		$.fn.serializeJson = function () {
			var serializeObj = {};
			$(this.serializeArray()).each(function () {
				serializeObj[this.name] = this.value;
			});
			return serializeObj;
		};
	})($);

	layer.config({
		// time: 2000,
	});
	var laydate = layui.laydate;
	var form = layui.form;
	var table = layui.table;

	laydate.render({
		elem: '.my-input-date', //指定元素
		type: 'date',
	});
	laydate.render({
		elem: '.my-input-datetime', //指定元素
		type: 'datetime',
	});

	form.on('submit(form-search-form)', function (data) {
		var data = $('#searchForm').serializeJson();
		console.log(data);
		table.reload('dataTable', {
			where: data,
			text: '没有找到数据哦!',
			page: {
			curr: 1 //重新从第 1 页开始
		}
		});
		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});



	var loadIndex,
		csrfToken = '';
	if($('#csrfToken').length > 0) {
		csrfToken = $('#csrfToken').text();
	}
	$.ajaxSetup({
		beforeSend: function () {
			loadIndex = layer.load(0, {shade: 0.4,});
		},
		complete: function (result) {
			layer.close(loadIndex);
		},
		error: function () {
			layer.close(loadIndex);
			layer.msg('系统错误', {time: 1500});
		},
		data: {
			_csrf_token_backend_xstnet: csrfToken,
		}
	})

	// 刷新
	$('#btn-refresh').on('click', function () {
		location.reload();
	});

	/*
       自定义jquery函数，完成将form 数据转换为 json格式
*/
	$.fn.serializeJson = function() {
		var serializeObj = {};
		var array = this.serializeArray();
		// var str=this.serialize();
		$(array).each(function(){ // 遍历数组的每个元素
			if(serializeObj[this.name]){ // 判断对象中是否已经存在 name，如果存在name
				if($.isArray(serializeObj[this.name])){
					serializeObj[this.name].push(this.value); // 追加一个值 hobby : ['音乐','体育']
				}else{
					// 将元素变为 数组 ，hobby : ['音乐','体育']
					serializeObj[this.name]=[serializeObj[this.name],this.value];
				}
			}else{
				serializeObj[this.name]=this.value; // 如果元素name不存在，添加一个属性 name:value
			}
		});
		return serializeObj;
	};


});

function showDialog(title, params) {
	params = params || {};
	var attribute = $.extend({}, {
		title: title,
		type: 1, // 0-信息框，1-页面层， 2-iframe层，3-加载层， 4-tips层
		// offset: 'auto', // 居中方式
		offset: '100px', // 居中方式
		shade: 0.3, //
		closeBtn: 1, // 右上关闭按钮样式 [0, 1, 2]
		area: '500px', // 宽高
		// area: 'auto', // 宽高
		shadeClose: true, // 点击遮罩是否关闭弹层
		time: 0, // 自动关闭所需时间 0-不自动关闭
		btn: ['确认', '取消',],
		yes: function () {
			// 第一个按钮的回调
		},
		btn2: function () {
			//return false 开启该代码可禁止点击该按钮关闭
		}
	}, params);

	layer.open(attribute);
}

/**
 * 公共上传图片方法 传入 elem, url,即可
 * @param params
 */
function uploadImage(params) {
	layui.use(['upload', 'layer'], function() {
		var $ = layui.jquery;
		var upload = layui.upload;
		var config = $.extend({}, {
			elem: '.select-image-file',
			url: '/index.php?r=upload/image-file',
			data: { _csrf_token_backend_xstnet: $('#csrfToken').text() },
			before: function (obj) {
				var item = this.item;
				//预读本地文件示例，不支持ie8
				obj.preview(function (index, file, result) {
					item.prev().find('.upload-img').attr('src', result); //图片链接（base64）
				});
			},
			done: function (res) {
				var item = this.item;
				if(res.code === AJAX_STATUS_SUCCESS) {
					//上传成功
					item.prev().find('.upload-img').attr('src', '/'+res.data.file);
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
				uploadMessage.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs reload-item">重试</a>');
				uploadMessage.find('.reload-item').on('click', function () {
					uploadInst.upload();
				});
			},
			error: function () {
				this.retry();
			}

		}, params);
		var uploadInst = upload.render(config);

		return uploadInst;
	});

}

