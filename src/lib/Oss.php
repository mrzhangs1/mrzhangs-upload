<?php
namespace MrZhangs\lib;

use OSS\OssClient;
use OSS\Core\OssException;
use MrZhangs\exception\UploadException;

/**
 * aliyun 文件对象
 * User: mrzhangs
 * Date: 2021/3/12
 * Time: 14:58
 */
class Oss extends FileInterface
{

    /*oss 对象*/
    private static $ossInstance;

    /**
     * 文件上传
     *
     * @param $tmpname 文件源
     * @param null $filename 文件名称
     * @return mixed
     */
    public function upload($tmpname, $filename)
    {
        /**判断参数不能为空**/
        $this->IsfieldConfig($this->config);

        /**获取oss对象**/
        $Object = $this->OssObject();
        try{
            $filepath = $this->config['OSSTYPES'] . '/' . date('Ymd') . '/' . $filename;
            $Object->putObject($this->config['OSSNAME'], $filepath, file_get_contents($tmpname));
            return back(0,'操作成功！',$this->config['ENDPOINT'] . '/' . $filepath);
        } catch(OssException $e) {
            return back(1,$e->getMessage());
        }
    }

    /**
     * 文件下载
     *
     * @param $path 文件路径
     * @param null $filename 文件名称
     * @return mixed
     */
    public function download($path, $filename)
    {
        /**$path就是文件的全路径 注意是全路径 get_headers函数会打印出oss文件的详细信息**/
        $info = get_headers($path, true);

        $size = $info['Content-Length'];
        header("Content-type:application/octet-stream");
        header("Content-Disposition:attachment;filename = " . $filename);
        header("Accept-ranges:bytes");
        header("Accept-length:" . $size);
        readfile($path);
        exit;
    }

    /**
     * Oss单利
     * @return OssClient
     */
    protected function OssObject()
    {
        if (!self::$ossInstance instanceof self)
        {
            self::$ossInstance = new OssClient($this->config['ACCESSKEYID'], $this->config['ACCESSKEYSECRET'], $this->config['ENDPOINT'], true);
        }
        return self::$ossInstance;
    }

    /**
     * 判断参数不能为空
     * @param $config
     */
    protected function IsfieldConfig(&$config)
    {
        $field = ['ACCESSKEYID'=>'秘钥ID','ACCESSKEYSECRET'=>'秘钥','ENDPOINT'=>'访问路径','OSSNAME'=>'空间名称','OSSTYPES'=>'项目名称'];
        foreach($field as $key => $value)
        {
            if(empty($config[$key]))
            {
                throw new UploadException($value . '参数为空！');
            }
        }
    }
}