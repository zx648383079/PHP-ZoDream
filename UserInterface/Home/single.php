<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
$post = $this->get('post');
$comments = $this->get('comments');
?>
<div class="single-page-artical">
								<div class="artical-content">
									<h3><?php echo $post['title'];?></h3>
									<?php echo $post['content'];?>
								    </div>
								    <div class="artical-links">
		  						 	<ul>
		  						 		<li><small> </small><span><?php echo TimeExpand::format($post['cdate']);?></span></li>
		  						 		<li><a href="#"><small class="admin"> </small><span>admin</span></a></li>
		  						 		<li><a href="#"><small class="no"> </small><span><?php echo $post['comment_count'];?>条评论</span></a></li>
		  						 		<li><a href="#"><small class="posts"> </small><span>查看提交</span></a></li>
		  						 		<li><a href="#"><small class="link"> </small><span>永久链接</span></a></li>
		  						 	</ul>
		  						 </div>
								 <div class="comment-grid-top">
			<h3><?php echo $post['comment_count'];?>条评论</h3>
			<?php foreach ($comments as $key => $value) {?>
			<div id="comment<?php echo $value['id'];?>" class="comments-top-top <?php echo $key == 0 ? : 'top-grid-comment'; ?>">
				<div class="top-comment-left">
					<a href="#"><img class="img-responsive" src="<?php $this->asset('company/images/co.png');?>" alt=""></a>
				</div>
				<div class="top-comment-right">
					<ul>
						<li><span class="left-at"><a href="#"><?php echo $value['name'];?></a></li>
						<li><span class="right-at"><?php echo TimeExpand::format($value['cdate']);?></span></li>
						<li><a class="reply" href="#">回复</a></li>
					</ul>
				<p><?php echo $value['content'];?></p>
				</div>
				<div class="clearfix"> </div>
			</div>
				
			<?php }?>
			
		</div>
		  						
		  						 <div class="artical-commentbox">
		  						 	<h3>留下足迹</h3>
		  						 	<div class="table-form">
									<form action="<?php $this->url();?>" method="post">
										<input type="hidden" name="posts_id" value="<?php echo $post['id'];?>">
										<input type="hidden" name="parent_id" value="0">
										<input type="text" name="name" class="textbox" placeholder="昵称" value="游客">
										<input type="email" name="email" class="textbox" placeholder="邮箱">
										<textarea name="content" placeholder="评论"></textarea>	
									<input type="submit" value="评论">
									</form>
									
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