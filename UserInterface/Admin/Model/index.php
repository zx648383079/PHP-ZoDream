<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">所有备份文件</h3>
          </div>
          <div class="panel-body">
              
              <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>文件</th>
                          <th>更新时间</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($data as $item) :?>
                        <tr>
                            <td><a href="<?=Url::to(['download', 'file' =>$item['url']]);?>"><?=$item['url'];?></a></td>
                            <td><?=$this->ago($item['mtime']);?></td>
                        </tr>
                        <?php endforeach;?>
                  </tbody>
              </table>
          </div>
    </div>
    
</div>


<?=$this->extend('layout/footer')?>