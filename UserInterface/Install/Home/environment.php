<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
$this->extend('layouts/header');
?>

<div class="main">
<table class="ms-Table">
    <thead>
        <tr>
            <th>参数</th>
            <th>值</th>
            <th>建议</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>服务器域名</td>
            <td><?=$name?></td>
            <td></td>
        </tr>
        <tr>
            <td>服务器操作系统</td>
            <td><?=$os?></td>
            <td></td>
        </tr>
        <tr>
            <td>服务器解译引擎</td>
            <td><?=$server?></td>
            <td></td>
        </tr>
        <tr>
            <td>PHP版本</td>
            <td><?=$phpversion?></td>
            <td></td>
        </tr>
        <tr>
            <td>系统安装目录</td>
            <td><?=$appdir?></td>
            <td></td>
        </tr>
        <tr>
            <td>allow_url_fopen</td>
            <td><?=$allowUrlFopen != false ? '√' : '×';?></td>
            <td>采集、远程资料本地化等功能必须开启</td>
        </tr>
        <tr>
            <td>safe_mode</td>
            <td><?=$safeMode != false ? '√' : '×';?></td>
            <td>安全模式下将无法正常运行</td>
        </tr>
        <tr>
            <td>GD 支持</td>
            <td><?=$gd?></td>
            <td>图片相关的大多数功能必须</td>
        </tr>
        <tr>
            <td>MySQL 支持</td>
            <td><?=$mysql != false ? '√' : '×';?></td>
            <td>使用mysql连接数据库时必须</td>
        </tr>
        <tr>
            <td>MySQLi 支持</td>
            <td><?=$mysqli != false ? '√' : '×';?></td>
            <td>使用mysqli连接数据库时必须</td>
        </tr>
        <tr>
            <td>PDO 支持</td>
            <td><?=$pdo != false ? '√' : '×';?></td>
            <td>使用PDO连接数据库时必须(默认|推荐)</td>
        </tr>
        <tr>
            <td>Session文件夹</td>
            <td><?=$temp != false ? '√' : '×';?></td>
            <td>必须 路径：<?php echo APP_DIR ?>/temp</td>
        </tr>
        <tr>
            <td>错误日志文件夹</td>
            <td><?=$log != false ? '√' : '×';?></td>
            <td>必须 路径：<?=APP_DIR ?>/log</td>
        </tr>
    </tbody>
</table>

<a class="btn btn-primary" href="<?=Url::to(['database']);?>">下一步</a>
</div>

<?php $this->extend('layouts/footer');?>