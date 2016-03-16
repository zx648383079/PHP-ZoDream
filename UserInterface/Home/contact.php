<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
<div class="content">
		<div class="contact about-desc">
   			<div class="row">
   				<?php $this->ech('data.0.value');?>
   			</div>
   			<hr>
   			<div class="row">
   				<div class="col-md-4 rst">
   				<div class="contact_right">
   				<div class="contact-form_grid">
				   <form method="post" action="<?php $this->url();?>">
					 <input type="text" name="name" class="textbox" placeholder="姓名" value="<?php $this->ech('name');?>">
					 <input type="email" name="email" class="textbox" placeholder="邮箱" value="<?php $this->ech('email');?>" required>
					 <input type="text" name="title" class="textbox" placeholder="标题">
					 <textarea name="content" placeholder="内容" required></textarea>
					 <input type="submit" value="发送">
				   </form>
			      </div>
   			     </div>
   				</div>
   				<div class="col-md-8 map">
					<iframe src="/map.html" frameborder="0" style="border:0"></iframe>
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