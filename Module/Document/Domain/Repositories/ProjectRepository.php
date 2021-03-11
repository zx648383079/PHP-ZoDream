<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;


use Domain\Model\SearchModel;
use Module\Document\Domain\Model\ProjectModel;

class ProjectRepository {

    public static function getList(string $keywords = '') {
        return ProjectModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->page();
    }


    public static function all() {
        return ProjectModel::where('user_id', auth()->id())
            ->orderBy('id', 'asc')
            ->get('id', 'name');
    }
}