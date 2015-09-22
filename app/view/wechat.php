<?php 
use App\App;	

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
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>OpenId</th>
        <th>Name</th>
        <th>Update</th>
        <th>Create</th>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      </tr>
      <tr>
      <td>2</td>
      <td>2</td>
      <td>2</td>
      <td>2</td>
      <td>2</td>
      </tr>
      <tr>
      <td>3</td>
      <td>3</td>
      <td>3</td>
      <td>3</td>
      <td>3</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3">上一页 下一页</th>
      </tr>
    </tfoot>
  </table>
</div>

</div>
	
<?php App::extend('~layout.foot');?>