<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class FileNode extends Node {

    public function render($type = null) {
        $content = $this->attr('content');
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> <a href="">{$content}</a></p>
    <p>156 Bytes, 下载次数: 83</p>
</div>
HTML;

    }
}