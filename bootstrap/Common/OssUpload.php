<?php
/**
 * Oss上传图片
 */

namespace Bootstrap\Common;
use App\Tools\Oss;

class OssUpload
{

    /**
     * 单图上传oss
     * @param $fileName  string 上传的文件名
     * @param $fileSize   int   上传文件大小
     * @param $fileUrl   string 上传oss路径
     * @return array
     */
    public function upload($fileName, $fileSize = 204800, $fileUrl = '')
    {
        //判断图片是否为空上传
        if(!isset($_FILES[$fileName])){
            return ['status'=>false, 'message'=>'请选择图片','body'=>''];
        }
        $imageInfo = $_FILES[$fileName];
        if($imageInfo['error'] !== 0 || $imageInfo['size'] > $fileSize){
            return ['status'=>false, 'message'=>'图片不能大于'.ceil($fileSize/1024).'KB','body'=>''];
        }
        //奖品图
        $type   = trim(strstr($imageInfo['type'], '/'), '/');
        $opaths = $imageInfo['tmp_name'];
        $ikey   = date('Ymd').'/'.rand(1000, 9999).uniqid().'.'.$type;
        Oss::publicUpload($ikey, $opaths);   //上传oss
        $data['imageName'] = $ikey;
        $data['imageUrl']  = $fileUrl.$ikey;
        return ['status'=>true, 'message'=>'上传成功','body'=>$data];
    }

    /**
     * 多图上传oss
     * @param $fileName  string 上传的文件名
     * @param $fileSize   int   上传文件大小
     * @param $fileUrl   string 上传oss路径
     * @return array
     */
    public function moreUpload($fileName, $fileSize = 204800, $fileUrl = '')
    {
        //判断图片是否为空上传
        if(!isset($_FILES[$fileName])){
            return ['status'=>false, 'message'=>'请选择图片','body'=>''];
        }
        $imageInfo = $_FILES[$fileName];
        //判断图片是否符合上传要求
        foreach($imageInfo['error'] as $v){
            if($v != 0 ){
                return ['status'=>false, 'message'=>'图片上传失败','body'=>''];
            }
        }
        foreach($imageInfo['size'] as $v){
            if($v > $fileSize){
                return ['status'=>false, 'message'=>'图片不能大于'.ceil($fileSize/1024).'KB','body'=>''];
            }
        }
        //多图上传
        $imageUrls  = [];
        $imageNames = [];
        foreach($imageInfo['tmp_name']  as  $k => $v){
            $key1    = date('Ymd').'/'.rand(1000, 9999).uniqid().'.'.'jpeg';
            $opaths1 = $v;
            Oss::publicUpload($key1, $opaths1);   //上传oss
            $imageUrls[$k]  = $fileUrl.$key1;
            $imageNames[$k] = $key1;
        }
        $data['imageNames'] = $imageNames;
        $data['imageUrls']  = $imageUrls;
        return ['status'=>true, 'message'=>'上传成功','body'=>$data];
    }
}