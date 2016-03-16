<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
?>
<body>
<div id="wrapper">
     <!-- Navigation -->
        <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php $this->url('/');?>">ZoDream</a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
	        		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="badge"><?php $this->ech('noread');?></span></a>
	        		<ul class="dropdown-menu">
						<li class="dropdown-menu-header">
							<strong>消息</strong>
							<div class="progress thin">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
							    <span class="sr-only">40% Complete (success)</span>
							  </div>
							</div>
						</li>
						<?php foreach ($this->get('usermessages', array()) as $value) {?>
                        <li class="avatar">
							<a href="<?php $this->url('messages/view/id/'.$value['id']);?>">
								<img src="<?php $this->asset('admin/images/1.png');?>" alt=""/>
								<div><?php echo $value['title'];?></div>
								<small><?php echo TimeExpand::isTimeAgo($value['cdate']);?> ago</small>
								<?php if($value['readed'] == 0) {?><span class="label label-info">新</span><?php }?>
							</a>
						</li>
                        <?php }?>
						<li class="dropdown-menu-footer text-center">
							<a href="<?php $this->url('messages');?>">查看所有消息</a>
						</li>	
	        		</ul>
	      		</li>
			    <li class="dropdown">
	        		<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img src="<?php $this->asset('admin/images/1.png');?>"><span class="badge">9</span></a>
	        		<ul class="dropdown-menu">
						<li class="dropdown-menu-header text-center">
							<strong>账户</strong>
						</li>
						<li class="m_2"><a href="<?php $this->url('updates');?>"><i class="fa fa-bell-o"></i> 更新 <span class="label label-info">42</span></a></li>
						<li class="m_2"><a href="<?php $this->url('messages');?>"><i class="fa fa-envelope-o"></i> 消息 <span class="label label-success"><?php $this->ech('noread');?></span></a></li>
						<li class="m_2"><a href="<?php $this->url('tasks');?>"><i class="fa fa-tasks"></i> 任务 <span class="label label-danger"><?php $this->ech('newtasks');?></span></a></li>
						<li><a href="<?php $this->url('comments');?>"><i class="fa fa-comments"></i> 评论 <span class="label label-warning"><?php $this->ech('newcomments');?></span></a></li>
						<li class="dropdown-menu-header text-center">
							<strong>设置</strong>
						</li>
						<li class="m_2"><a href="<?php $this->url('users/info');?>"><i class="fa fa-user"></i> 用户中心 </a></li>
						<li class="m_2"><a href="<?php $this->url('settings');?>"><i class="fa fa-wrench"></i> 设置 </a></li>
						<li class="m_2"><a href="<?php $this->url('payments');?>"><i class="fa fa-usd"></i> 支付 <span class="label label-default">42</span></a></li>
						<li class="m_2"><a href="<?php $this->url('projects');?>"><i class="fa fa-file"></i> 项目 <span class="label label-primary">42</span></a></li>
						<li class="divider"></li>
						<li class="m_2"><a href="<?php $this->url('lock');?>"><i class="fa fa-shield"></i> 安全 </a></li>
						<li class="m_2"><a href="<?php $this->url('account/logout');?>"><i class="fa fa-sign-out"></i> 登出</a></li>	
	        		</ul>
	      		</li>
			</ul>
			<form class="navbar-form navbar-right">
              <input type="text" class="form-control" name="search" value="搜索。。。" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '搜索。。。';}">
            </form>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="<?php $this->url('/');?>"><i class="fa fa-dashboard fa-fw nav_icon"></i>面板</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit nav_icon"></i>发布<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('posts');?>">所有发布</a>
                                    <a href="<?php $this->url('comments');?>">所有评论</a>
                                    <a href="<?php $this->url('posts/add');?>">新建发布</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-plug nav_icon"></i>插件<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('plugins');?>">已下载插件</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('plugins/shop');?>">插件市场</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-envelope nav_icon"></i>消息<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('messages');?>">网页消息</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('messages/chat');?>">站内消息</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-tasks nav_icon"></i>任务<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('tasks');?>">所有任务</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('tasks/add');?>">新建任务</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                         <li>
                            <a href="#"><i class="fa fa-user nav_icon"></i>用户管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('users');?>">所有用户</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('users/roles');?>">用户权限</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('users/info');?>">个人信息</a>
                                </li>
                                <li>
                                    <a href="<?php $this->url('users/reset');?>">更改密码</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="<?php $this->url('system');?>"><i class="fa fa-table nav_icon"></i>文件系统</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw nav_icon"></i>设置<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php $this->url('settings');?>">基本设置</a>
                                </li>
                                <li>
                                    <a href="#">单页更改<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
		                                <li>
		                                    <a href="<?php $this->url('singles');?>">单页列表</a>
		                                </li>
		                                <li>
		                                    <a href="<?php $this->url('singles/add');?>">新增单页</a>
		                                </li>
		                            </ul>
                                </li>
                                <li>
                                    <a href="<?php $this->url('settings/info');?>">系统参数</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>