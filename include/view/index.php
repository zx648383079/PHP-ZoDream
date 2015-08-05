<?php extand("head"); ?>
	
	<div class="container" ng-app ng-init="aa='aaa'" ng-controller="phonelist">
		<div><a href="/?c=auth">登出</a></div>
		<div class="head">
			Hello {{'World'}}!
			<br/>
			输入数据：<input type="text" ng-model="name" placeholder="World">
			<br/>
			
			{{ name || 'word' }}
			
			<p>1+2= {{ 1+2 }}</p>
			<select ng-model="orderProp">
			  <option value="name">Alphabetical</option>
			  <option value="age">Newest</option>
			</select>
			
			<ul>
				<li ng-repeat="phone in phones | filter:name | orderBy:orderProp">
					{{ phone.name }}
					<p>{{ phone.text }}</p>
				</li>
			</ul>
			
			{{ phones.length }}
			{{ hello }}
			
			<br/>
			
			<table>
				<tr><th>fdgd</th></tr>
				<tr ng-repeat="i in [0,1,2,3,4,5,6,7,8]">
					<td>{{ i+1 }}</td>
				</tr>
			</table>
	</div>
	
	<form>
			
	</form>
	
	<!--<div class="navbar">
		<div class="brand">
			<img src="/asset/img/favicon.png" alt=""/>
		</div>
		<ul class="inline">
			<li><a href="#">主页</a></li>
			<li><a href="#">消息</a>
				<ul class="up">
					<li><a href="#">普通消息</a></li>
					<li><a href="#">图文消息</a></li>
				</ul>
			</li>
			<li><a href="#">会员</a></li>
			<li><a href="#">回复</a></li>
		</ul>
	</div>-->
	
<?php extand("foot"); ?>