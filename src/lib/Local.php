<?php
namespace MrZhangs\lib;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2021/3/15
 * Time: 16:01
 */
class Local extends FileInterface
{
    /**
     * 文件上传
     *
     * @param $tmpname 文件源
     * @param null $filename 文件名称
     * @return mixed
     */
    public function upload($tmpname, $filename)
    {
        $filepath = $this->config['uploadpath'] . date('Ymd') . '/';

        $this->checkPath($filepath);
        $filepath = $filepath . $filename;

        if(move_uploaded_file($tmpname, $filepath))
        {
            return back(0,'操作成功！',$filepath);
        }
        return back(1,'操作失败！');
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
        if(file_exists($path)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            ob_clean();
            flush();
            readfile($path);
            exit;
        }else{
            return back(1,"未找到当前文件");
        }
    }

}