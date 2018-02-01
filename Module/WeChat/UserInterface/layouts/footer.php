<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@wechat.min.js');
?>
    </div>
</div>
<?=$this->footer()?>
</body>
</html>
