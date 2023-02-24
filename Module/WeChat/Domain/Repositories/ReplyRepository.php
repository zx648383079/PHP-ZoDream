<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\ModelHelper;
use Module\WeChat\Domain\Adapters\AdapterEvent;
use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\UserGroupModel;
use Module\WeChat\Domain\Model\UserModel;

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
        if ($model->event !== AdapterEvent::Message->getEventName()) {
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

    public static function send(int $wid, int $toType, array|int $to, array $data = []) {
        if ($data['type'] == 3) {
            static::sendTemplate($wid, is_array($to) ? reset($to) : $to, $data);
            return;
        }
        AccountRepository::isSelf($wid);
        $content = '';
        if ($data['type'] < 1) {
            $content = $data['text'];
        }
        $adapter = PlatformRepository::entry($wid);
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

    public static function sendTemplate(int $wid, int $userId, array $data) {
        AccountRepository::isSelf($wid);
        if ($userId < 1) {
            throw new \Exception('模板消息只能发给单个用户');
        }
        $openid = UserModel::where('id', $userId)->value('openid');
        if (empty($openid)) {
            throw new \Exception('用户未关注公众号');
        }
        $data['template_data'] = TemplateModel::strToArr($data['template_data']);
        PlatformRepository::entry($wid)
            ->sendTemplate($openid, $data);
    }

    public static function templateList(int $wid) {
        AccountRepository::isSelf($wid);
        return TemplateModel::where('wid', $wid)->page();
    }

    public static function asyncTemplate(int $wid) {
        AccountRepository::isSelf($wid);
        PlatformRepository::entry($wid)
            ->pullTemplate(function (array $item) use ($wid) {
                $model = TemplateModel::where('wid', $wid)
                    ->where('template_id', $item['template_id'])->first();
                if (empty($model)) {
                    $model = new TemplateModel();
                }
                $model->set(['wid' => $wid,
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

    public static function templateSave(int $wid, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['wid']);
        if ($id > 0) {
            AccountRepository::isSelf($wid);
            $model = TemplateModel::where('id', $id)->where('wid', $wid)->first();
        } else {
            $model = new TemplateModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function templateRemove(int $id) {
        $model = TemplateModel::findOrThrow($id, '模板不存在');
        AccountRepository::isSelf($model->wid);
        $model->delete();
    }


    public static function getMessageReply(int $wid) {
        $data = ReplyModel::where('event', AdapterEvent::Message->getEventName())
            ->where('wid', $wid)
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

    public static function cacheReply(int $wid, bool $refresh = false) {
        $key = 'wx_reply_'. $wid;
        if ($refresh) {
            cache()->set($key, static::getMessageReply($wid));
        }
        return  cache()->getOrSet($key, function () use ($wid) {
            return static::getMessageReply($wid);
        });
    }

    /**
     * @param $wid
     * @param $content
     * @return int
     */
    public static function findIdWithCache(int $wid, string $content) {
        $data = self::cacheReply($wid);
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
     * @param int $wid
     * @param string $content
     * @return ReplyModel|null
     */
    public static function findWithCache(int $wid, string $content) {
        $id = self::findIdWithCache($wid, $content);
        return $id > 0 ? ReplyModel::where('wid', $wid)->where('id', $id)->first() : null;
    }

    public static function findWithEvent(string $event, int $wid): ReplyModel {
        return ReplyModel::where('event', $event)
            ->where('wid', $wid)->where('status', 1)->first();
    }

}