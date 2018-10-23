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
				$htmlStr .= self::getTreeSelect($item['children'], $splitStr. '++++');
			}
		}
		return $htmlStr;
	}
}