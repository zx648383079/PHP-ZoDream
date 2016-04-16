<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">所有备份文件</h3>
          </div>
          <div class="panel-body">
              
              <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>文件</th>
                          <th>更新时间</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($this->get('data', array()) as $item) {?>
                        <tr>
                            <td><a href="<?php $this->url('download?file='.$item['url']);?>"><?php echo $item['url'];?></a></td>
                            <td><?php $this->ago($item['mtime']);?></td>
                        </tr>
                        <?php }?>
                  </tbody>
              </table>
          </div>
    </div>
    
</div>


<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>