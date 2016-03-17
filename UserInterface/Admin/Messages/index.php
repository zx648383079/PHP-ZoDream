<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
$page = $this->get('page');
?>
<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
        <h3>网页消息</h3>
        <div class="row">
         	<form class="col-sm-8" action="#" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control1 input-search" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div><!-- Input Group -->
            </form>
            <div class="mailbox-content">
               <div class="mail-toolbar clearfix">
			     <div class="float-left">
			        <div class="btn btn_1 btn-default mrg5R">
			           <i class="fa fa-refresh"> </i>
			        </div>
			        <div class="dropdown">
			            <a href="#" title="" class="btn btn-default" data-toggle="dropdown" aria-expanded="false">
			                <i class="fa fa-cog icon_8"></i>
			                <i class="fa fa-chevron-down icon_8"></i>
			            <div class="ripple-wrapper"></div></a>
			            <ul class="dropdown-menu float-right">
			                <li>
			                    <a href="#" title="">
			                        <i class="fa fa-pencil-square-o icon_9"></i>
			                        Edit
			                    </a>
			                </li>
			                <li>
			                    <a href="#" title="">
			                        <i class="fa fa-calendar icon_9"></i>
			                        Schedule
			                    </a>
			                </li>
			                <li>
			                    <a href="#" title="">
			                        <i class="fa fa-download icon_9"></i>
			                        Download
			                    </a>
			                </li>
			                <li class="divider"></li>
			                <li>
			                    <a href="#" class="font-red" title="">
			                        <i class="fa fa-times" icon_9=""></i>
			                        Delete
			                    </a>
			                </li>
			            </ul>
			        </div>
			        <div class="clearfix"> </div>
			    </div>
			    <div class="float-right">
			        
			              
                            <span class="text-muted m-r-sm">Showing 20 of 346 </span>
                            <div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">
                                <div class="btn-group">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li><a href="#">Social</a></li>
                                        <li><a href="#">Forums</a></li>
                                        <li><a href="#">Updates</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Spam</a></li>
                                        <li><a href="#">Trash</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">New</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li><a href="#">Work</a></li>
                                        <li><a href="#">Family</a></li>
                                        <li><a href="#">Social</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Primary</a></li>
                                        <li><a href="#">Promotions</a></li>
                                        <li><a href="#">Forums</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-default"><i class="fa fa-angle-left"></i></a>
                                <a class="btn btn-default"><i class="fa fa-angle-right"></i></a>
                            </div>
                        
			        
			    </div>
               </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>邮箱</th>
                            <th>标题</th>
                            <th>IP</th>
                            <th>是否已阅</th>
                            <th>提交时间</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($page->getPage() as $value) {?>
                            <tr class="unread checked">
                            <td class="hidden-xs">
                                <input type="checkbox" class="checkbox">
                            </td>
                            <td>
                                <?php echo $value['id'];?>
                            </td>
                            <td>
                                <?php echo $value['name'];?>
                            </td>
                            <td>
                                <?php echo $value['email'];?>
                            </td>
                            <td>
                                <?php echo $value['title'];?>
                            </td>
                            <td>
                                <?php echo $value['ip'];?>
                            </td>
                            <td>
                                <?php echo $value['readed'] == 1 ? '已阅' : '';?>
                            </td>
                            <td>
                                <?php echo TimeExpand::format($value['cdate']);?>
                            </td>
                            <td>
                                <a href="<?php $this->url('messages/view/id/'.$value['id']);?>">查看回复</a>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php $page->pageLink();?>
               </div>
            </div>
            <div class="clearfix"> </div>
       </div>
       <div class="copy_layout">
         <p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
       </div>
   </div>
      </div>
      <!-- /#page-wrapper -->
   </div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>