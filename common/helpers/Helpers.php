<?php
/**
 * Desc: array 助手
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/6/27
 * Time: 11:00
 */

namespace common\helpers;

class Helpers
{
	/**
	 * @Desc: 获取分类树
	 * @param $list 列表数据 id => data
	 * @param string $parrentName
	 * @param string $childName
	 * @return array
	 */
	public static function getTree($list, $parrentName = 'parent_id', $childName = 'children')
	{
		$tree = []; //格式化好的树  id => array
		foreach ($list as $item) {
			// 不是一级分类，父级分类不存在，跳过
			if ($item[ $parrentName ] != 0 && !isset($list[ $item[ $parrentName ] ])) {
				continue;
			}
			if (isset($list[$item[ $parrentName ]])) {
				$list[$item[ $parrentName ]][ $childName ][] = &$list[$item['id']];
			} else {
				$tree[] = &$list[$item['id']];
			}
		}

		return $tree;
	}

	public static function getTreeSelect($data, $splitStr = '')
	{
		$htmlStr = '';
		foreach ($data as $item) {
			$htmlStr .= sprintf('<option value="%s">%s%s</option>', $item['id'], $splitStr, $item['label']);
			if (isset($item['children']) && count($item['children']) > 0) {
				$htmlStr .= self::getTreeSelect($item['children'], $splitStr. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}
		}
		return $htmlStr;
	}
	
	public static function renderCategoryTree($tree) : string
	{
		$htmlStr = '<ul>';
		foreach ($tree as $item) {
			$htmlStr .= sprintf('<li><a href="/category-%s.html">%s</a></li>', $item['id'], $item['category_name']);
			if (isset($item['children']) && count($item['children']) > 0) {
				$htmlStr .= self::renderCategoryTree($item['children']);
			}
		}
		$htmlStr .= '</ul>';
		
		return $htmlStr;
	}
	
	/**
	 * 渲染面包屑导航
	 * @param $breadcrumb
	 * @return string
	 */
	public static function renderBreadcrumb($breadcrumb) : string
	{
		if (!is_array($breadcrumb) || count($breadcrumb) <= 0) {
			return '';
		}
		
		$count = count($breadcrumb);
		$html = '<ol class="breadcrumb">';
		foreach ($breadcrumb as $key => $vo) {
			$item = $vo['name'];
			if (!empty($vo['href'])) {
				$item = sprintf('<a href="%s">%s</a>', $vo['href'], $vo['name']);
			}
			$html .= sprintf('<li%s>%s</li>', ($key == ($count-1) ? ' class="active"' : ''), $item);
		}
		$html .= '</ol><hr class="hr">';
		
		return $html;
	}
}