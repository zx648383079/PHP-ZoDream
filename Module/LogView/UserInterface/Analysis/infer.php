<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Bootstrap\TableWidget;
/** @var $this View */
$this->title = 'ZoDream Log Viewer';
?>

        <div class="tab-box">
            <div class="tab-header">
                <div class="tab-item active">
                    可疑IP
                </div>
                <div class="tab-item">
                    可疑路径
                </div>
            </div>
            <div class="tab-body">
                <div class="tab-item active">
                    <table class="table table-hover tag-table">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>所在地</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ip_list as $ip):?>
                            <tr>
                                <td>
                                    <span data-url="<?=$this->url('./log/tag', ['name' => 'c_ip'])?>"><?=$ip[0]?></span>
                                    <i class="fa fa-tag"></i>
                                </td>
                                <td>
                                    <?=$ip[1]?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-item">
                    <table class="table table-hover tag-table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="80%">路径</th>
                                <th width="20%">文件名</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($path_list as $path):?>
                            <tr>
                                <td>
                                    <span data-url="<?=$this->url('./log/tag', ['name' => 'cs_uri_stem'])?>"><?=$path[0]?></span>
                                    <i class="fa fa-tag"></i>
                                </td>
                                <td>
                                    <?=$path[1]?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  

