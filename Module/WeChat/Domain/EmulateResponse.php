<?php
declare(strict_types=1);
namespace Module\WeChat\Domain;

use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Zodream\Infrastructure\Contracts\ArrayAble;
use Zodream\ThirdParty\WeChat\MessageResponse;

class EmulateResponse extends MessageResponse implements ArrayAble {

    protected array $sourceData = [];

    public function setText($arg) {
        $this->sourceData = [
            'type' => 'text',
            'content' => $arg
        ];
        return $this;
    }

    public function setUrl(string $url) {
        $this->sourceData = [
            'type' => 'url',
            'content' => $url
        ];
        return $this;
    }

    public function setTemplate(string $templateId, array|string $data, string $url) {
        /** @var TemplateModel $model */
        $model = TemplateModel::where('template_id', $templateId)->first();
        if (empty($model)) {
            return $this->setText('模板不存在');
        }
        $this->sourceData = [
            'type' => 'template',
            'content' => $model->preview($data),
            'url' => $url,
        ];
        return $this;
    }

    public function setMedia(MediaModel $model) {
        if ($model->type !== MediaModel::TYPE_NEWS) {
            $this->sourceData = [
                'type' => $model->type,
                'content' => $model->content,
                'title' => $model->title,
                'thumb' => $model->thumb
            ];
            return $this;
        }
        $items = [$model];
        $data = MediaModel::where('type', MediaModel::TYPE_NEWS)
            ->where('parent_id', $model->id)->get();
        if (!empty($data)) {
            $items = array_merge($items, $data);
        }
        $this->sourceData = [
            'type' => 'news',
            'items' => array_map(function ($item) {
                return $this->formatNews($item);
            }, $items)
        ];
        return $this;
    }

    private function formatNews(MediaModel $item) {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'thumb' => $item->thumb,
            'url' => url('./emulate/media', ['id' => $item->id]),
        ];
    }

    public function toArray() {
        return $this->sourceData;
    }

    protected function parseData(array $data): array {
        if ($data['MsgType']['@cdata'] === 'text') {
            return [
                'type' => 'text',
                'content' => $data['Content']['@cdata']
            ];
        }
        if ($data['MsgType']['@cdata'] === 'news') {
            return [
                'type' => 'news',
                'items' => array_map(function ($item) {
                    return [
                        'title' => $item['Title']['@cdata'],
                        'thumb' => $item['PicUrl']['@cdata'],
                        'url' => url('./emulate/media', ['id' =>
                            MediaModel::where('title', $item['Title']['@cdata'])->value('id')])
                    ];
                }, $data['Articles']['item'])
            ];
        }
        return [];
    }
}