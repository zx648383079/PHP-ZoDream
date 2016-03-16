<?php 
$this->extend(array(
		'layout' => array(
				'head'
		)
));
?>
<div>
<a href="<?php $this->url('messages');?>">返回</a>
</div>
<div>
<form action="<?php $this->url();?>" method="post">
<div><lable>Name:</lable>:<input type="text" name="name" value="<?php $this->ech('data.name', '');?>"  required></div><div><lable>Email:</lable>:<input type="text" name="email" value="<?php $this->ech('data.email', '');?>"  required></div><div><lable>Title:</lable>:<input type="text" name="title" value="<?php $this->ech('data.title', '');?>" ></div><div><lable>Content:</lable>:<textarea name="content"  required><?php $this->ech('data.content', '');?></textarea></div><div><lable>Ip:</lable>:<input type="text" name="ip" value="<?php $this->ech('data.ip', '');?>"  required></div><div><lable>Readed:</lable>:<input type="text" name="readed" value="<?php $this->ech('data.readed', '0');?>"  required></div><div><lable>Cdate:</lable>:<input type="text" name="cdate" value="<?php $this->ech('data.cdate', '');?>"  required></div>
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