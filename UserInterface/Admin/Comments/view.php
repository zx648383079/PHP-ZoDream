<?php 
$this->extend(array(
		'layout' => array(
				'head'
		)
));
?>
<div>
<a href="<?php $this->url('comments');?>">返回</a>
<a href="<?php $this->url('comments/edit/'.$value['id']);?>">edit</a>
<a href="<?php $this->url('comments/delete/'.$value['id']);?>">delete</a>
</div>
<div>
<div><lable>Id</lable>:<?php echo $data['id'];?></div><div><lable>Content</lable>:<?php echo $data['content'];?></div><div><lable>User_id</lable>:<?php echo $data['user_id'];?></div><div><lable>Blog_id</lable>:<?php echo $data['blog_id'];?></div><div><lable>Parent_id</lable>:<?php echo $data['parent_id'];?></div><div><lable>Cdate</lable>:<?php echo $data['cdate'];?></div>
</div>


<?php 
$this->extend(array(
		'layout' => array(
				'foot'
		)
));
?>