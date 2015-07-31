<?php extand("head"); ?>
	
	<div class="form" ng-app="formApp" ng-controller="formController">
		<form name="myForm" ng-submit="sendForm()">
			<div class="row">
				<input type="email" name="email" placeholder="账号" required ng-model="formData.email"/>
				<span ng-show="myForm.email.$error.required">*这是必须的</span>
				<span ng-show="myForm.email.$error.email">*请输入正确的邮箱</span>
				<span class="help-block" ng-show="errorEmail">{{ errorEmail }}</span>
			</div>
			<div class="row">
				<input type="password" name="pwd" placeholder="密码" required ng-minlength="6" ng-model="formData.pwd"/>
				<span ng-show="myForm.pwd.$error.required">*这是必须的</span>
				<span class="error" ng-show="myForm.pwd.$error.minlength">*请输入6位以上的密码</span>
				<span class="help-block" ng-show="errorPwd">{{ errorPwd }}</span>
			</div>
			<div class="row">
				<input type="text" name="code" placeholder="验证码" required ng-model="formData.code"/>
				<img id="code" src="/verify-index" alt="验证码"/>
				<span ng-show="myForm.code.$error.required">*这是必须的</span>
				<span class="help-block" ng-show="errorCode">{{ errorCode }}</span>
			</div>
			<div class="row" ng-show="message">{{ message }}</div>
			<div class="row">
				<button type="submit">提交</button>
				<a href="/auth-qrcode">二维码</a>
			</div>
		</form>
	
	</div>
	
<?php extand("foot"); ?>