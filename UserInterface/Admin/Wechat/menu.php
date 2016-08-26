<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/head');
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">自定义菜单管理</h3>
          </div>
          <div class="panel-body">
              <ul>
                  <?php foreach ($data as $item):?>
                      <li>
                         <span><?=$item[0]['name']?></span>
                          <ul>
                              <?php foreach ($item[1] as $value):?>
                              <li><?=$value['name']?></li>  
                              <?php endforeach;?>
                          </ul>
                      </li>
                  <?php endforeach;?>
              </ul>
          </div>
    </div>
    
</div>

<?=$this->extend('layout/foot')?>