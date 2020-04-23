<?php
namespace Module\Blog\Domain\Repositories;


use Module\Blog\Domain\Model\TagModel;

class TagRepository {

    public static function get() {
        return TagModel::query()->get();
    }
}