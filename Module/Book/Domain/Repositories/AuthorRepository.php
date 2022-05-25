<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Database\Contracts\SqlBuilder;

class AuthorRepository extends CRUDRepository {

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

    protected static function query(): SqlBuilder
    {
        return BookAuthorModel::query();
    }

    protected static function createNew(): Model
    {
        return new BookAuthorModel();
    }
}