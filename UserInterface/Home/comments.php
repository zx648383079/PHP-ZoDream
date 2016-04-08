<ul class="ms-List">
    <?php foreach ($this->get('data', array()) as $item) {?>
        <li class="ms-ListItem ms-ListItem--image">
            <div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
            <span class="ms-ListItem-primaryText">
                <?php echo $item['user'];?>
            </span>
            <span class="ms-ListItem-tertiaryText"><?php echo $item['content'];?></span>
            <span class="ms-ListItem-metaText"><?php echo $item['create_at'];?></span>
            <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
            <div class="ms-ListItem-actions">
                <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
                <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
                <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
                <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
            </div>
        </li>
    <?php }?>
</ul>
<?php if ($this->get('more') === true) {?>
<button class="ms-button--command">加载更多</button>
<?php }?>