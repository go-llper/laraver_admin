<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/4/19
 * Time: 下午7:36
 */

namespace App\Logics\Oss;

require_once __DIR__.'/../../../vendor/aliyuncs/oss-sdk-php/autoload.php';

use App\Logics\Common\BaseLogic;
use League\Flysystem\Exception;
use OSS\OssClient;
use OSS\Core\OssException;
use Config;
use Log;

class OssLogic extends BaseLogic
{

    private $ossClient    = null;

    private $bucket       = null;

    CONST OSS_CACHE_KEY   = 'OSS_FILE_CRC_PRE_';

    /**
     * @param string  $oss_config
     * @throws \Exception
     * @desc  传入$bucket会修改配置,将文件存入对应的bucket中,默认bucket为'timecash-waybill'
     */
    public function __construct($oss_config = 'oss_3')
    {
        $config_key = 'oss.'.$oss_config;
        $config     = Config::get($config_key);
        if(empty($config)){
            throw new Exception('Can not get the bucket config '.$oss_config.'!');
        }
        $this->bucket    = $config['bucket'];
        $endpoint        = Config::get($config_key.'.endpoint');
        $accessKeyId     = Config::get($config_key.'.accessKeyId');
        $accessKeySecret = Config::get($config_key.'.accessKeySecret');
        try {
            $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        } catch (OssException $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @param array $file       $_FILES 表单上传的文件
     * @param string $nameSpace 默认nameSpace为uploads,如果想进行细分,请以'a/b/c'格式传入nameSpace,如:'resources/pdf'
     * @return array
     * @desc 上传文件到oss,生成oss保存路径和对应crc32路径码,写入oss_file_path表,返回crc32码
     */
    public function putFile($file, $nameSpace='uploads')
    {

        $return = self::callError('上传文件信息不正确');

        //判断文件
        if(empty($file['tmp_name'])){
            return $return;
        }

        //处理nameSpace
        $nameSpace = $this->applyPathSuffix($nameSpace);

        //文件名
        $fileName = strpos($file['name'],'.') ? md5(uniqid().rand(100,10000)).'.'.substr($file['name'],strrpos($file['name'],'.')+1) : md5(uniqid().rand(100,10000));

        //构建oss存储路径
        $object = $nameSpace.'/'.date("Ymd",time()).'/'.$fileName;

        try{

            $ossClient = $this->ossClient;

            $exist = $ossClient->doesObjectExist($this->bucket, $object);

            if($exist){
                return self::callError('文件名已存在,请重新上传');
            }

            $ossClient->uploadFile($this->bucket,$object,$file['tmp_name']);

            $fileInfo = [
                'path' => substr($object,0,strrpos($object,'/')),
                'name' => $fileName,
            ];

            return self::callSuccess($fileInfo);

        }catch (OssException $e) {

            $attributes = [
                'file'=>$file,
                'msg' => $e->getMessage(),
                'errorMsg'=> $e->getErrorMessage(),
                'details' => $e->getDetails(),
                'code' => $e->getErrorCode()
            ];

            Log::error(__METHOD__.'Error', $attributes);
            return self::callError($e->getMessage());

        }

    }

    /**
     * @param string $localDirectory 需要上传的文件目录
     * @param string $prefix         上传到oss后的object前缀,不能以'/'开头
     * @param bool   $recursive      是否递归的上传localDirectory下的子目录内容
     * @return bool
     * @desc                         上传整个目录文件
     */
    public function uploadDir($localDirectory, $prefix, $recursive=true)
    {

        $return = self::callError('缺少目录或路径前缀');

        if(!$localDirectory || !$prefix){
            return $return;
        }

        try{

            $ossClient = $this->ossClient;

            $ossClient->uploadDir($this->bucket, $prefix, $localDirectory, $exclude = '.|..|.svn|.git', $recursive);

            return self::callSuccess();

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }
    }

    /**
     * @param string $contents    需要上传的内存中的内容,如果是需要追加内容的文件,请使用appendFile方法
     * @param string $object      存储路径和文件名 如:'uploads/qrcode/xxx.png'
     * @return bool
     * @desc 保存内存中的内容(二进制流)如二维码,txt文件
     */
    public function writeFile($contents, $object)
    {

        $object = $this->applyPathSuffix($object);

        try{

            $ossClient = $this->ossClient;

            $ossClient->putObject($this->bucket,$object,$contents);

            return true;

        }catch(\Exception $e){

            return false;
        }

    }

    /**
     * @param string $path
     * @return bool|string
     * @desc    获取文件内容并保存在内存中
     */
    public function getObject($path)
    {
        $path = $this->applyPathSuffix($path);

        $ossClient = $this->ossClient;

        try{
            $content = $ossClient->getObject($this->bucket, $path);
        } catch(OssException $e) {
            return false;
        }

        return $content;
    }

    /**
     * @param string $path
     * @return bool|string
     * @desc 获取文件meta信息
     */
    public function getObjectMeta($path)
    {
        $ossClient = $this->ossClient;

        try {
            $objectMeta = $ossClient->getObjectMeta($this->bucket, $path);
        } catch (OssException $e) {
            return false;
        }
        return $objectMeta;
    }

    /**
     * @param string $path
     * @return bool|string
     * @desc 生成GetObject的签名url,主要用于私有权限下的读访问控制
     */
    public function getSignUrl($path)
    {
        $timeout = 300;
        $object = $this->applyPathSuffix($path);
        $oss = $this->ossClient;

        try{
            $exit = $this->checkPathExit($object);
            if($exit){
                return $oss->signUrl($this->bucket, $object, $timeout);
            }
        } catch(OssException $e) {

            return false;

        }
    }

    /**
     * @param string $object
     * @return bool
     * @desc 删除对象(未用)
     */
    public function delete($object)
    {
        $object = $this->applyPathSuffix($object);

        try{

            $oss = $this->ossClient;

            $oss->deleteObject($this->bucket, $object);

        }catch (OssException $e) {

            return false;

        }

        return true;

    }

    /**
     * @param $crc
     * @param array $data
     * @return bool
     * @desc 设置缓存
     */
    private static function setOssCacheByCrc($crc, $data=[]){

        $cacheKey = self::OSS_CACHE_KEY.$crc;

        if( !empty($data) && is_array($data) ){

            \Cache::put($cacheKey, json_encode($data), 30);

        }

        return true;

    }

    /**
     * @param $crc
     * @return bool|mixed
     * @desc 获取缓存
     */
    private static function getOssCacheByCrc($crc){

        $cacheKey = self::OSS_CACHE_KEY.$crc;

        $jsonData = \Cache::get($cacheKey);

        if( !empty($jsonData) && json_decode($jsonData, true) ){

            return json_decode($jsonData, true);

        }

        return false;

    }

    /**
     * @param string $path
     * @return bool  存在返回true,不存在返回false
     * @throws \Exception
     * @desc         判断oss中是否存在此object
     */
    public function checkPathExit($path)
    {
        $object = $this->applyPathSuffix($path);

        try {

            $ossClient = $this->ossClient;

            return $ossClient->doesObjectExist($this->bucket, $object);

        } catch (OssException $e) {

            throw new \Exception($e->getMessage());

        }

    }

    /**
     * @param string $nameSpace
     * @return string
     * @desc 处理命名空间,去除$nameSpace前后的'/'
     */
    private function applyPathSuffix($nameSpace)
    {

        return trim($nameSpace, '\\/');

    }

}