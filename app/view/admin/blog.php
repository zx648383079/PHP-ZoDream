<?php 

App::extend(array('~layout' => array('head','nav')));
?>

<div class="container">
	<ul class="listbox">
		<?php
		$data = App::ret('data');
		if(empty($data))
		{
			echo "暂无数据！";
		}else{
			foreach ($data as $value) {
				echo "<li>{$value['title']}</li>";
			}
		}
		?>
	</ul>
	 <div class="pager">
		<?php
			$page = App::ret('page');
			$total = App::ret('total');
			if( $page > 1)
			{
				echo '<a href="',App::url('?v=blog&page='.($page-1)),'">上一页</a>';
			}
			
			for ($i = 1 ; $i < $total; $i++) 
			{ 
				if( $i == $page )
				{
					echo "<span>$i</span>";
				}else{
					echo '<a href="',App::url('?v=blog&page='.$i),"\">$i</a>";
				}
				
			}
			
			if( $page < $total)
			{
				echo '<a href="',App::url('?v=blog&page='.($page+1)),'">下一页</a>';
			}
		?>
	</div>
</div>

<?php App::extend('~layout.foot');?>