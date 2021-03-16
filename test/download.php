<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * 测试文件
 * User: mrzahngs
 * Date: 2021/3/16
 * Time: 11:47
 */

use \MrZhangs\File;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $type = $_POST['type'];
    $url = $_POST['url'];

    $file = new File($type);
    $data = $file->download($url);
    print_r($data);
    exit;
}

?>

<html>
<body>

<form action="" method="post">
    <table>
        <tr>
            <td>文件路径</td>
            <td><input type="text" name="url" /></td>
        </tr>
        <tr>
            <td>上传类型</td>
            <td>
                <select name="type">
                    <option value="oss">OSS对象储存</option>
                    <option value="local">本地文件操作</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="提 交" /></td>
        </tr>
    </table>
</form>

</body>
</html>