<?php 
$this->extend(array(
		'layout' => array(
				'head'
		)
));
?>
<div>
<a href="<?php $this->url('comments');?>">返回</a>
</div>
<div>
<form action="<?php $this->url();?>" method="post">
<div><lable>Content:</lable>:<input type="text" name="content" value="<?php $this->ech('data.content', '');?>"  required></div><div><lable>User_id:</lable>:<input type="text" name="user_id" value="<?php $this->ech('data.user_id', '');?>"  required></div><div><lable>Blog_id:</lable>:<input type="text" name="blog_id" value="<?php $this->ech('data.blog_id', '');?>"  required></div><div><lable>Parent_id:</lable>:<input type="text" name="parent_id" value="<?php $this->ech('data.parent_id', '0');?>"  required></div><div><lable>Cdate:</lable>:<input type="text" name="cdate" value="<?php $this->ech('data.cdate', '');?>"  required></div>
<button type="submit">保存</button>
</form>
</div>


<?php 
$this->extend(array(
		'layout' => array(
				'foot'
		)
));
?>