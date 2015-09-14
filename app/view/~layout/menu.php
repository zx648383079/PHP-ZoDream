<?php 

use App\App;

?>

<div class="ms-Grid">
	<div class="ms-Grid-row">
            <div class="ms-Grid-col ms-u-sm0 ms-u-md2">
                  <ul>
                        <li><a href="<?php App::url('?c=admin');?>">消息</a></li>
                        <li><a href="#">动态</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li>
                        <a href="#">微信管理</a>
                        <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
                        <ul>
                        <li><a href="<?php App::url('?c=admin&v=wechat');?>">消息</a></li>
                        <li><a href="#">事件</a></li>
                        <li><a href="#">菜单</a></li>
                        <li><a href="#">用户</a></li>
                        </ul>
                        </li>
                        <li><a href="#">Move</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li>
                        <a href="#">数据库管理</a>
                        <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
                        <ul>
                        <li><a href="#">Flag</a></li>
                        <li><a href="#">Important</a></li>
                        <li><a href="#">Label</a></li>
                        <li><a href="<?php App::url('?c=admin&v=mysql');?>">高级</a></li>
                        </ul>
                        </li>
                        <li><a href="<?php App::url('?c=admin&v=users');?>">账号管理</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li><a href="<?php App::url('?c=admin&v=about');?>">关于</a></li>
                  </ul>
             </div>