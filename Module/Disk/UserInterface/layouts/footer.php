<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@jquery.upload.min.js')
    ->registerJsFile('@vue.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@disk.min.js');
?>
    </div>
</div>
<?=$this->footer()?>
</body>
</html>
