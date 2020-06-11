<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
/** @var $this View */
?>

<?php foreach($data as $store => $args):?>
   <h3><?=$args['_label']?></h3>
   <?php foreach($args as $key => $item):?>
      <?php if(strpos($key, '_label') === false):?>
            <?=Theme::text(sprintf('option[%s][%s]', $store, $key), $item,
                isset($args[$key.'_label']) ? $args[$key.'_label'] : Str::studly($key))?>
      <?php endif;?>
   <?php endforeach;?>
<?php endforeach;?>