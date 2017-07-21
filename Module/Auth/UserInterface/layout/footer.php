<?php
/** @var $this \Zodream\Domain\View\View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.validate.min.js')
    ->registerJsFile('@jquery.form.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@login.min.js');
?>
<div class="footer">
    湘ICP备 00000000000
</div>
<?=$this->footer()?>
</body>
</html>
