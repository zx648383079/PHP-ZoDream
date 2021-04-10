<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\Auth\Domain\Events\ManageAction;

class ContentRepository {
    public static function getList(int $site, int $category, string $keywords = '', int $parent = 0) {
        $scene = CategoryRepository::apply($site, $category);
        return $scene->search($keywords, [
            'cat_id' => $category,
            'parent_id' => $parent
        ], 'id desc', 0, 20, '');
    }

    public static function get(int $site, int $category, int $id = 0) {
        $scene = CategoryRepository::apply($site, $category);
        return $scene->find($id);
    }

    public static function save(int $site, int $category, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $scene = CategoryRepository::apply($site, $category);
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $scene->insert($data);
        }
        event(new ManageAction('cms_content_edit', '', 33, $id));
        if ($scene->hasError()) {
            throw new \Exception($scene->getFirstError());
        }
    }

    public static function remove(int $site, int $category, int $id) {
        $scene = CategoryRepository::apply($site, $category);
        $scene->remove($id);
    }
}