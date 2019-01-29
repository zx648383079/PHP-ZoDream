<?php
namespace Module\WeChat\Domain\Model;

use Zodream\ThirdParty\WeChat\EventEnum;

/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @property integer $id
 * @property integer $wid
 * @property string $event
 * @property string $keywords
 * @property integer $match
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReplyModel extends EditorModel {

    /**
     * 激活状态
     */
    const STATUS_ACTIVE = 1;
    /**
     * 禁用状态
     */
    const STATUS_DISABLED = 0;
    const PROCESSOR_DEFAULT = 'process';
    public static $statuses = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DISABLED => '禁用'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_reply';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'event' => 'required|string:0,20',
            'keywords' => 'string:0,60',
            'match' => 'required|int:0,9',
            'content' => 'required',
            'type' => 'required|string:0,10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'event' => 'Event',
            'keywords' => 'Keywords',
            'match' => 'Match',
            'content' => 'Content',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getMessageReply($wid) {
        $data = static::where('event', EventEnum::Message)
            ->where('wid', $wid)
            ->orderBy('`match`', 'asc')
            ->orderBy('updated_at', 'asc')->get('id', 'keywords', '`match`');
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

    public static function cacheReply($wid, $refresh = false) {
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
    public static function findIdWithCache($wid, $content) {
        $data = self::cacheReply($wid);
        if (isset($data[$content])) {
            return $data[$content]['id'];
        }
        foreach ($data as $key => $item) {
            if ($item['match'] > 0) {
                continue;
            }
            if (strpos($content, $key) !== false) {
                return $item['id'];
            }
        }
        return 0;
    }

    /**
     * @param $wid
     * @param $content
     * @return ReplyModel|null
     */
    public static function findWithCache($wid, $content) {
        $id = self::findIdWithCache($wid, $content);
        return $id > 0 ? static::where('wid', $wid)->where('id', $id)->first() : null;
    }

    public static function findWithEvent($event, $wid) {
        return static::where('event', $event)
            ->where('wid', $wid)->first();
    }

}