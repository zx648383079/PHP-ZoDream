<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Scene\SceneInterface;
use Zodream\Database\Relation;
use Zodream\Helpers\Time;

class ContentRepository {
    public static function getList(
        int $site, int $category, string $keywords = '', int $parent = 0, int $page = 1, int $perPage = 20) {
        $scene = CategoryRepository::apply($site, $category);
        $column = static::searchField($scene);
        $page = $scene->search($keywords, [
            'cat_id' => $category,
            'parent_id' => $parent
        ], 'id desc', $page, $perPage, implode(',', array_column($column, 'name')));
        $data = $page->toArray();
        if (!empty($data['data'])) {
            static::formatValue($data['data']);
            $data['data'] = Relation::create($data['data'], [
                'category' => [
                    'query' => CategoryModel::query()->select('id', 'title'),
                    'link' => ['cat_id', 'id'],
                    'type' => Relation::TYPE_ONE
                ]
            ]);
        }
        $data['column'] = $column;
        return $data;
    }

    protected static function formatValue(array &$items) {
        foreach ($items as &$item) {
            foreach ([
                'updated_at', 'created_at'
                     ] as $key) {
                if (isset($item[$key])) {
                    $item[$key] = Time::format($item[$key]);
                }
            }
        }
    }

    protected static function searchField(SceneInterface $scene) {
        $column = [
            [
                'name' => 'id',
                'label' => 'ID',
            ],
            [
                'name' => 'cat_id',
                'label' => '分类',
            ]
        ];
        foreach ($scene->fieldList() as $item) {
            if ($item->is_disable) {
                continue;
            }
            if (($item->is_system && $item->field === 'title') || $item->is_search) {
                $column[] = [
                    'name' => $item->field,
                    'label' => $item->name,
                ];
            }
        }
        return array_merge($column, [
            [
                'name' => 'status',
                'label' => '状态',
            ],
            [
                'name' => 'view_count',
                'label' => '浏览数',
            ],
            [
                'name' => 'created_at',
                'label' => '发布时间',
            ],
            [
                'name' => 'updated_at',
                'label' => '更新时间',
            ],
        ]);
    }

    public static function get(int $site, int $category, int $id = 0) {
        $scene = CategoryRepository::apply($site, $category);
        return $scene->find($id);
    }

    public static function form(array $data, SceneInterface $scene) {
        $tabItems = ModelRepository::fieldGroupByTab($scene->modelId());
        foreach ($tabItems as $i => $group) {
            foreach ($group['items'] as $j => $item) {
                $tabItems[$i]['items'][$j] = $scene->toInput($item, $data, true);
            }
        }
        return $tabItems;
    }

    public static function getForm(int $site, int $category, int $id = 0) {
        $data = $id < 1 ? compact('id') : static::get($site, $category, $id);
        if ($id < 1) {
            CategoryRepository::apply($site, $category);
        }
        $data['form_data'] = static::form($data, CMSRepository::scene());
        return $data;
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