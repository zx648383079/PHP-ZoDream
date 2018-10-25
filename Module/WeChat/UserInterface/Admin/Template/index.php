<?php foreach($model_list as $item):?>
<li data-id="<?=$item->id?>" data-title="<?=$item->name?>" class="rich_media_content">
<?=$item->content?>
</li>
<?php endforeach;?>