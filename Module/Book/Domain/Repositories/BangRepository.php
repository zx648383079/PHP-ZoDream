<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookModel;
use Zodream\Database\Contracts\SqlBuilder;

class BangRepository {

    public static function recommend(int $limit = 4) {
        return static::query()->orderBy('recommend_count', 'desc')->limit($limit)->get();
    }

    public static function hot(int $limit = 10) {
        return static::query()->orderBy('click_count', 'desc')->limit($limit)->get();
    }

    public static function newRecommend(int $limit = 12) {
        return static::query()->where('size', '<', 100000)
            ->orderBy('created_at', 'desc')
            ->orderBy('recommend_count', 'desc')->limit($limit)->get();
    }

    public static function weekClick(int $limit = 5) {
        return static::query()->orderBy('click_count', 'desc')->limit($limit)->get();
    }

    public static function weekRecommend(int $limit = 5) {
        return static::query()->orderBy('recommend_count', 'desc')->limit($limit)->get();
    }

    public static function monthClick(int $limit = 5) {
        return static::query()->orderBy('click_count', 'desc')->limit($limit)->get();
    }

    public static function monthRecommend(int $limit = 5) {
        return static::query()->orderBy('recommend_count', 'desc')->limit($limit)->get();
    }

    public static function click(int $limit = 5) {
        return static::query()->orderBy('click_count', 'desc')->limit($limit)->get();
    }

    public static function size(int $limit = 5) {
        return static::query()->orderBy('size', 'desc')->limit($limit)->get();
    }

    public static function over(int $limit = 5) {
        return static::query()->where('over_at > 0')->orderBy('click_count', 'desc')->limit($limit)->get();
    }

    public static function query(): SqlBuilder {
        return BookModel::with('category', 'author')->ofClassify()->isOpen();
    }
}