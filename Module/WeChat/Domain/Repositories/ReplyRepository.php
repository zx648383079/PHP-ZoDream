<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\ModelHelper;
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
        AccountRepository::isSelf($wid);
        return ReplyModel::where('wid', $wid)
            ->when(!empty($event), function ($query) use ($event) {
                $query->where('event', $event);
            })->orderBy('status', 'desc')->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return ReplyModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function remove(int $id) {
        $model = ReplyModel::find($id);
        AccountRepository::isSelf($model->wid);
        $model->delete();
    }

    public static function getByEvent(int $wid, string $event) {
        $model = ReplyModel::where('wid', $wid)->where('event', $event)
            ->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        return $model;
    }

    public static function save(int $wid, array $input) {
        $id = $input['id'] ?? 0;
        unset($input['id'], $input['wid']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new ReplyModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
        }
        $model->load($input);
        if ($model->event != EventEnum::Message) {
            $model->keywords = '';
        }
        EditorInput::save($model, $input);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        ReplyModel::cacheReply($model->wid, true);
        return $model;
    }

    public static function update(int $id, array $data) {
        $model = ReplyModel::findOrThrow($id);
        AccountRepository::isSelf($model->wid);
        ModelHelper::updateField($model, ['status'], $data);
        ReplyModel::cacheReply($model->wid, true);
        return $model;
    }

    public static function send(int $wid, int $user_id = 0, array $editor = []) {
        if ($editor['type'] === 3) {
            return static::sendTemplate($wid, $user_id, $editor['template_id'], $editor['template_url'], $editor['template_data']);
        }
        AccountRepository::isSelf($wid);
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
        AccountRepository::isSelf($wid);
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
        AccountRepository::isSelf($wid);
        return TemplateModel::where('wid', $wid)->page();
    }

    public static function asyncTemplate(int $wid) {
        AccountRepository::isSelf($wid);
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
        $model = static::templateDetail($id);
        return $model->getFields();
    }

    public static function templateDetail(string $id): TemplateModel {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            throw new \Exception('模板不存在');
        }
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function templatePreview(string $id, array $data) {
        $model = static::templateDetail($id);
        return $model->preview($data);
    }

    public static function sceneList(): array {
        $data = [];
        foreach (EditorInput::$scene_list as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $data;
    }
}