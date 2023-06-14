<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '环境检测';
?>
<div class="page-header">
    环境检测
</div>
<div class="page-body">
    <table class="table table-hover">
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
                <td><?=$allowUrlFopen ? '√' : '×';?></td>
                <td>采集、远程资料本地化等功能必须开启</td>
            </tr>
            <tr>
                <td>safe_mode</td>
                <td><?= $safeMode ? '√' : '×';?></td>
                <td>安全模式下将无法正常运行</td>
            </tr>
            <tr>
                <td>GD 支持</td>
                <td><?=$gd?></td>
                <td>图片相关的大多数功能必须</td>
            </tr>
            <tr>
                <td>MySQL 支持</td>
                <td><?= $mysql ? '√' : '×';?></td>
                <td>使用mysql连接数据库时必须</td>
            </tr>
            <tr>
                <td>MySQLi 支持</td>
                <td><?= $mysqli ? '√' : '×';?></td>
                <td>使用mysqli连接数据库时必须</td>
            </tr>
            <tr>
                <td>PDO 支持</td>
                <td><?= $pdo ? '√' : '×';?></td>
                <td>使用PDO连接数据库时必须(默认|推荐)</td>
            </tr>
            <?php foreach($folders as $item):?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['writeable'] ? '√' : '×';?></td>
                <td><?= $item['required'] ? '必须' : '';?> 路径：
                    <code><?= $item['path'] ?></code>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
<div class="page-footer">
    <a class="btn btn-primary" href="<?=$this->url('./database');?>">下一步</a>
</div>