<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\ModelHelper;
use Module\Bot\Domain\Adapters\AdapterEvent;
use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\Model\ReplyModel;
use Module\Bot\Domain\Model\TemplateModel;
use Module\Bot\Domain\Model\UserGroupModel;
use Module\Bot\Domain\Model\UserModel;

class ReplyRepository {

    const EVENT_DEFAULT = 'default';

    public static function eventItems(): array {
        return  [
            self::EVENT_DEFAULT => '默认回复',
            AdapterEvent::Message->getEventName() => '消息',
            AdapterEvent::Subscribe->getEventName() => '关注',
            AdapterEvent::MenuClick->getEventName() => '菜单事件',
        ];
    }

    public static function getList(int $bot_id, string $event = '') {
        AccountRepository::isSelf($bot_id);
        return ReplyModel::where('bot_id', $bot_id)
            ->when(!empty($event), function ($query) use ($event) {
                $query->where('event', $event);
            })->orderBy('status', 'desc')->orderBy('id', 'desc')->page();
    }

    public static function manageList(int $bot_id = 0, string $event = '') {
        return ReplyModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })
            ->when(!empty($event), function ($query) use ($event) {
                $query->where('event', $event);
            })->orderBy('status', 'desc')->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return ReplyModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->bot_id);
        return $model;
    }

    public static function remove(int $id) {
        $model = ReplyModel::find($id);
        AccountRepository::isSelf($model->bot_id);
        $model->delete();
    }

    public static function getByEvent(int $bot_id, string $event) {
        $model = ReplyModel::where('bot_id', $bot_id)->where('event', $event)
            ->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        return $model;
    }

    public static function save(int $bot_id, array $input) {
        $id = $input['id'] ?? 0;
        unset($input['id'], $input['bot_id']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new ReplyModel();
            $model->bot_id = $bot_id;
            AccountRepository::isSelf($model->bot_id);
        }
        $model->load($input);
        if ($model->event !== AdapterEvent::Message->getEventName()) {
            $model->keywords = '';
        }
        EditorInput::save($model, $input);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        ReplyModel::cacheReply($model->bot_id, true);
        return $model;
    }

    public static function update(int $id, array $data) {
        $model = ReplyModel::findOrThrow($id);
        AccountRepository::isSelf($model->bot_id);
        ModelHelper::updateField($model, ['status'], $data);
        ReplyModel::cacheReply($model->bot_id, true);
        return $model;
    }

    public static function send(int $bot_id, int $toType, array|int $to, array $data = []) {
        if ($data['type'] == 3) {
            static::sendTemplate($bot_id, is_array($to) ? reset($to) : $to, $data);
            return;
        }
        AccountRepository::isSelf($bot_id);
        $content = '';
        if ($data['type'] < 1) {
            $content = $data['text'];
        }
        $adapter = BotRepository::entry($bot_id);
        if ($toType < 1) {
            $adapter->sendUsers($content);
            return;
        }
        if ($toType == 1) {
            $groupId = UserGroupModel::whereIn('id', (array)$to)
                ->whereNot('tag_id', '')
                ->value('tag_id');
            $adapter->sendGroup($groupId, $content);
            return;
        }
        $openId = UserModel::whereIn('id', (array)$to)->pluck('openid');
        $adapter->sendAnyUsers($openId, $content);
    }

    public static function sendTemplate(int $bot_id, int $userId, array $data) {
        AccountRepository::isSelf($bot_id);
        if ($userId < 1) {
            throw new \Exception('模板消息只能发给单个用户');
        }
        $openid = UserModel::where('id', $userId)->value('openid');
        if (empty($openid)) {
            throw new \Exception('用户未关注公众号');
        }
        $data['template_data'] = TemplateModel::strToArr($data['template_data']);
        BotRepository::entry($bot_id)
            ->sendTemplate($openid, $data);
    }

    public static function templateList(int $bot_id) {
        AccountRepository::isSelf($bot_id);
        return TemplateModel::where('bot_id', $bot_id)->page();
    }

    public static function asyncTemplate(int $bot_id) {
        AccountRepository::isSelf($bot_id);
        BotRepository::entry($bot_id)
            ->pullTemplate(function (array $item) use ($bot_id) {
                $model = TemplateModel::where('bot_id', $bot_id)
                    ->where('template_id', $item['template_id'])->first();
                if (empty($model)) {
                    $model = new TemplateModel();
                }
                $model->set(['bot_id' => $bot_id,
                    'template_id' => $item['template_id'],
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'example' => $item['example'],]);
                $model->save();
            });
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
        AccountRepository::isSelf($model->bot_id);
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

    public static function templateSave(int $bot_id, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['bot_id']);
        if ($id > 0) {
            AccountRepository::isSelf($bot_id);
            $model = TemplateModel::where('id', $id)->where('bot_id', $bot_id)->first();
        } else {
            $model = new TemplateModel();
            $model->bot_id = $bot_id;
            AccountRepository::isSelf($model->bot_id);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function templateRemove(int $id) {
        $model = TemplateModel::findOrThrow($id, '模板不存在');
        AccountRepository::isSelf($model->bot_id);
        $model->delete();
    }


    public static function getMessageReply(int $bot_id) {
        $data = ReplyModel::where('event', AdapterEvent::Message->getEventName())
            ->where('bot_id', $bot_id)
            ->where('status', 1)
            ->orderBy('`match`', 'asc')
            ->orderBy('updated_at', 'asc')
            ->get('id', 'keywords', '`match`');
        $args = [];
        foreach ($data as $item) {
            foreach (explode(',', $item->keywords) as $val) {
                $val = trim($val);
                if (!empty($val)){
                    $args[$val] = [
                        'id' => $item->id,
                        'match' => $item->match
                    ];
                }
            }
        }
        return $args;
    }

    public static function cacheReply(int $bot_id, bool $refresh = false) {
        $key = 'wx_reply_'. $bot_id;
        if ($refresh) {
            cache()->set($key, static::getMessageReply($bot_id));
        }
        return  cache()->getOrSet($key, function () use ($bot_id) {
            return static::getMessageReply($bot_id);
        });
    }

    /**
     * @param $bot_id
     * @param $content
     * @return int
     */
    public static function findIdWithCache(int $bot_id, string $content) {
        $data = self::cacheReply($bot_id);
        if (isset($data[$content])) {
            return $data[$content]['id'];
        }
        foreach ($data as $key => $item) {
            if ($item['match'] > 0) {
                continue;
            }
            if (str_contains($content, $key . '')) {
                return $item['id'];
            }
        }
        return 0;
    }

    /**
     * @param int $bot_id
     * @param string $content
     * @return ReplyModel|null
     */
    public static function findWithCache(int $bot_id, string $content) {
        $id = self::findIdWithCache($bot_id, $content);
        return $id > 0 ? ReplyModel::where('bot_id', $bot_id)->where('id', $id)->first() : null;
    }

    public static function findWithEvent(string $event, int $bot_id): ReplyModel {
        return ReplyModel::where('event', $event)
            ->where('bot_id', $bot_id)->where('status', 1)->first();
    }

}