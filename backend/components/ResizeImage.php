<?php
namespace backend\components;

use Yii;

class ResizeImage {
    public function getThumb($file_path,$dir,$ext,$width=100,$height=100,$thumbname=false){
        $show_pic_scal = $this->show_pic_scal($width,$height,$file_path);
        $this->resize($file_path,$show_pic_scal[0],$show_pic_scal[1],$thumbname); 
        if($thumbname){
            $imgPath_thumb =  $dir.'.thumb.thumb.jpg';
        }else{
            $imgPath_thumb =  $dir.'.thumb.jpg';
        }
        return $imgPath_thumb;
    }
    public function show_pic_scal($width, $height, $picpath) {     
        $imginfo = GetImageSize ( $picpath );     
        $imgw = $imginfo [0];     
        $imgh = $imginfo [1];     
             
        $ra = number_format ( ($imgw / $imgh), 1 ); //宽高比     
        $ra2 = number_format ( ($imgh / $imgw), 1 ); //高宽比     
             
        
        if ($imgw > $width or $imgh > $height) {     
            if ($imgw > $imgh) {     
                $newWidth = $width;     
                $newHeight = round ( $newWidth / $ra );     
                 
            } elseif ($imgw < $imgh) {     
                $newHeight = $height;     
                $newWidth = round ( $newHeight / $ra2 );     
            } else {     
                $newWidth = $width;     
                $newHeight = round ( $newWidth / $ra );     
            }     
        } else {     
            $newHeight = $imgh;     
            $newWidth = $imgw;     
        }     
        $newsize [0] = $newWidth;     
        $newsize [1] = $newHeight;     
             
        return $newsize;     
    }     
    
    
    
    public function getImageInfo($src)     
    {     
        return getimagesize($src);     
    }     
    /**   
    * 创建图片，返回资源类型   
    * @param string $src 图片路径   
    * @return resource $im 返回资源类型    
    * **/    
    public function create($src)     
    {     
        $info=$this->getImageInfo($src);     
        switch ($info[2])     
        {     
            case 1:     
                $im=imagecreatefromgif($src);     
                break;     
            case 2:     
                $im=imagecreatefromjpeg($src);     
                break;     
            case 3:     
                $im=imagecreatefrompng($src);     
                break;     
        }     
        return $im;     
    }     
    /**   
    * 缩略图主函数   
    * @param string $src 图片路径   
    * @param int $w 缩略图宽度   
    * @param int $h 缩略图高度   
    * @return mixed 返回缩略图路径   
    * **/    
    
    public function resize($src,$w,$h,$thumb=false)     
    {     
        $temp=pathinfo($src);     
        $name=$temp["basename"];//文件名     
        $dir=$temp["dirname"];//文件所在的文件夹     
        $extension=$temp["extension"];//文件扩展名     
        $savepath="{$dir}/{$name}.thumb.jpg";//缩略图保存路径,新的文件名为*.thumb.jpg     
        if($thumb){
            $savepath="{$dir}/{$name}.thumb.thumb.jpg";
        }
        //获取图片的基本信息     
        $info=$this->getImageInfo($src);     
        $width=$info[0];//获取图片宽度     
        $height=$info[1];//获取图片高度     
        $per1=round($width/$height,2);//计算原图长宽比     
        $per2=round($w/$h,2);//计算缩略图长宽比     
        
        //计算缩放比例     
        if($per1>$per2||$per1==$per2)     
        {     
            //原图长宽比大于或者等于缩略图长宽比，则按照宽度优先     
            $per=$w/$width;     
        }     
        if($per1<$per2)     
        {     
            //原图长宽比小于缩略图长宽比，则按照高度优先     
            $per=$h/$height;     
        }     
        $temp_w=intval($width*$per);//计算原图缩放后的宽度     
        $temp_h=intval($height*$per);//计算原图缩放后的高度     
        $temp_img=imagecreatetruecolor($temp_w,$temp_h);//创建画布     
        $im=$this->create($src);     
        imagecopyresampled($temp_img,$im,0,0,0,0,$temp_w,$temp_h,$width,$height);     
        if($per1>$per2)     
        {     
            imagejpeg($temp_img,$savepath, 100);     
            imagedestroy($im);     
            return $this->addBg($savepath,$w,$h,"w");     
            //宽度优先，在缩放之后高度不足的情况下补上背景     
        }     
        if($per1==$per2)     
        {     
            imagejpeg($temp_img,$savepath, 100);     
            imagedestroy($im);     
            return $savepath;     
            //等比缩放     
        }     
        if($per1<$per2)     
        {     
            imagejpeg($temp_img,$savepath, 100);     
            imagedestroy($im);     
            return $this->addBg($savepath,$w,$h,"h");     
            //高度优先，在缩放之后宽度不足的情况下补上背景     
        }     
    }     
    /**   
    * 添加背景   
    * @param string $src 图片路径   
    * @param int $w 背景图像宽度   
    * @param int $h 背景图像高度   
    * @param String $first 决定图像最终位置的，w 宽度优先 h 高度优先 wh:等比   
    * @return 返回加上背景的图片   
    * **/    
    public function addBg($src,$w,$h,$fisrt="w")     
    {     
        $bg=imagecreatetruecolor($w,$h);     
        $white = imagecolorallocate($bg,255,255,255);     
        imagefill($bg,0,0,$white);//填充背景     
        
        //获取目标图片信息     
        $info=$this->getImageInfo($src);     
        $width=$info[0];//目标图片宽度     
        $height=$info[1];//目标图片高度     
        $img=$this->create($src);     
        if($fisrt=="wh")     
        {     
            //等比缩放     
            return $src;     
        }     
        else    
        {     
            if($fisrt=="w")     
            {     
                $x=0;     
                $y=($h-$height)/2;//垂直居中     
            }     
            if($fisrt=="h")     
            {     
                $x=($w-$width)/2;//水平居中     
                $y=0;     
            }     
            imagecopymerge($bg,$img,$x,$y,0,0,$width,$height,100);     
            imagejpeg($bg,$src,100);     
            imagedestroy($bg);     
            imagedestroy($img);     
            return $src;     
        }     
        
    }     
    
}
