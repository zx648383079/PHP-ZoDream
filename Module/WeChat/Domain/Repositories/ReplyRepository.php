<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Database\Model\Model;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Mass;
use Zodream\ThirdParty\WeChat\Template;

class ReplyRepository {

    public static function getList(int $wid, string $event = '') {
        return ReplyModel::where('wid', $wid)
            ->when(!empty($event), function ($query) use ($event) {
                $query->where('event', $event);
            })->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return ReplyModel::findOrThrow($id, '数据有误');
    }

    public static function getByEvent(int $wid, string $event) {
        $model = ReplyModel::where('wid', $wid)->where('event', $event)
            ->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        return $model;
    }

    public static function remove(int $id) {
        ReplyModel::where('id', $id)->delete();
    }

    public static function save(int $wid, Input $input) {
        $model = new ReplyModel();
        $model->load();
        if ($model->event != EventEnum::Message) {
            $model->keywords = null;
        }
        EditorInput::save($model, $input);
        $model->wid = $wid;
        if (!$model->autoIsNew()->save()) {
            throw new \Exception($model->getFirstError());
        }
        ReplyModel::cacheReply($model->wid, true);
        return $model;
    }

    public static function send(int $wid, int $user_id = 0, array $editor = []) {
        if ($editor['type'] === 3) {
            return static::sendTemplate($wid, $user_id, $editor['template_id'], $editor['template_url'], $editor['template_data']);
        }
        $data = '';
        $type = Mass::TEXT;
        if ($editor['type'] < 1) {
            $data = $editor['text'];
        }
        /** @var Mass $api */
        $api = WeChatModel::find($wid)
            ->sdk(Mass::class);
        $openid = null;
        if ($user_id > 0) {
            $openid = UserModel::where('id', $user_id)->value('openid');
        }
        return empty($openid) ? $api->sendAll($data, $type) : $api->send([$openid], $data, $type);
    }

    public static function sendTemplate(int $wid, int $user_id, string $template_id, string $url, array|string $data) {
        if ($user_id < 1) {
            throw new \Exception('模板消息只能发给单个用户');
        }
        $openid = UserModel::where('id', $user_id)->value('openid');
        if (empty($openid)) {
            throw new \Exception('用户未关注公众号');
        }
        /** @var Template $api */
        $api = WeChatModel::find($wid)
            ->sdk(Template::class);
        $res = $api->send($openid, $template_id, url($url), TemplateModel::strToArr($data));
        if (!$res) {
            throw new \Exception('发送失败');
        }
        return $res;
    }

    public static function templateList(int $wid) {
        return TemplateModel::where('wid', $wid)->page();
    }

    public static function asyncTemplate(int $wid) {
        /** @var Template $api */
        $api = WeChatModel::find($wid)
            ->sdk(Template::class);
        $data = $api->allTemplate();
        if (!isset($data['template_list'])) {
            throw new \Exception('同步失败');
        }
        TemplateModel::where('wid', $wid)->delete();
        foreach ($data['template_list'] as $item) {
            TemplateModel::create([
                'wid' => $wid,
                'template_id' => $item['template_id'],
                'title' => $item['title'],
                'content' => $item['content'],
                'example' => $item['example'],
            ]);
        }
    }

    public static function template(string $id) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            throw new \Exception('模板不存在');
        }
        return $model->getFields();
    }

    public static function templatePreview(string $id, array $data) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            throw new \Exception('模板不存在');
        }
        return $model->preview($data);
    }
}