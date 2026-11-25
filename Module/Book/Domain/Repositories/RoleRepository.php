<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Entities\BookMetaEntity;
use Zodream\Helpers\Json;

final class RoleRepository {

    const TYPE_ROLE = 0;
    const TYPE_ROLE_RELATION = 1;
    const TYPE_EVENT = 5;
    const TYPE_RULE = 7;
    const TYPE_OUTLINE = 8;
    const TYPE_MAP = 9;

    public static function roleList(int $book, string $keywords = '') {
        return BookMetaEntity::where('type', self::TYPE_ROLE)->where('target_id', $book)->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'keywords']);
            })->page();
    }

    public static function roleAll(int $book) {
        $items = BookMetaEntity::where('type', self::TYPE_ROLE)->where('target_id', $book)->get();
        $link_items = [];
        if (empty($items)) {
            return compact('items', 'link_items');
        }
        $link_items = self::relationGet($book);
        return compact('items', 'link_items');
    }


    public static function roleSave(int $book, array $data) {
        $model = self::metaSave(self::TYPE_ROLE, $book, $data);
        static::linkAdd($book, $model->id, $data['link_id'] ?? 0, $data['link_title'] ?? '');
        return $model;
    }

    public static function roleRemove(int $book, int $id) {
        self::metaRemove(self::TYPE_ROLE, $book, $id);
        $data = self::relationGet($book);
        if (empty($data)) {
            return;
        }
        $data = array_filter($data, function (array $item) use ($id) {
            return $item['role_id'] !== $id && $item['role_link'] !== $id;
        });
        self::relationSave($book, $data);
    }

    public static function bookId(int $from, int $to): int {
        return intval(BookMetaEntity::query()->whereIn('id', [$from, $to])->value('target_id'));
    }

    public static function linkAdd(int $book, int $from, int $to, string $title) {
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        if ($from < 1 || $to < 1) {
            return;
        }
        $data = self::relationGet($book);
        $data[] = [
            'role_id' => $from,
            'role_link' => $to,
            'title' => $title
        ];
        self::relationSave($book, $data);
    }

    public static function linkRemove(int $book, int $from, int $to) {
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        if ($from < 1 || $to < 1) {
            return;
        }
        $data = self::relationGet($book);
        if (empty($data)) {
            return;
        }
        $data = array_filter($data, function (array $item) use ($from, $to) {
            return $item['role_id'] !== $from || $item['role_link'] !== $to;
        });
        self::relationSave($book, $data);
    }

    public static function linkSave(int $book, array $items) {
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        $data = [];
        foreach($items as $item) {
            $item['role_id'] = intval($item['role_id']);
            $item['role_link'] = intval($item['role_link']);
            if ($item['role_id'] === 0 || $item['role_link'] === 0) {
                continue;
            }
            $key = sprintf('%d-%d', $item['role_id'], $item['role_link']);
            $data[$key] = $item;
        }
        self::relationSave($book, array_values($data));
    }

    public static function mapGet(int $book): array {
        $value = BookMetaEntity::where('type', self::TYPE_MAP)->where('target_id', $book)
            ->value('content');
        if (!empty($value)) {
            return Json::decode($value);
        }
        return [];
    }

    public static function mapSave(int $book, array $data): void {
        $exist = BookMetaEntity::where('type', self::TYPE_MAP)->where('target_id', $book)->any();
        if ($exist) {
            BookMetaEntity::where('type', self::TYPE_MAP)->where('target_id', $book)->update([
                'content' => Json::encode($data)
            ]);
        } else {
            BookMetaEntity::query()->insert([
                'target_id' => $book,
                'type' => self::TYPE_MAP,
                'name' => 'map',
                'content' => Json::encode($data)
            ]);
        }
    }

    public static function metaList(int $type, int $book, string $keywords = '', string $time = '') {
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        return BookMetaEntity::where('type', $type)->where('target_id', $book)->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'keywords']);
            })->when(!empty($time), function($query) use ($time) {
                $query->where('start_at', '>=', $time)->where('end_at', '<=', $time);
            })->page();
    }

    public static function metaSave(int $type, int $book, array $data): BookMetaEntity {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        $model = $id > 0 ? 
            BookMetaEntity::where('type', $type)->where('target_id', $book)->where('id', $id)->first() 
            : new BookMetaEntity();
        if (empty($model)) {
            throw new \Exception('数据错误');
        }
        $model->load($data);
        $model->target_id = $book;
        $model->type = $type;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function metaRemove(int $type, int $book, int $id): void {
        if (!BookRepository::isSelf($book)) {
            throw new \Exception('无权限');
        }
        $model = BookMetaEntity::where('type', $type)->where('target_id', $book)->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('事件不存在');
        }
        $model->delete();
    }

    public static function eventList(int $book, string $keywords = '', string $time = '') {
        return self::metaList(self::TYPE_EVENT, $book, $keywords,  $time);
    }

    public static function eventSave(int $book, array $data) {
        return self::metaSave(self::TYPE_EVENT, $book, $data);
    }

    public static function eventRemove(int $book, int $id) {
        self::metaRemove(self::TYPE_EVENT, $book, $id);
    }

    public static function outlineList(int $book, string $keywords = '') {
        return self::metaList(self::TYPE_OUTLINE, $book, $keywords);
    }

    public static function outlineSave(int $book, array $data) {
        return self::metaSave(self::TYPE_OUTLINE, $book, $data);
    }

    public static function outlineRemove(int $book, int $id) {
        self::metaRemove(self::TYPE_OUTLINE, $book, $id);
    }

    public static function ruleList(int $book, string $keywords = '') {
        return self::metaList(self::TYPE_RULE, $book, $keywords);
    }

    public static function ruleSave(int $book, array $data) {
        return self::metaSave(self::TYPE_RULE, $book, $data);
    }

    public static function ruleRemove(int $book, int $id) {
        self::metaRemove(self::TYPE_RULE, $book, $id);
    }

    private static function relationGet(int $book): array {
        $value = BookMetaEntity::where('type', self::TYPE_ROLE_RELATION)->where('target_id', $book)
            ->value('content');
        if (!empty($value)) {
            return Json::decode($value);
        }
        return [];
    }

    private static function relationSave(int $book, array $data): void {
        $exist = BookMetaEntity::where('type', self::TYPE_ROLE_RELATION)->where('target_id', $book)->any();
        if ($exist) {
            BookMetaEntity::where('type', self::TYPE_ROLE_RELATION)->where('target_id', $book)->update([
                'content' => Json::encode($data)
            ]);
        } else {
            BookMetaEntity::query()->insert([
                'target_id' => $book,
                'type' => self::TYPE_ROLE_RELATION,
                'name' => 'role_link',
                'content' => Json::encode($data)
            ]);
        }
    }
}