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
						<h1 class="Header-title">注册</h1>
					</div>
				</div>
			</div>
		
			<div class="Content">
				<div class="u-contentCenter">
		
				<form class="Form">
		
				<div class="ms-TextField is-required">
					<label class="ms-Label">账号</label>
					<input class="ms-TextField-field" type="text">
				</div>
		
				<div class="ms-TextField is-required">
					<label class="ms-Label">密码</label>
					<input class="ms-TextField-field" type="password">
				</div>
		
				<div class="ms-TextField is-required">
					<label class="ms-Label">确认密码</label>
					<input class="ms-TextField-field" type="password">
				</div>
				
				<div class="SubmitButton">
					<button class="ms-Button ms-Button--primary"><span class="ms-Button-label">注册</span></button>
					<a href="<?php Main::url('?c=auth'); ?>" class="ms-font-l ms-Link">登录</a>
				</div>
				</form>
			</div>
			</div>
		</main>
	</div>
  </div>
</div>
  
	
	
<?php Main::extend('~layout.foot');?>