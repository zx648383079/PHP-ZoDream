<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Entities\BookEventEntity;
use Module\Book\Domain\Entities\BookMetaEntity;
use Module\Book\Domain\Entities\BookRoleEntity;
use Zodream\Helpers\Json;

final class RoleRepository {

    public static function getList(int $book, string $keywords = '') {
        return BookRoleEntity::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'identity']);
            })->where('book_id', $book)->page();
    }

    public static function all(int $book) {
        $items = BookRoleEntity::where('book_id', $book)->get();
        $link_items = [];
        if (empty($items)) {
            return compact('items', 'link_items');
        }
        $link_items = self::relationGet($book);
        return compact('items', 'link_items');
    }


    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookRoleEntity::findOrNew($id);
        $model->load($data);
        $bookId = intval($model->book_id);
        if (!BookRepository::isSelf($bookId)) {
            throw new \Exception('无权限');
        }
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        static::linkAdd($bookId, $model->id, $data['link_id'] ?? 0, $data['link_title'] ?? '');
        return $model;
    }

    public static function remove(int $id) {
        $model = BookRoleEntity::find($id);
        if (empty($model) || !BookRepository::isSelf($model->book_id)) {
            throw new \Exception('角色不存在');
        }
        $model->delete();
        $data = self::relationGet($model->book_id);
        if (empty($data)) {
            return;
        }
        $data = array_filter($data, function (array $item) use ($id) {
            return $item['role_id'] !== $id && $item['role_link'] !== $id;
        });
        self::relationSave($model->book_id, $data);
    }

    public static function bookId(int $from, int $to): int {
        return intval(BookRoleEntity::query()->whereIn('id', [$from, $to])->value('book_id'));
    }

    public static function linkAdd(int $book, int $from, int $to, string $title) {
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
        $value = BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_MAP_KEY)->value('content');
        if (!empty($value)) {
            return Json::decode($value);
        }
        return [];
    }

    public static function mapSave(int $book, array $data): void {
        $exist = BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_MAP_KEY)->any();
        if ($exist) {
            BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_MAP_KEY)->update([
                'content' => Json::encode($data)
            ]);
        } else {
            BookMetaEntity::query()->insert([
                'target_id' => $book,
                'name' => BookRepository::META_MAP_KEY,
                'content' => Json::encode($data)
            ]);
        }
    }

    public static function eventList(int $book, string $keywords = '', string $time = '') {
        return BookEventEntity::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'location', 'role']);
            })->when(!empty($time), function($query) use ($time) {
                $query->where('start_at', '>=', $time)->where('end_at', '<=', $time);
            })->where('book_id', $book)->page();
    }

    public static function eventSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookEventEntity::findOrNew($id);
        $model->load($data);
        $bookId = intval($model->book_id);
        if (!BookRepository::isSelf($bookId)) {
            throw new \Exception('无权限');
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function eventRemove(int $id) {
        $model = BookEventEntity::find($id);
        if (empty($model) || !BookRepository::isSelf($model->book_id)) {
            throw new \Exception('事件不存在');
        }
        $model->delete();
    }

    private static function relationGet(int $book): array {
        $value = BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_ROLE_KEY)->value('content');
        if (!empty($value)) {
            return Json::decode($value);
        }
        return [];
    }

    private static function relationSave(int $book, array $data): void {
        $exist = BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_ROLE_KEY)->any();
        if ($exist) {
            BookMetaEntity::query()->where('target_id', $book)
            ->where('name', BookRepository::META_ROLE_KEY)->update([
                'content' => Json::encode($data)
            ]);
        } else {
            BookMetaEntity::query()->insert([
                'target_id' => $book,
                'name' => BookRepository::META_ROLE_KEY,
                'content' => Json::encode($data)
            ]);
        }
    }
}