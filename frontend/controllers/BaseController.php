<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

/**
 * Site controller
 */
class BaseController extends Controller
{
	/**
	 *
	 * @param \yii\db\ActiveQuery $query
	 * @param int $pageSize
	 * @return array
	 */
	public function getPage($query, $pageSize = 10)
	{
		$page = (int) Yii::$app->request->get('page', 1);
		$page = $page < 1 ? 1 : $page;
		$count = $query->count();
		
		$offset = ($page - 1) * $pageSize;
		
		$query->offset($offset)->limit($pageSize);
		
		$pages = new Pagination(['totalCount' => $count]);
		
		return [$count, $pages];
	}
	
	/**
	 * 二次处理返回的Html
	 * @param string $content
	 * @return string
	 */
	public function renderContent($content)
	{
		return $content = parent::renderContent($content);
		
		return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</","//","'/\*[^*]*\*/'","/\r\n/","/\n/","/\t/",'/>[ ]+</'),array(">\\1<",'','','','','','><'), $content)));
	}

}
