<?php
/**
 * Desc: 公共service
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/9
 * Time: 21:23
 */

namespace backend\services;
use Yii;


class BaseService
{
	protected static $_instance = [];

	public static $defaultPageSie = 20;

	/**
	 * @Desc: 创建实例
	 * @param array $params
	 * @return static
	 * @throws \yii\base\InvalidConfigException
	 */
	public static function instance($params = [])
	{
		$className = get_called_class();
		if (!isset(static::$_instance[ $className] )) {
			$params['class'] = $className;
			static::$_instance[ $className ] = \Yii::createObject($params);
		}

		return static::$_instance[ $className ];
	}

	/**
	 * @Desc: 获取分页
	 * @param $query \yii\db\ActiveQuery
	 * @param array $searchFields
	 * @return array
	 */
	public static function getPageAndSearch($query, $searchFields = [])
	{
		$defaultPageSie = self::$defaultPageSie;
		$page = (int) Yii::$app->request->get('page', 1);
		$pageSize = (int) max(Yii::$app->request->get('limit', self::$defaultPageSie), Yii::$app->request->get('pageSize', $defaultPageSie));

		$page = $page < 1 ? 1 : $page;
		$pageSize = $pageSize < 1 ? $defaultPageSie : $pageSize;
		
		if (!empty($searchFields)) {
			$where = self::buildSearchFields($searchFields);
			$query->andWhere($where);
		}

		$count = $query->count();

		$offset = ($page - 1) * $pageSize;

		$query->offset($offset)->limit($pageSize);

		return [$count, $page ];
	}
	
	public static function buildSearchFields(array $searchFields = []) : array
	{
		$get = Yii::$app->request->get();
		$where = [];
		foreach ($searchFields as $name => $item) {
			if (isset($get[$name]) && !empty($get[$name])) {
				$value = $get[$name];
				$field = $name;
				if (!empty($item['field'])) {
					$field = $item['field'];
				}
				if ($item['type'] == 'date' || $item['type'] == 'datetime') {
					$value = strtotime($value);
					if (empty($value)) {
						continue;
					}
				}
				if (!empty($field['format'])) {
					$value = call_user_func($item['format'], $value);
//					$value = $item['format']($value);
				}
				$where[] = [$item['condition'], $field, $value];
			}
		}
		if (!empty($where)) {
			array_unshift($where, 'and');
		}
		
		return $where;
	}
}