<?php
/**
 * Created by PhpStorm.
 * User: CHARLES
 * Date: 2017/10/3
 * Time: 11:59
 */
namespace frontend\widgets\comment;

use Yii;
use yii\base\Widget;
use frontend\models\comment\UserCommentFront;
use common\models\UserReplyComment;
use common\models\UserCenter;
use frontend\models\article\ArticleFront;

/**
 * 评论组件
 */

Class CommentWidget extends Widget
{
    public function run()
    {
	$id = $_GET['id'];
        $cid =$_GET['cid'];
        $model = ArticleFront::find()->where(['id'=>$id,'cid'=>$cid])->one();
        if(!$model){
                Yii::$app->session->setFlash('error','不存在该文章');
                return false;
        }
	$result = UserCommentFront::getCommentData($id);
        $comment_nums = UserCommentFront::getCommentNums($id);
	$session = Yii::$app->session;
	$headimg = $session['UserLogin']['head_img'];
        return $this->render('index',['result'=>$result,'nums'=>$comment_nums,'headimg'=>$headimg]);
    }
}
