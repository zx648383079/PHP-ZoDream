<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head',
                'navbar'
		)), array(
            '@admin/css' => array(
                'custom.css'
            )
        )
);
?>

<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
            <h3>系统参数</h3>
            <div class="tab-content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       主页
                    </div>
                    <div class="panel-body">
                        <table class="table">
                        	<thead>
                        		<tr>
                        			<th>名称</th>
                        			<th>值</th>
                        		</tr>
                        	</thead>
                        	<tbody>
                        		<tr>
                        			<td>jjj</td>
                        			<td>jj</td>
                        		</tr>
                        	</tbody>
                        </table>
                    </div>
                 </div>
        </div>
  </div>
  <div class="copy_layout">
      <p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
  </div>
  </div>
      </div>
      <!-- /#page-wrapper -->
</div>


<?php 
$this->extend(array(
		'layout' => array(
				'foot'
		)), array(
            '@admin/js' => array(
                'metisMenu.min',
                'custom'
            )
        )
);
?>