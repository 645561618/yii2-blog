<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use backend\components\ResizeImage;

class Upload{

    public function uploadImage($file,$ifthumb=false,$newdir=false){
        $attachDir = Yii::$app->params['attachDir'];
        $dir = '/order/'.'day_'.date('ymd').'/';
        if($newdir){
            $dir = '/'.$newdir.'/day_'.date('ymd').'/';
        }
        $save_path = $attachDir . $dir;
         //var_dump($save_path);exit;
        if(!is_dir($save_path)){
            @mkdir($save_path,0777,true);
        }
	//print_r($file['upload']['tmp_name']);exit;
        $filetype = $this->getImageBufferExt($file['upload']['tmp_name']);
        $error = $this->showError($file,$filetype);
        if($error){
            return $error;
        }
        $md5 = md5_file($file['upload']['tmp_name']);
        $new_file_name = date("Ymd") . '_' . substr($md5,0,7) . '.' . $filetype;
        $file_path = $save_path . $new_file_name;
	//echo $file_path;exit;
        $url = Yii::$app->params['targetDomain']. $dir.$new_file_name;
        $model = new UploadedFile;
        $model->tempName=$file['upload']['tmp_name'];
        if($model->saveAs($file_path)){
            $realpath = $dir.$new_file_name;
            return [true,$url,$realpath];
        }else{
            return [false,"文件上传失败，请检查上传目录设置和目录读写权限"];
        }
    }



    public function uploadImages($files,$ifthumb=false,$newdir=false){
	foreach	($files['upload']['tmp_name'] as $tmp_name){
        $attachDir = Yii::$app->params['attachDir'];
        $dir = '/order/'.'day_'.date('ymd').'/';
        if($newdir){
            $dir = '/'.$newdir.'/day_'.date('ymd').'/';
        }
        $save_path = $attachDir . $dir;
         //var_dump($save_path);exit;
        if(!is_dir($save_path)){
            @mkdir($save_path,0777,true);
        }   
        //print_r($file['upload']['tmp_name']);exit;
        $filetype = $this->getImageBufferExt($tmp_name);
        //$error = $this->showError($file,$filetype);
       // if($error){
         //   return $error;
        //}
        $md5 = md5_file($tmp_name);
        $new_file_name = date("Ymd") . '_' . substr($md5,0,7) . '.' . $filetype;
        $file_path = $save_path . $new_file_name;
        //echo $file_path;exit;
        $url = Yii::$app->params['targetDomain']. $dir.$new_file_name;
        $model = new UploadedFile;
        $model->tempName=$tmp_name;
        if($model->saveAs($file_path)){
            $realpath = $dir.$new_file_name;
            return [true,$url,$realpath];
        }else{ 
            return [false,"文件上传失败，请检查上传目录设置和目录读写权限"];
        }
	}
    }






    public function uploadArticle($file,$ifthumb=false,$newdir=false){
        $attachDir = Yii::$app->params['attachDir'];
        $dir = '/article/'.'day_'.date('ymd').'/';
        if($newdir){
            $dir = '/'.$newdir.'/day_'.date('ymd').'/';
        }
        $save_path = $attachDir . $dir;
        // var_dump($save_path);exit;
        if(!is_dir($save_path)){
            @mkdir($save_path,0777,true);
        }
        $filetype = $this->getImageBufferExt($file['upload']['tmp_name']);
        $error = $this->showError($file,$filetype);
        if($error){
            return $error;
        }
        $md5 = md5_file($file['upload']['tmp_name']);
        $new_file_name = date("Ymd") . '_' . substr($md5,0,7) . '.' . $filetype;
        $file_path = $save_path . $new_file_name;
        $url = Yii::$app->params['targetDomain']. $dir.$new_file_name;
        $model = new UploadedFile;
        $model->tempName=$file['upload']['tmp_name'];
        if($model->saveAs($file_path)){
            $realpath = $dir.$new_file_name;
            $oss_file_path = 'blog/'.$new_file_name;
            $oss_url = Yii::$app->AliyunOss->upload($oss_file_path,$file_path);
            if($ifthumb){
                $resize = new ResizeImage;
                $thumb1 = $resize->getThumb($file_path,$realpath,$filetype,400,400);
                $thumb2 = $resize->getThumb($file_path,$realpath,$filetype,200,60,true);
                $url = Yii::$app->params['targetDomain'].$thumb1;
                return [true,$url,$thumb1,$thumb2];
            }
            return [true,$url,$realpath];
            // $this->mkhtml($fn,$url,"上传成功");
        }else{
            return [false,"文件上传失败，请检查上传目录设置和目录读写权限"];
            // $this->mkhtml($fn,"","文件上传失败，请检查上传目录设置和目录读写权限");
        }
    }

    
    public function uploadWx($file,$ifthumb=false,$newdir=false){
        $attachDir = Yii::$app->params['attachDir'];
        $dir = '/wx/'.'day_'.date('ymd').'/';
        if($newdir){
            $dir = '/'.$newdir.'/day_'.date('ymd').'/';
        }
        $save_path = $attachDir . $dir;
         //var_dump($save_path);exit;
        if(!is_dir($save_path)){
            @mkdir($save_path,0777,true);
        }
        //print_r($file['upload']['tmp_name']);exit;
        $filetype = $this->getImageBufferExt($file['upload']['tmp_name']);
        $error = $this->showError($file,$filetype);
        if($error){
            return $error;
        }
        $md5 = md5_file($file['upload']['tmp_name']);
        $new_file_name = date("Ymd") . '_' . substr($md5,0,7) . '.' . $filetype;
        $file_path = $save_path . $new_file_name;
        //echo $file_path;exit;
        $url = Yii::$app->params['targetDomain']. $dir.$new_file_name;
        $model = new UploadedFile;
        $model->tempName=$file['upload']['tmp_name'];
        if($model->saveAs($file_path)){
            $realpath = $dir.$new_file_name;
            return [true,$url,$realpath];
        }else{
            return [false,"文件上传失败，请检查上传目录设置和目录读写权限"];
        }
    }









    public function getImageBufferExt($buffer) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $buffer) ;
        switch ($mime) {
            case 'image/png':
            case 'image/x-png':
                return "png";
            case 'image/pjpeg':
            case 'image/jpeg':
                return "jpg";
            case 'image/gif':
                return "gif";
            default:
                return "";
        }
    }

    public function showError($file,$filetype){
        $type = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        if(!in_array($filetype,$type)){
            return [false,"错误的文件类型！"];
        }
        if($file['upload']['size']>5*1024*1024){
            return [false,"上传的文件不能超过5M"];
        }
        return false;
    }


}
