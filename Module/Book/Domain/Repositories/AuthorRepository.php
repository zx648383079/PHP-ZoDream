<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;

class AuthorRepository {
    public static function getList(string $keywords = '') {
        return BookAuthorModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return BookAuthorModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookAuthorModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        BookAuthorModel::where('id', $id)->delete();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            BookAuthorModel::query()->select('id', 'name', 'avatar'),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function profile(int $id) {
        return static::appendProfile(static::get($id));
    }

    protected static function appendProfile(BookAuthorModel $model) {
        $model->book_count = 0;
        $model->word_count = 0;
        $model->collect_count = 0;
        if ($model->id > 0) {
            $data = BookModel::query()->where('author_id', $model->id)
                ->selectRaw('COUNT(id) as count, SUM(size) as size')
                ->asArray()
                ->first();
            $model->book_count = intval($data['count']);
            $model->word_count = intval($data['size']);
        }
        return $model;
    }

    public static function profileByAuth() {
        $model = BookAuthorModel::where('user_id', auth()->id())
            ->first();
        if (empty($model)) {
            $user = auth()->user();
            $model = new BookAuthorModel([
                'name' => $user->name,
                'avatar' => $user->avatar,
            ]);
        }
        return static::appendProfile($model);
    }

    public static function authAuthor(): int {
        $id = intval(BookAuthorModel::where('user_id', auth()->id())->value('id'));
        if ($id > 0) {
            return $id;
        }
        $user = auth()->user();
        $model = static::save([
            'name' => $user->name,
            'avatar' => $user->avatar,
            'description' => '',
            'user_id' => $user->id,
        ]);
        return intval($model->id);
    }
}