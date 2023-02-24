<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Adapters;

use Module\Auth\Domain\Model\UserModel;
use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Route\Response\RestResponse;

class EmulateAdapter implements IReplyAdapter {

    public function __construct(
        protected WeChatModel $platform)
    {
    }

    public function platformId(): int
    {
        return $this->platform->id;
    }

    public function oAuthType(): string
    {
        return '';
    }

    public function listen(): Output
    {
        $message = $this->receive();
        $data = (new MessageReply($this, $message))->reply();
        $output = response();
        $this->reply($output, $data);
        return $output;
    }

    public function receive(): array
    {
        $request = request();
        $event = $request->get('type') === 'menu' ? AdapterEvent::MenuClick : AdapterEvent::Message;
        $content = (string)$request->get('content', '');
        if ($event === AdapterEvent::MenuClick) {
            $content = sprintf('menu_%d', $content);
        }
        $from = (string)auth()->id();
        $created_at = time();
        return compact('event', 'content', 'from', 'created_at');
    }

    public function reply(Output $output, array $data)
    {
        $response = RestResponse::createWithAuto($this->replyData($data));
        $response->ready($output);
        return $response;
    }

    public function listenWithBack(): array {
        $message = $this->receive();
        $data = (new MessageReply($this, $message))->reply();
        return $this->replyData($data);
    }

    protected function replyData(array $data): array {
        switch ($data['type']) {
            case EditorInput::TYPE_MEDIA:
                return $this->replyMedia(intval($data['content']));
            case EditorInput::TYPE_NEWS:
                return $this->replyNews(intval($data['content']));
            case EditorInput::TYPE_MINI:
                $editor = Json::decode($data['content']);
                return $this->renderReply($editor['url'], 'url');
            case EditorInput::TYPE_TEMPLATE:
                return $this->replyTemplate(Json::decode($data['content']));
            case EditorInput::TYPE_PHOTO:
            case EditorInput::TYPE_PICTURE:
            case EditorInput::TYPE_SCAN:
                return $this->renderReply('[不支持此信息]');
            case  EditorInput::TYPE_URL:
                return $this->renderReply($data['content'], 'url');
            default:
                return $this->renderReply((string)$data['content']);
        }
    }

    protected function replyTemplate(array $data) {
        /** @var TemplateModel $model */
        $model = TemplateModel::where('template_id', $data['template_id'])->first();
        if (empty($model)) {
            return $this->renderReply('[模板不存在]');
        }
        return [
            'type' => 'template',
            'content' => $model->preview($data['template_data']),
            'url' => $data['template_url'],
        ];
    }

    protected function replyNews(int $newsId) {
        $items = MediaModel::where('id', $newsId)
            ->orWhere('parent_id', $newsId)->orderBy('parent_id', 'asc')->get();
        return [
            'type' => 'news',
            'items' => array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'thumb' => $item->thumb,
                    'url' => url('./emulate/media', ['id' => $item->id]),
                ];
            }, $items)
        ];
    }
    protected function replyMedia(int $mediaId) {
        $model = MediaModel::find($mediaId);
        if (empty($model)) {
            return $this->renderReply('内容有误');
        }
        return [
            'type' => $model->type,
            'content' => $model->content,
            'title' => $model->title,
            'thumb' => $model->thumb
        ];
    }

    protected function renderReply(string $content, string $type = 'text') {
        return compact('content', 'type');
    }



    public function authUser(string $openId): ?UserModel
    {
        return auth()->guest() ? null : auth()->user();
    }

    public function authUserId(string $openId): int
    {
        return auth()->guest() ? 0 : auth()->id();
    }
}