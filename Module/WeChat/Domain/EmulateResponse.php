<?php
namespace Module\WeChat\Domain;

use Module\WeChat\Domain\Model\MediaModel;
use Zodream\Infrastructure\Interfaces\ArrayAble;
use Zodream\ThirdParty\WeChat\MessageResponse;

class EmulateResponse extends MessageResponse implements ArrayAble {

    public function toArray() {
        if ($this->data['MsgType']['@cdata'] === 'text') {
            return [
                'type' => 'text',
                'content' => $this->data['Content']['@cdata']
            ];
        }
        if ($this->data['MsgType']['@cdata'] === 'news') {
            return [
                'type' => 'news',
                'items' => array_map(function ($item) {
                    return [
                        'title' => $item['Title']['@cdata'],
                        'thumb' => $item['PicUrl']['@cdata'],
                        'url' => url('./emulate/media', ['id' =>
                            MediaModel::where('title', $item['Title']['@cdata'])->value('id')])
                    ];
                }, $this->data['Articles']['item'])
            ];
        }
        return [];
    }
}