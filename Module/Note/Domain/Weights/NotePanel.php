<?php
namespace Module\Note\Domain\Weights;

use Module\Note\Domain\Model\NoteModel;
use Module\Note\Domain\Repositories\NoteRepository;
use Module\Template\Domain\Weights\Node;

class NotePanel extends Node {

    const KEY = 'home_note';

    public function render($type = null) {
        return $this->cache()->getOrSet(self::KEY, function () {
            $data = NoteRepository::getNewList(intval($this->attr('limit')));
            return implode('', array_map(function (NoteModel $item) {
                return sprintf('<div class="note-item"><div class="item-body">%s</div><div class="item-time">%s</div></div>',
                    $item->html, $item->date);
            }, $data));
        });
    }
}