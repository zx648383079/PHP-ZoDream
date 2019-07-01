<?php
namespace Module\Note\Domain\Weights;

use Module\Note\Domain\Model\NoteModel;
use Module\Template\Domain\Weights\Node;

class NotePanel extends Node {

    public function render($type = null) {
        $data = NoteModel::getNew($this->attr('limit'));
        return implode('', array_map(function (NoteModel $item) {
            return sprintf('<div class="note-item"><p>%s</p><div class="item-time">%s</div></div>',
                $item->html, $item->date);
        }, $data));
    }
}