<?php 
$this->extend(array(
		'layout' => array(
				'head'
		)
));
?>
<div>
<a href="<?php $this->url('users');?>">返回</a>
</div>
<div>
<form action="<?php $this->url();?>" method="post">
<div><lable>Username:</lable>:<input type="text" name="username" value="<?php $this->ech('data.username', '');?>"  required></div><div><lable>Email:</lable>:<input type="text" name="email" value="<?php $this->ech('data.email', '');?>"  required></div><div><lable>Password:</lable>:<input type="text" name="password" value="<?php $this->ech('data.password', '');?>"  required></div><div><lable>Cdate:</lable>:<input type="text" name="cdate" value="<?php $this->ech('data.cdate', '');?>"  required></div>
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