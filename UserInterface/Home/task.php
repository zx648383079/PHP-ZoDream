<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
$page = $this->get('page');
?>
<div class="content">
		<div class="contact about-desc">
			<h3>任务列表</h3>
			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">进行中的任务</div>
				<div class="panel-body">
					<?php foreach ($page->getPage() as $value) { ?>
						<div class="row">
							<div class="col-md-2"><?php echo $value['name'];?></div>
							<div class="col-md-8">
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $value['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value['progress'];?>%">
										<?php echo $value['progress'];?>%
									</div>
								</div>
							</div>
							<div class="col-md-2"><?php echo $value['user'];?></div>
						</div>
					<?php } ?>
				</div>
				<div class="panel-footer">
					<?php $page->PageLink();?>
				</div>
			</div>
   			<hr>
   			<div class="row">
   				<div class="contact_right">
   				<div class="contact-form_grid">
				   <form method="post" action="<?php $this->url();?>">
					 <input type="text" name="name" class="textbox" placeholder="名称" value="<?php $this->ech('name');?>">
					   <textarea name="content" placeholder="详情"></textarea>
					   <p class="text-danger"><?php $this->ech('status'); ?></p>
					   <input type="submit" value="提交">
				   </form>
			      </div>
   			     </div>
   			</div>
   	</div>
   
   </div>	
   
</div>	
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>