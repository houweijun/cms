<?PHP

/**
 * 上传图片
 */

namespace Bootstrap\Common;

class Upload
{
    public $formName;        //上传的数据
    public $fileType = array("jpg",'png','gif','jpeg');//允许上传格式
    public $maxSize = "6097152";  //默认为2097152字节  2M
    public $small_size = "1";//最小1字节
    public $format = "B";//获取文件大小的类型
    public $directory = "/Upload";        //文件上传至目录
    public $doUpFile;        //上传的文件名
    public $sm_File;                //缩略图名称
    private $dirPath;              //生成目录的路径

    private static $prefixDirsPsr4 = [];
    //上传文件
    public function upload(){
        if (@$this->formName['tmp_name'])//判断是否允许上传
        {
            $getExt = strtolower($this->getExt());//获取文件类型 并转为小写
            if(in_array($getExt,$this->fileType)){//判断文件类型是否存在
                $GetSize = $this->getSize($this->format);//获取文件大小
                if ($GetSize >= $this->maxSize || $GetSize < $this->small_size) {//判断文件是否超出指定大小
                    die(json_encode(['message'=>"文件大小不符",'result'=>0],JSON_UNESCAPED_UNICODE));
                } else {
                    $_newName = $this->newName();//获取新文件名称
                    $_ext = $getExt;
                    $dir = rtrim($this->directory).'/'.date('Y-m').'/'.date('Ymd');  //创建子目录
                    if(!file_exists($dir) || !is_dir($dir)){
                        mkdir ($dir,0777,true);
                    }
                    $file1 = date('Y-m').'/'.date('Ymd');
                    $this->dirPath = $dir;
                    $_doUpload = move_uploaded_file($this->formName['tmp_name'], $dir.'/'. $_newName . "." . $_ext);
                    if ($_doUpload) {
                        $this->doUpFile = $_newName;
                        $filepath = $file1.'/'.$_newName.'.'.$_ext;
                        return ['new_name'=>$_newName.".".$_ext,"ext"=>$_ext,"dirPath"=>$this->dirPath,'filepath'=>$filepath,"result"=>1];//上传成功
                    }else{
                        return ['message'=>"文件上传失败，请重新上传。",'result'=>0];
                    }
                }
            }else{
                return ['message'=>"文件类型不允许",'result'=>0];
            }
        }else{
            return ['message'=>"文件出错，请重新上传。",'result'=>0];
        }
    }

    /**多文件上传**/

    public function UploadAllFile()
    {
        $file = $this->formName;//需要上传的文件
        if (is_array($file)){
            for ($i=0; $i<=count($file["tmp_name"])-1;$i++){
                $FileData["name"] = $file["name"][$i];//文件原名称
                $FileData["type"] = $file["type"][$i];//文件类型
                $FileData["tmp_name"] = $file["tmp_name"][$i];//文件类型
                $FileData["error"] = $file["error"][$i];//文件类型
                $FileData["size"] = $file["size"][$i];//文件类型
                $this->formName = $FileData;
                $res = $this->upload();//上传文件
                if ($res["result"] != 1){
                    return ["message"=>$res["message"],"result"=>$res["result"]];
                }else{
                    unset($res["result"]);
                    $ResData[] = $res;
                }
            }
            return ["data"=>$ResData,"result"=>1];
        }else{
            return ["message"=>"此类型为多图上传","result"=>0];
        }
    }

    //获取文件大小

    public function getSize($_format)
    {
        if (0 == $this->formName['size']) {
            return ['message'=>"文件大小不正确","result"=>0];
        }
        switch ($_format){
            case 'B':
                return $this->formName['size'];
                break;
            case 'K':
                return round($this->formName['size'] / 1024);
                break;
            case 'M':
                return round($this->formName['size'] / (1024*1024),2);
                break;
        }
    }

    //获取文件类型
    
    public function getExt()
    {
        $type = $this->formName['name'];
        $typeArr = explode('.',$type);
        return $typeArr[1];
    }



    //新建文件名

    public function newName()
    {
        return rand(1111,9999).date('YmdHis').rand(0,9);
    }

    //获取文件名称

    public function getName()
    {
        if ($this->canUpload) {
            return $this->formName['name'];
        }
    }



    /**
     *  创建缩略图
     * @param $OldImagePath
     * @param $NewImagePath
     * @param int $NewWidth
     * @param int $NewHeight
     * @return bool|string
     */

    public function thumb($OldImagePath, $NewImagePath, $NewWidth=251, $NewHeight=251)
    {
        $this->formName = $OldImagePath;
        $oldimg = $OldImagePath['tmp_name'];
        $src_image = imagecreatefromstring(file_get_contents($oldimg));
        $src_width = imagesx($src_image);
        $src_height = imagesy($src_image);

        //生成等比例的缩略图
        $tmp_image_width = 0;
        $tmp_image_height = 0;
        if ($src_width / $src_height >= $NewWidth / $NewHeight) {
            $tmp_image_width = $NewWidth;
            $tmp_image_height = round($tmp_image_width * $src_height / $src_width);
        } else {
            $tmp_image_height = $NewHeight;
            $tmp_image_width = round($tmp_image_height * $src_width / $src_height);
        }
        $tmpImage = imagecreatetruecolor($tmp_image_width, $tmp_image_height);
        imagecopyresampled($tmpImage, $src_image, 0, 0, 0, 0, $tmp_image_width, $tmp_image_height, $src_width, $src_height);

        //添加白边
        $final_image = imagecreatetruecolor($NewWidth, $NewHeight);
        $color = imagecolorallocate($final_image, 255, 255, 255);
        imagefill($final_image, 0, 0, $color);
        $x = round(($NewWidth - $tmp_image_width) / 2);
        $y = round(($NewHeight - $tmp_image_height) / 2);
        imagecopy($final_image, $tmpImage, $x, $y, 0, 0, $tmp_image_width, $tmp_image_height);

        //保存文件

        $file_name = $this->newName().".".$this->getExt();//缩略图名称
        $SamllPostion = $NewImagePath.$file_name;
        $res = imagejpeg($tmpImage, $SamllPostion , 100);

        //结束图形
        
        if($res){
            imagedestroy($tmpImage);
            return ['new_name'=>$file_name,"directory"=>$NewImagePath,
                "result"=>1, 'filename' => $NewImagePath.$file_name];//上传成功
        }else{
            return ['message'=>"生成图片失败",'result'=>0];
        }
    }

    //多图上传缩放
    public function thumbAll($OldImagePath, $NewImagePath, $NewWidth=251, $NewHeight=251)
    {

        $this->formName = $OldImagePath;
        $file = $this->formName;//需要上传的文件
        $NewWidth = $NewWidth;
        $NewHeight = $NewHeight;
        if (is_array($file)){
            for ($i=0; $i<=count($file["tmp_name"])-1;$i++){
                $FileData["name"] = $file["name"][$i];//文件原名称
                $FileData["type"] = $file["type"][$i];//文件类型
                $FileData["tmp_name"] = $file["tmp_name"][$i];//文件类型
                $FileData["error"] = $file["error"][$i];//文件类型
                $FileData["size"] = $file["size"][$i];//文件类型
//                $this->formName = $FileData;
                $res = $this->thumb($FileData, $NewImagePath, $NewWidth, $NewHeight);//上传文件
                if ($res["result"] != 1){
                    return ["message"=>$res["message"],"result"=>$res["result"]];
                }else{
                    unset($res["result"]);
                    $ResData[] = $res;
                }
            }
            return ["data"=>$ResData,"result"=>1];
        }else{
            return ["message"=>"此类型为多图上传","result"=>0];
        }
    }

    //得到上传后的文件名

    public function getUpFile()
    {
        if ($this->doUpFile!='') {
            $_ext = $this->getExt();
            return $this->doUpFile.".".$_ext;
        }else {
            return false;
        }
    }

    //得到上传后的文件全路径

    public function getFilePatch()
    {
        if ($this->doUpFile!='') {
            $_ext = $this->getExt();
            return $this->directory.$this->doUpFile.".".$_ext;
        }else {
            return false;
        }
    }

    //得到缩略图文件全路径
    
    public function getThumb()
    {
        if ($this->sm_File!='') {
            return $this->sm_File;
        }else {
            return false;
        }
    }

    //得到上传文件的路径

    public function getDirectory()
    {
        if ($this->directory!='') {
            return $this->directory;
        }else {
            return false;
        }
    }

}



