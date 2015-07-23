<?php extand("head"); ?>
	
	<div class="form">
		<from action="/?c=auth&v=login" method="Post">
			<input type="email" name="email" placeholder="账号" required/>
			
			<input type="password" name="pwd" placeholder="密码" required/>
			
			<input type="text" name="code" placeholder="验证码" required/>
		
			<input type="submit" name="submit" value="提交"/>
		</from>
	</div>
	
<?php extand("foot"); ?>