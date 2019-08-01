<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/5/12
 * Time: 20:48
 */

namespace backend\services\systemLog;


use backend\services\BaseService;
use common\exceptions\ParameterException;
use common\models\SystemLog;
use Yii;

class SystemLogService extends BaseService implements SystemLogServiceInterface
{

	/**
	 * @Desc: 获取操作日志列表
	 * @return array
	 */
	public function getLogs()
	{
		$query = SystemLog::find();

		list ($count, $page) = self::getPageAndSearch($query);

		$list = $query->asArray()
			->orderBy('created_at desc')
			->all();

		$result = [
			'total' => $count,
			'list' => $list,
			'page' => $page,
		];

		return $result;
	}

}