<?php 
use App\Main;	

Main::extend('~layout.head');
?>
<div class="ms-Grid">
  <div class="ms-Grid-row">
    <div class="ms-Grid-col ms-u-sm12 ms-u-mdPush2 ms-u-md8 ms-u-lgPush3 ms-u-lg6">
		<main class="Container">
			<div class="Header ms-bgColor-neutralPrimary ms-borderColor-greenLight">
				<div class="Header-text ms-fontColor-white">
					<div class="u-contentCenter">
						<h1 class="Header-title">登录</h1>
						<div class="error"><?php Main::ech('error');?></div>
					</div>
				</div>
			</div>
		
			<div class="Content">
				<div class="u-contentCenter">
		
				<form class="Form" action="<?php Main::url('?c=auth'); ?>" method="POST">
		
				<div class="ms-TextField is-required">
					<label class="ms-Label">邮箱</label>
					<input class="ms-TextField-field" type="email" name="email" placeholder="邮箱" value="<?php Main::ech('email'); ?>" required>
				</div>
		
				<div class="ms-TextField is-required">
					<label class="ms-Label">密码</label>
					<input class="ms-TextField-field" type="password" name="pwd" placeholder="密码" required>
				</div>
				
				<div class="ms-ChoiceField">
					<input id="demo-checkbox-selected" class="ms-ChoiceField-input" type="checkbox" name="remember" value="1">
					<label for="demo-checkbox-selected" class="ms-ChoiceField-field"><span class="ms-Label">保持登录</span></label>
				</div>
				
				<div class="SubmitButton">
					<button class="ms-Button ms-Button--primary"><span class="ms-Button-label">登录</span></button>
					<a href="<?php Main::url('?c=auth&v=register'); ?>" class="ms-font-l ms-Link">注册</a>
				</div>
				</form>
			</div>
			</div>
		</main>
	</div>
  </div>
</div>
  
	
	
<?php Main::extend('~layout.foot');?>