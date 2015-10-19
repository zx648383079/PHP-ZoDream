<?php 
use App\Lib\Html\HTree;

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
      <a href="javascript:;">编辑</a>
      <a href="javascript:;">删除</a>
      <a href="javascript:;">上移</a>
      <a href="javascript:;">下移</a>
    </div>
    <?php HTree::make(App::ret('data')); ?>
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
      <input type="hidden" name="pid" value="0">
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