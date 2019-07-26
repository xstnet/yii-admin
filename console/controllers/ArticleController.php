<?php
/**
 *
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-26
 * Time: 上午10:00
 */
namespace console\controllers;

use Yii;
use common\models\Article;
use common\models\ArticleTag;

class ArticleController extends BaseController
{
	public function actionInitArticleTag()
	{
		$articleList = Article::find()
			->select(['keyword', 'created_at', 'updated_at'])
			->where(['<>', 'keyword', ''])
			->orderBy(['created_at' => SORT_ASC])
			->asArray()
			->all();
		
		foreach ($articleList as $item) {
			if (empty(trim($item['keyword']))) {
				continue;
			}
			$tags = explode(',', trim($item['keyword']));
			foreach ($tags as $tag) {
				Yii::$app->db->createCommand()->upsert(ArticleTag::tableName(), [
					'name' => $tag,
					'article_count' => 1,
					'is_show' => ArticleTag::IS_SHOW_YES,
					'created_at' => $item['created_at'],
					'updated_at' => $item['updated_at'],
				], [
					'article_count' => new \yii\db\Expression('article_count + 1'),
				])->execute();
			}
		}
		
		echo '初始化标签完成' . PHP_EOL;
	}
}