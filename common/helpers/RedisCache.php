<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/11/3
 * Time: 19:58
 */

namespace common\helpers;

use Yii;


class RedisCache extends \yii\redis\Cache
{

	public function useDefault()
	{
		$this->redis->select(Yii::$app->params['redis_database']['default']);
	}
	
	/**
	 *
	 * @param string|int $key
	 * @return bool
	 */
	public function use($key = NULL)
	{
		if ($key === null) {
			$this->useDefault();
			return true;
		}
		if (is_int($key)) {
			$this->redis->select(1);
			return true;
		}
		if (is_string($key)) {
			if (isset(Yii::$app->params['redis_database'][$key])) {
				$this->redis->select(Yii::$app->params['redis_database'][$key]);
				return true;
			}
		}
		
		return false;
	}
}