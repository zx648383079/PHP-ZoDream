<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@jquery.upload.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@auth.min.js');
?>
    </div>
</div>
<?=$this->footer()?>
</body>
</html>