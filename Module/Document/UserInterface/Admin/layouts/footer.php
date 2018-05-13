<?php
/** @var $this \Zodream\Template\View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@prism.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@doc.min.js');
?>
    </div>
</div>
<?=$this->footer()?>
</body>
</html>
