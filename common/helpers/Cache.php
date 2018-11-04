<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/11/3
 * Time: 19:58
 */

namespace common\helpers;

use backend\services\setting\SettingService;
use Yii;


class Cache
{
	public function get($name)
	{
		$ret = Yii::$app->cache->get($name);
		if ($ret === false) {
			$funName = 'get' . ucfirst($name);
			if (method_exists ($this, $funName)) {
				$ret = $this->$funName();
			}
		}

		return $ret;
	}

	public function set($key, $value)
	{
		Yii::$app->cache->set($key, $value);
	}

	public function refresh($name)
	{
		Yii::$app->cache->delete($name);
	}

	public function getSetting()
	{
		$setting = SettingService::instance()->getSettingDataToCache();
		$this->set('setting', $setting);
		return $setting;
	}

}