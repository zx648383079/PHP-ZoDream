<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters\WeChat;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Bot\Domain\Adapters\AdapterEvent;
use Module\Bot\Domain\Adapters\BasePlatformAdapter;
use Module\Bot\Domain\Adapters\IPlatformAdapter;
use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Model\QrcodeModel;
use Module\Bot\Domain\Model\UserModel;
use Module\Bot\Domain\Repositories\MediaRepository;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\ThirdParty\WeChat\Account;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Mass;
use Zodream\ThirdParty\WeChat\Media;
use Zodream\ThirdParty\WeChat\Menu;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;
use Zodream\ThirdParty\WeChat\NewsItem;
use Zodream\ThirdParty\WeChat\Template;
use Zodream\ThirdParty\WeChat\User;

class WxAdapter extends BasePlatformAdapter implements IPlatformAdapter {

    protected function messageSdk(): Message {
        return $this->cacheSdk(Message::class);
    }

    protected function massSdk(): Mass {
        return $this->cacheSdk(Mass::class);
    }

    protected function mediaSdk(): Media {
        return $this->cacheSdk(Media::class);
    }

    protected function userSdk(): User {
        return $this->cacheSdk(user::class);
    }

    protected function accountSdk(): Account {
        return $this->cacheSdk(Account::class);
    }

    protected function menuSdk(): Menu {
        return $this->cacheSdk(Menu::class);
    }

    protected function templateSdk(): Template {
        return $this->cacheSdk(Template::class);
    }

    public function oAuthType(): string {
        return OAuthModel::TYPE_WX;
    }

    protected function replyAccessVerification(): Output {
        $this->messageSdk()->valid();
        return response();
    }

    public function receive(): array {
        $messageSdk = $this->messageSdk();
        if ($messageSdk->isValid()) {
            return [
                'event' => AdapterEvent::AccessVerification
            ];
        }
        // 可以通过ip 和 $this->platform->original === $messageSdk->getTo() 验证真实性
        //if (!$messageSdk->verifyServer($this->platform->original)) {
            //return $messageSdk->getResponse();
        //}
        switch ($messageSdk->getEvent()) {
            case EventEnum::Message:
                return $this->formatReceive(AdapterEvent::Message, (string)$messageSdk->content, $messageSdk);
            case EventEnum::Subscribe:
            case EventEnum::ScanSubscribe:
                return $this->formatReceive(AdapterEvent::Subscribe, $messageSdk->eventKey, $messageSdk);
            case EventEnum::UnSubscribe:
                return $this->formatReceive(AdapterEvent::UnSubscribe, '', $messageSdk);
            case EventEnum::Click:
                return $this->formatReceive(AdapterEvent::MenuClick, $messageSdk->eventKey, $messageSdk);
            default:
                // TODO ANY
                return $this->formatReceive(AdapterEvent::Message, (string)$messageSdk->content, $messageSdk);
        }
    }

    protected function formatReceive(AdapterEvent $event, string $content, Message $messageSdk) {
        $from = $messageSdk->getFrom();
        $created_at = $messageSdk->createTime;
        return compact('event', 'content', 'from', 'created_at');
    }

    public function reply(Output $output, array $data) {
        $response = $this->messageSdk()->getResponse();
        switch ($data['type']) {
            case EditorInput::TYPE_MEDIA:
                $this->replyMedia($response, intval($data['content']));
                break;
            case EditorInput::TYPE_NEWS:
                $this->replyNews($response, intval($data['content']));
                break;
            case EditorInput::TYPE_MINI:
                $editor = Json::decode($data['content']);
                $response->setText($editor['url']);
                break;
            case EditorInput::TYPE_TEMPLATE:
            case EditorInput::TYPE_PHOTO:
            case EditorInput::TYPE_PICTURE:
            case EditorInput::TYPE_SCAN:
                $response->setText('[不支持此信息]');
                break;
            default:
                $response->setText($data['content']);
                break;
        }
        return $response->ready($output);
    }
    protected function replyNews(MessageResponse $response, int $newsId) {
        $model_list = MediaModel::where('id', $newsId)
            ->orWhere('parent_id', $newsId)->orderBy('parent_id', 'asc')->get();
        foreach ($model_list as $item) {
            /** @var $item MediaModel */
            $picUrl = MediaModel::where('type', MediaModel::TYPE_IMAGE)
                ->where('content', $item->thumb)->value('url');
            $response->addNews($item->title, $item->title,
                $picUrl
            );
        }
    }
    protected function replyMedia(MessageResponse $response, int $mediaId) {
        $model = MediaModel::find($mediaId);
        if (empty($model) || !$model->media_id) {
            return $response->setText('内容有误');
        }
        if ($model->type === MediaModel::TYPE_IMAGE) {
            return $response->setImage($model->media_id);
        }
        if ($model->type === MediaModel::TYPE_VIDEO) {
            return $response->setVideo($model->media_id, $model->title);
        }
        if ($model->type === MediaModel::TYPE_VOICE) {
            return $response->setVoice($model->media_id);
        }
        return $response->setText('内容有误');
    }

    protected function formatUser(array $info): array {
        return [
            'openid' => $info['openid'],
            'nickname' => $info['nickname'],
            'sex' => $info['sex'],
            'city' => $info['city'],
            'country' => $info['country'],
            'province' => $info['province'],
            'language' => $info['language'],
            'avatar' => $info['headimgurl'],
            'subscribe_at' => $info['subscribe_time'],
            'remark' => $info['remark'],
            'union_id' => $info['unionid'] ?? '', // 测试号无此项
            'group_id' => $info['groupid'],
            'status' => $info['subscribe'] > 0 ? UserModel::STATUS_SUBSCRIBED
                : UserModel::STATUS_UNSUBSCRIBED,
        ];
    }



    public function pullUser(string $openId): array {
        $data = $this->userSdk()->userInfo($openId);
        if (empty($data)) {
            return [];
        }
        return $this->formatUser($data);
    }

    public function pullUsers(callable $cb)
    {
        $api = $this->userSdk();
        $next_openid = null;
        /** @var User $api */
        while (true) {
            $openid_list = $api->userList($next_openid);
            if (empty($openid_list['data']['openid'])) {
                break;
            }
            $data = $api->usersInfo($openid_list['data']['openid']);
            foreach ($data['user_info_list'] as $item) {
                call_user_func($cb, $this->formatUser($item));
            }
            if (empty($openid_list['next_openid'])) {
                break;
            }
            $next_openid = $openid_list['next_openid'];
        }
    }

    public function pushMenu(array $items)
    {
        $api = $this->menuSdk();
        if (count($items) < 1) {
            $api->deleteMenu();
            return;
        }

        $api->create(MenuItem::menu(array_map(function ($menu) {
            return $this->renderMenu($menu);
        }, $items)));
    }

    protected function renderMenu(array $data) {
        $menu = MenuItem::name($data['name']);
        $children = $data['children'];
        if (!empty($children)) {
            return $menu->setMenu(array_map(function ($model) {
                return $this->renderMenu($model);
            }, $children));
        }
        switch ($data['type']) {
            case EditorInput::TYPE_MEDIA:
            case EditorInput::TYPE_EVENT:
            case EditorInput::TYPE_NEWS:
            case EditorInput::TYPE_SCENE:
            case EditorInput::TYPE_TEMPLATE:
            case EditorInput::TYPE_TEXT:
                return $menu->setKey('menu_'.$data['id']);
            case EditorInput::TYPE_LOCATION:
                return $menu->setType(MenuItem::LOCATION);
            case EditorInput::TYPE_PHOTO:
                return $menu->setType(MenuItem::SYSTEM_PHOTO);
            case EditorInput::TYPE_PICTURE:
                return $menu->setType(MenuItem::SYSTEM_PHOTO_ALBUM);
            case EditorInput::TYPE_SCAN:
                return $menu->setType(MenuItem::SCAN_CODE_MSG);
            case EditorInput::TYPE_URL:
                return $menu->setUrl($data['content']);
            case EditorInput::TYPE_MINI:
                $editor = Json::decode($data['content']);
                return $menu->setMini($editor['appid'], $editor['path'], $editor['url']);
        }
        return $menu;
    }

    public function pushQr(QrcodeModel $model): array
    {
        $res = $this->accountSdk()->qrCode($model->scene_type < 1 ? $model->scene_str : intval($model->scene_id),
            $model->type > 0 ? false : intval($model->expire_time));
        return [
            'qr_url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($res['ticket']),
            'url' => $res['url']
        ];
    }

    public function sendUsers(string $content) {
        $this->massSdk()->sendAll($content, Mass::TEXT);
    }
    public function sendGroup(string $group, string $content) {
        $this->massSdk()->sendAll($content, Mass::TEXT, $group);
    }
    public function sendAnyUsers(array $openid, string $content) {
        $this->massSdk()->send($openid, $content, Mass::TEXT);
    }

    public function sendTemplate(string $openid, array $data) {
        $res = $this->templateSdk()
            ->send($openid, $data['template_id'],
            !empty($data['template_url']) ? url($data['template_url']) : '',
            $data['template_data'],
            !empty($data['appid']) ? [
                'appid' => $data['appid'],
                'pagepath' => $data['path']
            ] : []);
        if (!$res) {
            throw new \Exception('发送失败');
        }
    }

    public function pullTemplate(callable $cb)
    {
        $items = $this->templateSdk()->allTemplate();
        if (!isset($items['template_list'])) {
            throw new \Exception('同步失败');
        }
        foreach ($items as $item) {
            call_user_func($cb, $item);
        }
    }

    public function pushMedia(MediaModel $model) {
        $api = $this->mediaSdk();
        $file = MediaRepository::formatFile($model->content);
        if ($model->material_type != MediaModel::MATERIAL_PERMANENT) {
            $res = $api->uploadTemp($file, $model->type);
        } else {
            $res = $api->addMedia($file, $model->type);
        }
        if (isset($res['errcode']) && !isset($res['media_id'])) {
            throw new \Exception($res['errmsg']);
        }
        if (isset($res['media_id'])) {
            $model->media_id = $res['media_id'];
        }
        if ($res['url']) {
            $model->url = $res['url'];
        }
        $model->save();
    }

    public function pushNews(MediaModel $model) {
        if ($model->parent_id > 0) {
            throw new \Exception('只有主素材才能上传');
        }
        $news = new NewsItem();
        $news->setArticle($this->converterNews($model));
        $child = MediaModel::where('parent_id', $model->id)
            ->where('bot_id', $this->platformId())->get();
        foreach ($child as $item) {
            $news->setArticle($this->converterNews($item));
        }
        if (!$model->media_id) {
            $media_id = $this->mediaSdk()->addNews($news);
            if (empty($media_id)) {
                throw new \Exception('上传失败');
            }
            $model->media_id = $media_id;
            $model->save();
        } else {
            $news->setMediaId($model->media_id);
            $this->mediaSdk()->updateNews($news);
        }
        return true;
    }


    private function converterNews(MediaModel $model) {
        $news = new NewsItem();
        // 处理封面
        $thumb = $this->getThumbMediaId($model);
        if (empty($thumb)) {
            throw new \Exception('封面上传失败');
        }
        $news->setThumb($thumb);
        // 处理内容图片路径
        $news->setContent($this->renderContent($model));
        return $news->setTitle($model->title)
            ->setShowCover($model->show_cover)
            ->setOnlyFansCanComment($model->only_comment)
            ->setNeedOpenComment($model->open_comment)
            ->setUrl(url('./emulate/media', ['id' => $model->id]));
    }

    private function getThumbMediaId(MediaModel $parent) {
        $model = MediaModel::where('type', MediaModel::TYPE_IMAGE)
            ->where('bot_id', $this->platformId())
            ->where('content', $parent->thumb)->first();
        if (!$model) {
            $model = new MediaModel([
                'bot_id' => $this->platformId(),
                'title' => $parent->title.'-封面',
                'material_type' => MediaModel::MATERIAL_PERMANENT,
                'type' => MediaModel::TYPE_IMAGE,
                'content' => $parent->thumb
            ]);
        }
        if ($model->media_id) {
            return $model->media_id;
        }
        $this->pushMedia($model);
        return $model->media_id;
    }

    private function renderContent(MediaModel $model): string {
        $content = preg_replace_callback('/src=["\']?([^\s"\'>]+)/', function ($match) {
            return str_replace($match[1], $this->getImgUrl($match[1]), $match[0]);
        }, $model->content);
        return preg_replace_callback('/url\(["\']?([^\s"\'>)]+)/', function ($match) {
            return str_replace($match[1], $this->getImgUrl($match[1]), $match[0]);
        }, $content);
    }

    /**
     * 转化为微信接受的图片链接
     * @param $path
     * @return bool|string
     * @throws \Exception
     */
    private function getImgUrl($path) {
        if (strpos($path, 'data:') >= 0) {
            return $path;
        }
        if (strpos($path, 'qlogo.cn') > 0) {
            return $path;
        }
        if (strpos($path, 'qpic.cn') > 0) {
            return $path;
        }
        return $this->mediaSdk()->uploadImg(MediaRepository::formatFile($path));
    }


    public function pullMedia(string $type)
    {
        $api = $this->mediaSdk();
        $total = 0;
        $offset = 0;
        $size = 20;
        do {
            $res = $api->mediaList($type, $offset, $size);
            if (isset($res['errcode'])) {
                throw new \Exception($res['errmsg']);
            }
            $total = $res['total_count'];
            foreach ($res['item'] as $item) {
                $model = MediaModel::where('media_id', $item['media_id'])
                    ->where('bot_id', $this->platformId())
                    ->first();
                if (empty($model)) {
                    $model = new MediaModel([
                        'bot_id' => $this->platformId(),
                        'type' => $type,
                        'media_id' => $item['media_id']
                    ]);
                }
                $model->material_type = MediaModel::MATERIAL_PERMANENT;
                if ($type !== Media::NEWS) {
                    $model->content = $model->url = $item['url'];
                    $model->title = $item['name'];
                    $model->save();
                    continue;
                }
                $first = $item['content']['news_item'][0];
                if (!$model->getIsNewRecord()) {
                    $this->pullANews($first, $model);
                    $model->save();
                    continue;
                }
                $this->pullANews($first, $model);
                $model->save();
                for ($i = 1; $i < count($item['content']['news_item']); $i ++) {
                    $next = new MediaModel([
                        'bot_id' => $this->platformId(),
                        'type' => $type,
                        'parent_id' => $model->id,
                    ]);
                    $this->pullANews($item['content']['news_item'][$i], $next);
                    $next->save();
                }
            }
            $offset += $size;
        } while ($total > $offset + 1);
    }

    private function pullANews(array $data, MediaModel $model) {
        $model->title = $data['title'];
        $model->url = $data['url'];
        $model->content = $data['content'];
        $model->show_cover = $data['show_cover_pic'];
        if (!empty($data['thumb_media_id'])) {
            $model->thumb = MediaModel::where('media_id', $data['thumb_media_id'])
                ->where('bot_id', $model->bot_id)
                ->value('content');
        }
    }

    public function deleteMedia(string $mediaId) {
        $this->mediaSdk()->deleteMedia($mediaId);
    }

}