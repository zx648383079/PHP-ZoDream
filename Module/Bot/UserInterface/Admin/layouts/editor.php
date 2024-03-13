<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Module\Bot\Domain\Model\EditorModel;
use Module\Bot\Domain\EditorInput;
/** @var View $this */
/** @var EditorModel $model */
$this->registerJs(sprintf('bindEditorInput(\'%s\');', $this->url('./@admin/', false)));
?>
<div class="wx-editor">
    <?= Theme::select('type', EditorInput::typeItems($model), $model->type, '响应类型') ?>

    <?= EditorInput::form($model) ?>
</div>