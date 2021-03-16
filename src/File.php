<?php
namespace MrZhangs;

use MrZhangs\exception\UploadException;

/**
 * 文件操作
 * User: mrzhangs
 * Date: 2021/3/15
 * Time: 13:47
 */
class File
{
    protected $object = null;

    protected $FileObj = [
        'oss' => \MrZhangs\lib\Oss::class,
        'local' => \MrZhangs\lib\Local::class
    ];

    public function __construct($objname, $config = null)
    {
        $this->object = $this->FileObj[$objname]::getInstance($config);
    }

    /**
     * 上传文件
     *
     * @param $file 文件源
     * @param null $filename 文件名称
     * @return array
     */
    public function upload($file, $filename = null)
    {
        try{
            /*判断是否上传正确*/
            if($file['error'] > 0)
            {
                return back(1,'上传文件错误');
            }

            /*获取文件后缀*/
            $ext = $this->object->getExt($file['name']);

            /*判断文件格式是否合法*/
            $this->object->checkMime($ext);

            /*判断文件大小是否合法*/
            $this->object->checkSize($file["size"]);

            if(empty($filename))
            {
                $filename = $this->object->createName($ext);
            }

            $filename = iconv("UTF-8", "GB2312", $filename);

            return $this->object->upload($file['tmp_name'], $filename);
        } catch(UploadException $e) {
            return back(1,$e->getMessage());
        }
    }

    /**
     * 下载文件
     *
     * @param $path 文件路径
     * @param null $filename 文件名称
     * @return array
     */
    public function download($path, $filename = null)
    {
        try{
            /*获取文件后缀*/
            $ext = $this->object->getExt($path);

            if(empty($filename))
            {
                $filename = $this->object->createName($ext);
            }

            $filename = iconv("UTF-8", "GB2312", $filename);

            return $this->object->download($path, $filename);
        } catch(UploadException $e) {
            return back(1,$e->getMessage());
        }
    }

    public function test()
    {
        return "安装正确";
    }
}