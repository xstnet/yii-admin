/**
 * Created by Administrator on 2017/5/20.
 * @name:   vip-admin 后台模板 菜单navJS
 * @author: 随丶
 */
layui.define(['layer', 'element'], function (exports) {
    // 操作对象
    var layer = layui.layer
        , element = layui.element
        , $ = layui.jquery;

    // 封装方法
    var mod = {
        // 添加 HTMl
        addHtml: function (addr, obj, treeStatus, data) {
            // 请求数据
            $.get(addr, data, function (res) {
                var view = "";
                if (res.data) {
                    $(res.data).each(function (k, v) {
                        v.subset && treeStatus ? view += '<li class="layui-nav-item layui-nav-itemed">' : view += '<li class="layui-nav-item">';
						if (v.children) {
							view += '<a href="javascript:;"><i class="layui-icon '+ v.icon + '"></i>' + v.label + '</a><dl class="layui-nav-child">';
							$(v.children).each(function (ko, vo) {
								vo.href = vo.children ? '' : vo.href;
								view += '<dd>';
								if(vo.target){
									view += '<a href="' + vo.href + '" target="_blank">';
								} else {
									view += '<a href="javascript:;" href-url="' + vo.href + '">';
								}
								view += '<i class="layui-icon '+ vo.icon +'"></i>' + vo.label + '</a>';

								if (vo.children) {
									view += '<dl class="layui-nav-child">';
									$(vo.children).each(function (k3, v3) {
										view += '<dd style="padding-left: 20px">';
										v3.href = v3.children ? '' : v3.href;
										if(v3.target){
											view += '<a href="' + v3.href + '" target="_blank">';
										} else {
											view += '<a href="javascript:;" href-url="' + v3.href + '">';
										}
										view += '<i class="layui-icon '+ v3.icon +'"></i>' + v3.label + '</a>';
										if (v3.children) {
											view += '<dl class="layui-nav-child">';
											$(v3.children).each(function (k4, v4) {
												view += '<dd style="padding-left: 20px">';
												v4.href = v4.children ? '' : v4.href;
												if(v4.target){
													view += '<a href="' + v4.href + '" target="_blank">';
												} else {
													view += '<a href="javascript:;" href-url="' + v4.href + '">';
												}
												view += '<i class="layui-icon '+ v4.icon +'"></i>' + v4.label + '</a>';
												view += '</dd>';
											});
											view += '</dl>';
										}
										view += '</dd>';
									});
									view += '</dl>';
								}
								view += '</dd>';
							});
							view += '<dl>';
						} else {
							if (v.target) {
								view += '<a href="' + v.url + '" target="_blank">';
							} else {
								view += '<a href="javascript:;" href-url="' + v.url + '">';
							}
							view += '<i class="layui-icon '+v.icon+'"></i>' + v.label + '</a>';
						}
                        view += '</li>';
                    });
                } else {
                    layer.msg('接受的菜单数据不符合规范,无法解析');
                }
                // 添加到 HTML
                $(document).find(".layui-nav[lay-filter=" + obj + "]").html(view);
                // 更新渲染
                element.init();
            },'json');
        }
        // 左侧主体菜单 [请求地址,过滤ID,是否展开,携带参数]
        , main: function (addr, obj, treeStatus, data) {
            // 添加HTML
            this.addHtml(addr, obj, treeStatus, data);
        }
        // 顶部左侧菜单 [请求地址,过滤ID,是否展开,携带参数]
        , top_left: function (addr, obj, treeStatus, data) {
            // 添加HTML
            this.addHtml(addr, obj, treeStatus, data);
        }
        /*// 顶部右侧菜单
         ,top_right: function(){

         }*/
    };

    // 输出
    exports('vip_nav', mod);
});


