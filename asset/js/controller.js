/// <reference path="../../typings/angularjs/angular.d.ts"/>
function phonelist($scope)
{
	$scope.phones=[
		{"name":"lumia",
			"text":"windowphone",
			"age":0},
			{"name":"apple",
				"text":"ios",
				"age":3},
				{"name":"zte",
					"text":"androd",
					"age":2}];
	$scope.hello = "Hello, World!";
	$scope.orderProp = 'age';
}

var formApp=angular.module("formApp",[]);

formApp.controller('formController',['$scope','$http','$window','$interval',function($scope,$http,$window,$interval)
{
	$scope.formData={};
	
	/*
	$http.post('process.php', $scope.formData)
        .success(function(data) {
	*/
	
	$scope.sendForm=function(){
		
		$http({
			method:"POST",
			url:"/auth-login",
			data:topost($scope.formData),
			headers:{"Content-Type":"application/x-www-form-urlencoded"}
		}).success(function(data){
			//console.log(data);
			if(!data.success)
			{
				$scope.errorEmail = data.errors.email;
				$scope.errorPwd = data.errors.pwd;
				$scope.errorCode = data.errors.code;
			}else{
				var i=5;
				var a=$interval(function()
					{
						if(i==0)
						{
							$window.location.href="/";
							$interval.cancel(a);
						}else{
							$scope.message="登录成功！将在"+i+"秒后进入……";
						}
						i--;
					},1000);
			}
		});
	};
}]);