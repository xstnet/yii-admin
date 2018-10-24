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
	 * @param $query \common\models\BaseModel
	 * @return array
	 */
	public static function getPage($query)
	{
		$defaultPageSie = self::$defaultPageSie;
		$page = (int) Yii::$app->request->get('page', 1);
		$pageSize = (int) Yii::$app->request->get('pageSize', $defaultPageSie);

		$page = $page < 1 ? 1 : $page;
		$pageSize = $pageSize < 1 ? $defaultPageSie : $pageSize;

		$count = $query->count();

		$offset = ($page - 1) * $pageSize;

		$query->offset($offset)->limit($pageSize);

		return [$count, $page ];
	}
}