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
	base: "./static/backend/frame/static/js/" //你存放新模块的目录，注意，不是layui的模块目录
});

layui.use(['jquery', 'layer'], function(){
	$ = layui.$,
		layer = layui.layer;
	layer.config({
		// time: 2000,
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

