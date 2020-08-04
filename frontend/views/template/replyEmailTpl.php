<?php
/**
 * Created by PhpStorm
 * Author: Shantong Xu <shantongxu@qq.com>
 * Date: 2020/8/4
 * Time: 8:50 下午
 */


use yii\helpers\Html;

$articleUrl = sprintf("//%s/article-%d.html", Yii::$app->request->hostName, $articleId);
?>


<div>
    <br>
    <div>
        <span style="color: #1f6295"><?=$username?></span> 回复了您的<?=($toOwner?'文章' : '留言')?>: &nbsp;&nbsp;<a target="_blank" href="<?=$articleUrl?>"><?=$articleTitle?></a>
    </div>
    <br>
    <div>回复内容:</div>
    <hr>
    <div>
        <?=$replyContent?>
    </div>
    <br>
    <br>
    <div>
        <a href="<?=$articleUrl?>" target="_blank">点此查看</a>
    </div>
</div>
