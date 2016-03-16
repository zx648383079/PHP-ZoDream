<?php 
$this->extend(array(
		'layout' => array(
				'head'
		)
));
?>
<div>
<a href="<?php $this->url('users');?>">返回</a>
<a href="<?php $this->url('users/edit/'.$value['id']);?>">edit</a>
<a href="<?php $this->url('users/delete/'.$value['id']);?>">delete</a>
</div>
<div>
<div><lable>Id</lable>:<?php echo $data['id'];?></div><div><lable>Username</lable>:<?php echo $data['username'];?></div><div><lable>Email</lable>:<?php echo $data['email'];?></div><div><lable>Password</lable>:<?php echo $data['password'];?></div><div><lable>Cdate</lable>:<?php echo $data['cdate'];?></div>
</div>


<?php 
$this->extend(array(
		'layout' => array(
				'foot'
		)
));
?>