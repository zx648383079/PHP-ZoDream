<?php 

App::$data['menu'] = 1;
App::extend(array(
	'~layout'=>array(
		'head',
		'nav',
		'menu'
		)
	)
);
?>

<div class="long">
  <button id="addPage">发表</button>
  <table id="pagelist">
	<thead>
		<tr>
			<th>ID</th>
			<th>Page</th>
			<th>Show</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
  <?php
    $data = App::ret('data');
    if(!empty($data)) 
    {
      foreach ($data as $value) {
        echo '<tr><td>',$value['id'],'</td><td>',$value['page'],
			'</td><td><input type="checkbox" ',$value['show'] == 1?'checked':'',
			'></td><td><a href="javascript:;">编辑</a> <a href="javascript:;">删除</a></td></tr>';
      }
    }
  ?>
  </tbody>
  </table>
</div>
</div>

<div id="page" class="sidebox">
  <div class="head"><button class="close">关闭</button></div>
	<div class="body">
		<form>
			<input type="hidden" name="id" value="0">
			标题：<input type="text" name="page" required><br>
			内容：<textarea name="content" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="save">保存</button>
	</div>
</div>	
	
<?php App::extend('~layout.foot');?>