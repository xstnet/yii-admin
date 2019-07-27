<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/11/3
 * Time: 19:58
 */

namespace common\helpers;

use common\models\Article;
use Yii;
use yii\filters\PageCache;


class ArticlePageCache extends PageCache
{
	public function afterRestoreResponse($data)
	{
		// 更新文章阅读数量
		if (!Yii::$app->request->isAjax) {
			$id = (int) Yii::$app->request->get('id' , 0);
			Yii::$app->db->createCommand()->update(Article::tableName(), ['hits' => new \yii\db\Expression('hits + 1')], 'id='. $id)->execute();
		}
	}
}