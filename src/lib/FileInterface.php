<?php
namespace MrZhangs\lib;

use MrZhangs\exception\UploadException;

/**
 * 文件操作
 * User: mrzhangs
 * Date: 2021/3/12
 * Time: 15:16
 */
abstract class FileInterface
{

    protected $config = [

        'extension' => ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'], /**默认上传文件后缀**/

        'filesize' => 5242880, /**文件上传大小 5mb**/

        'uploadpath' => '', /**文件上传路径**/

    ];

    /**
     * 创建静态私有的变量保存该类对象
     * @var null
     */
    protected static $instance = null;

    /**
     * 构造函数
     *
     * @param $config
     */
    protected function __construct(&$config)
    {
        if($config)
        {
            foreach($config as $key => $value)
            {
                $this->config[$key] = $value;
            }
        }

    }

    /**
     * 获取当前单利对象
     *
     * @param $obj this
     * @return null
     */
    public static function getInstance($config = null)
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * 文件上传
     *
     * @param $tmpname 文件源
     * @param null $filename 文件名称
     * @return mixed
     */
    abstract public function upload($tmpname, $filename);

    /**
     * 文件下载
     *
     * @param $path 文件路径
     * @param null $filename 文件名称
     * @return mixed
     */
    abstract public function download($path, $filename);

    /**
     * 生成文件名称
     *
     * @param $ext 文件后缀
     * @return string
     */
    public function createName($ext = 'png')
    {
        return time() . rand(1000,9999) . '.' . $ext;
    }

    /**
     * 获取文件后缀
     *
     * @param $url 文件路径
     * @return mixed
     */
    public function getExt($url)
    {
        return strtolower(pathinfo($url,PATHINFO_EXTENSION));
    }

    /**
     * 获取图片真实后缀
     *
     * @param $url 文件路径
     * @return $suffix 文件后缀
     */
    public function getImgSuffix($url)
    {
        $info = getimagesize($url);
        $suffix = false;
        if($mime = $info['mime'])
        {
            $suffix = explode('/',$mime)[1];
        }

        return $suffix;
    }

    /**
     * 检查目录是否可写
     *
     * @access protected
     * @param  string $path 目录
     * @return boolean
     */
    public function checkPath($path)
    {
        if (is_dir($path) || mkdir($path, 0755, true))
        {
            return true;
        }

        return false;
    }

    /**
     * 检测上传文件类型
     *
     * @access public
     * @param  array|string $mime 允许类型
     * @return bool
     */
    public function checkMime($mime)
    {
        if(in_array($mime, $this->config['extension']))
        {
            return true;
        }

        throw new UploadException('上传文件格式不正确');
    }

    /**
     * 检测上传文件大小
     *
     * @access public
     * @param  integer $size 最大大小
     * @return bool
     */
    public function checkSize($size)
    {
        if($this->config['filesize'] >= $size)
        {
            return true;
        }

        throw new UploadException('上传文件最大不能超过' . format_bytes($this->config['filesize']));
    }

    /**
     * 获取当前文件在服务器中路径
     * @param $domain 当前域名
     * @param $url 当前文件路径
     * @return mixed
     * @throws UploadException
     */
    public function getPath($domain, $url)
    {
        if(strpos($url, 'http') !== false)
        {
            if(strpos($url, $domain) === false)
            {
                throw new UploadException("未找到当前文件");
            }

            return str_replace($domain, '', $url);
        }
        return $url;
    }
}