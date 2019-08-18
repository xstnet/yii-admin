<?php
/**
 * Desc: array 助手
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/6/27
 * Time: 11:00
 */

namespace common\helpers;

use common\models\Article;

class Helpers
{
	/**
	 * @Desc: 获取分类树
	 * @param array $list 列表数据 id => data
	 * @param string $parentName
	 * @param string $childName
	 * @return array
	 */
	public static function getTree($list, $parentName = 'parent_id', $childName = 'children')
	{
		$tree = []; //格式化好的树  id => array
		foreach ($list as $item) {
			// 不是一级分类，父级分类不存在，跳过
			if ($item[ $parentName ] != 0 && !isset($list[ $item[ $parentName ] ])) {
				continue;
			}
			if (isset($list[$item[ $parentName ]])) {
				$list[$item[ $parentName ]][ $childName ][] = &$list[$item['id']];
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
	
	public static function renderArticleDirectory($tree) : string
	{
		$htmlStr = '<ul class="article-directory">';
		foreach ($tree as $item) {
			$htmlStr .= sprintf('<li><a href="#%s">%s</a></li>', $item['name'], $item['name']);
			if (isset($item['children']) && count($item['children']) > 0) {
				$htmlStr .= self::renderArticleDirectory($item['children']);
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
	
	public static function createArticleDirectory($text)
	{
		$result = [
			'', $text,
		];
		$pattern = '/<h(\d)(.*?)>(.*?)<\/h\d>/i';
		preg_match_all($pattern, $text, $match);
		//		print_r($match);
		if (empty($match[0])) {
			return $result;
		}
		
		$treeList = [];
		$id = 1; // 对匹配到的每一项定义一个ID, 从1开始
		foreach ($match[1] as $i => $level) {
			// id => item
			$treeList[$id] = [
				'id' => $id,
				'parent_id' => 0, // 上下级关系, 默认都为一级目录
				'level' => $level, // 等级, 对应 h [1,2,3,4,5,6]
				'name' => trim(strip_tags($match[3][$i])), // 标题名字
			];
			$id ++;
		}
		
		/**
		 * 按顺序处理目录对应关系, 第一个元素默认一级目录, 第二个元素开始遍历
		 * 每个元素和上一个元素的level进行比较, 分三种情况
		 *   case 1: 当 当前的level == 上个元素的level, 说明该元素和上个元素同级, 将当前元素的parent_id=上一个元素的parent_id
		 *   case 2: 当 当前的level > 上个元素的level, 说明该元素属于上个元素, 将当前元素的parent_id指向上一个元素的id
		 *   case 3: 当 当前的level < 上个元素的level, 则往上查找上个元素, 直到找到的上个元素的level等于当前的level时, parent_id=找到的元素的parent_id
		 *           如果往上没有找到, 则给予默认的一级目录
		 *
		 */
		for ($i=2; $i<=count($treeList); $i++) {
			$item = $treeList[$i];
			$prevItem = $treeList[$i-1];
			
			// case 1
			if ($item['level'] == $prevItem['level']) {
				$treeList[$i]['parent_id'] = $prevItem['parent_id'];
				continue;
			}
			// case 2
			if ($item['level'] > $prevItem['level']) {
				$treeList[$i]['parent_id'] = $prevItem['id'];
				continue;
			}
			
			$parentId = 0;
			
			// case 3
			while ($item['level'] <= $prevItem['level']) {
				$parentId = $prevItem['parent_id'];
				if (!isset($treeList[($prevItem['id'] - 1)])) {
					break;
				}
				$prevItem = $treeList[($prevItem['id'] - 1)];
			}
			$treeList[$i]['parent_id'] = $parentId;
		}
		$treeList = self::getTree($treeList);
		$directoryHtml = self::renderArticleDirectory($treeList);
		
		$text = preg_replace_callback(
			$pattern,
			function ($m) {
				$name = trim(strip_tags($m[3]));
				return "<h$m[1] name='$name' id='$name'>" . $m[3] . "</h$m[1]>";
			},
			$text
		);
		
		return [$directoryHtml, $text];
	}
}