<?php extand("head"); ?>
	
	<div class="form" ng-app="formApp" ng-controller="formController">
		<form ng-submit="sendForm()">
			<input type="email" name="email" placeholder="账号" required ng-model="formData.email"/>
			<span class="help-block" ng-show="errorEmail">{{ errorEmail }}</span>
			<input type="password" name="pwd" placeholder="密码" required ng-model="formData.pwd"/>
			<span class="help-block" ng-show="errorPwd">{{ errorPwd }}</span>
			<input type="text" name="code" placeholder="验证码" required ng-model="formData.code"/>
			<img id="code" src="/verify-index" alt="验证码"/>
			<span class="help-block" ng-show="errorCode">{{ errorCode }}</span>
			<button type="submit">提交</button>
		</form>
		<div ng-show="message">{{ message }}</div>
		
		{{ formData }}
		
	</div>
	
<?php extand("foot"); ?>