<?php extand("head"); ?>
	
	<div class="container" ng-app ng-init="aa='aaa'" ng-controller="phonelist">
		<div><a href="<?php echo url("auth"); ?>">登出</a></div>
		
		<canvas id="cas" height="500" width="700">
			<p>不支持HTM5</p>
		</canvas>
		
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
	</div>
	
	
	<form>
			
	</form>
	
	
	
<?php extand("foot"); ?>