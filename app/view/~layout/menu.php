<?php 

use App\App;

?>

<div class="ms-Grid">
	<div class="ms-Grid-row">
            <div class="ms-Grid-col ms-u-sm0 ms-u-md2">
                  <ul class="ms-ContextualMenu zx-open">
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="<?php App::url('?c=admin');?>">消息</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">动态</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li class="ms-ContextualMenu-item">
                        <a class="ms-ContextualMenu-link ms-ContextualMenu-link--hasMenu" href="#">微信管理</a>
                        <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
                        <ul class="ms-ContextualMenu">
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="<?php App::url('?c=admin&v=wechat');?>">消息</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">事件</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">菜单</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">用户</a></li>
                        </ul>
                        </li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Move</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li class="ms-ContextualMenu-item">
                        <a class="ms-ContextualMenu-link ms-ContextualMenu-link--hasMenu" href="#">数据库管理</a>
                        <i class="ms-ContextualMenu-subMenuIcon ms-Icon ms-Icon--chevronRight"></i>
                        <ul class="ms-ContextualMenu">
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Flag</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">Important</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link is-selected" href="#">Label</a></li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="<?php App::url('?c=admin&v=mysql');?>">高级</a></li>
                        </ul>
                        </li>
                        <li class="ms-ContextualMenu-item"><a class="ms-ContextualMenu-link" href="#">账号管理</a></li>
                        <li class="ms-ContextualMenu-item ms-ContextualMenu-item--divider"></li>
                        <li class="ms-ContextualMenu-item "><a class="ms-ContextualMenu-link is-disabled" href="#">关于</a></li>
                  </ul>
             </div>