<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/19
 * Time: 下午7:38
 */

namespace App\Http\Controllers\Oss;

use App\Http\Controllers\Controller;
use App\Logics\Oss\OssLogic;
use Config;
use Illuminate\Http\Request;

class OssController extends Controller
{

    private $oss_object = null;


    /**
     * OssPictureController constructor.
     */
    public function __construct()
    {
        $this->oss_object = new OssLogic();
    }

    /**
     * 获取路径
     * @param string $path
     * @desc  通过uploads路径查找oss文件
     */
    public function uploadsPath(Request $request,$path=''){
        if(empty($path)) {
            $this->showErrorPicture();
        }
        $prefix = 'uploads/';
        $ver = $request->input('v');
        if(!empty($ver)){
            $origin = $prefix.$path.'?v='.$ver;
        }else{
            $origin = $prefix.$path.'?v='.date('Ymd');
        }
        $etag = md5($origin);
        if(isset($_SERVER['HTTP_IF_NONE_MATCH'])){
            $old_etag = $_SERVER['HTTP_IF_NONE_MATCH'];
            if($old_etag == $etag){
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
        }
        $newPath = $prefix.$path;
        $meta = $this->oss_object->getObjectMeta($newPath);
        header("Content-type: {$meta['content-type']}");
        header("Content-length: {$meta['content-length']}");
        header("Etag: " . $etag);
        $this->show($prefix, $path);

    }

    /**
     * 获取路径
     * @param string $path
     * @desc  通过resources路径查找oss文件
     */
    public function resourcesPath(Request $request,$path=''){

        if(empty($path)) {
            $this->showErrorPicture();
        }

        $prefix = strpos($path,'uploads/')===0 ? '' : 'resources/';
        $ver = $request->input('v');
        if(!empty($ver)){
            $origin = $prefix.$path.'?v='.$ver;
        }else{
            $origin = $prefix.$path.'?v='.date('Ymd');
        }
        $etag = md5($origin);
        if(isset($_SERVER['HTTP_IF_NONE_MATCH'])){
            $old_etag = $_SERVER['HTTP_IF_NONE_MATCH'];
            if($old_etag == $etag){
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
        }
        $newPath = $prefix.$path;
        $meta = $this->oss_object->getObjectMeta($newPath);
        header("Content-type: {$meta['content-type']}");
        header("Content-length: {$meta['content-length']}");
        header("Etag: " . $etag);
        $this->show($prefix, $path);

    }

    /**
     * 获取路径
     * @param string $prefix 前缀
     * @param string $path   路径
     * @desc  显示oss中的图片
     */
    public function show($prefix,$path){

        $path = $prefix.$path;

        //判断文件是否存在于oss
        $exit = $this->oss_object->checkPathExit($path);

        if($exit) {
            $this->exportFile($path);
        }else{
            $this->showErrorPicture();
        }

    }

    /**
     * @param $path
     *
     * 获取文件内容并输出
     */
    public function exportFile($path){
        echo $this->oss_object->getObject($path);
        exit();
    }

    /**
     * 图片不存在，显示错误图片
     */
    protected function showErrorPicture() {
        $file   = Config::get('upload.PICTURE.ERROR_PICTURE');
        if(!file_exists($file)) {
            header("Content-type: image/png");
            $im = imagecreate(100, 100);
            $background_color = imagecolorallocate($im, 255, 255, 255);
            $text_color = imagecolorallocate($im, 155, 155, 155);
            imagestring($im, 2, 0, 45,  "Image Not Exists.", $text_color);
            imagepng($im);
            imagedestroy($im);
        } else {
            $imageConfig   = Config::get('upload.PICTURE');
            $uploadDir     = $imageConfig['PICTURE_SAVE_PATH'];
            if(file_exists($file)) {
                $this->headerType($file);
                echo file_get_contents($file);
            }
            exit;
        }
    }

    //根据图片后缀，header相应Content-Type
    private function headerType($file) {
        $typeExt = array(
            'png'   => 'png',
            'jpg'   => 'jpg',
            'jpeg'  => 'jpg',
            'gif'   => 'gif',
            'bmp'   => 'bmp',
        );

        if(preg_match('#\.([^.]+)$#is', $file, $match)) {
            $type = $typeExt[$match[1]];
            if(!isset($typeExt[$match[1]])) {
                $type = 'png';
            }

            header("Content-Type: image/{$type}");
        }
    }

}
