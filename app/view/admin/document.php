<?php 

App::$data['menu'] = 2;
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
  <div class="treebox">
    <div class="tool">
      <a class="create" href="javascript:;">新增</a>
      <a class="create" href="javascript:;">编辑</a>
      <a href="javascript:;">删除</a>
      <a href="javascript:;">上移</a>
      <a href="javascript:;">下移</a>
    </div>
    <ul>
      <li>a1</li>
      <li>a2</li>
      <li>a3
        <span class="more">+</span>
        <ul>
          <li>b1</li>
          <li>b2</li>
          <li>b3
            <span class="more">+</span>
            <ul>
              <li>c1</li>
              <li>c2</li>
              <li>c3</li>
              <li>c4</li>
            </ul>
          </li>
          <li>b4</li>
        </ul>
      </li>
      <li>a4</li>
    </ul>
  </div>
  <?php
    $data = App::ret('data');
    if(!empty($data)) 
    {
      foreach ($data as $value) {
        echo $value['page'];
      }
    }
  ?>
</div>
</div>
	
<div class="shade"></div>
<div id="create" class="window">
	<div class="head">新增纪录</div>
	<div class="body">
		<form>
			<input type="hidden" name="id" value="0">
			标题：<input type="text" name="title" required><br>
			内容：<textarea name="content" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="add">新增</button>
		<button class="close">关闭</button>
	</div>
</div>
	
<?php App::extend('~layout.foot');?>