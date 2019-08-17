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
	
	public static function renderArticleDirectory($id, $tree) : string
	{
		$htmlStr = '<ul class="article-directory">';
		foreach ($tree as $item) {
			$htmlStr .= sprintf('<li><a href="/article-%d.html#%s">%s</a></li>', $id, $item['name'], $item['name']);
			if (isset($item['children']) && count($item['children']) > 0) {
				$htmlStr .= self::renderArticleDirectory($id, $item['children']);
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
	
	public static function createArticleDirectory($articleId, $text)
	{
		$result = [
			'', $text,
		];
		preg_match_all('#<h(\d) .*>(.*)</h\d>#i', $text, $match);
		//		print_r($match);
		if (empty($match[0])) {
			return $result;
		}
		$treeList = [];
		$id = 1;
		foreach ($match[1] as $i => $level) {
			$treeList[$id] = [
				'id' => $id,
				'parent_id' => 0,
				'level' => $level,
				'name' => $match[2][$i],
			];
			$id ++;
		}
		
		for ($i=2; $i<=count($treeList); $i++) {
			$item = $treeList[$i];
			$prevItem = $treeList[$i-1];
			
			if ($item['level'] == $prevItem['level']) {
				$treeList[$i]['parent_id'] = $prevItem['parent_id'];
				continue;
			}
			if ($item['level'] > $prevItem['level']) {
				$treeList[$i]['parent_id'] = $prevItem['id'];
				continue;
			}
			
			
			$parentId = 0;
			
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
		$directoryHtml = self::renderArticleDirectory($articleId, $treeList);
		
		$text = preg_replace('#<h(\d) (.*)>(.*)</h\d>#i', '<h${1} name="${3}" id="${3}">${3}</h${1}>', $text);
		
		return [$directoryHtml, $text];
	}
}